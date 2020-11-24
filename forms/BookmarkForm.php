<?php

namespace app\forms;

use app\exceptions\BusinessException;
use app\models\Bookmark;
use yii\base\Model;

class BookmarkForm extends Model
{
    public $url;
    public $password;

    public function rules()
    {
        return [
            ['url', 'required', 'message' => 'Заполните url закдадки'],
            [['url', 'password'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'url' => 'Url страницы',
            'password' => 'Пароль',
        ];
    }

    public function parse(): Bookmark
    {
        $urlObject = parse_url($this->url);
        $hostUrl = trim($urlObject['scheme'] . '://' . $urlObject['host'], '/');

        if (!isset($urlObject['scheme'])) {
            throw new BusinessException('Не валидный url, добавьте протокол');
        }

        if (!isset($urlObject['host'])) {
            throw new BusinessException('Не валидный url');
        }

        $this->url = trim($this->url, '/');

        $bookmark = Bookmark::findOne(['url' => $this->url]);
        if ($bookmark) {
            throw new BusinessException('Закладка добавлена ' . date('Y-m-d', strtotime($bookmark->created_at)));
        }

        $bookmark = new Bookmark();
        $bookmark->url = $this->url;
        if ($this->password) {
            $bookmark->password_hash = \Yii::$app->security->generatePasswordHash($this->password);
        }

        try {
            $content = file_get_contents($this->url);
        } catch (\Exception $exception) {
            throw new BusinessException('Сайт не отвечает');
        }

        $meta = get_meta_tags($this->url);
        $bookmark->meta_description = $meta['description'];
        $bookmark->meta_keywords = $meta['keywords'];

        preg_match('/<title>([^>]*)<\/title>/si', $content, $match);
        if (isset($match) && is_array($match) && count($match) > 0) {
            $bookmark->title = strip_tags($match[1]);
        }

        preg_match_all('/<link([^>]*)rel="(shortcut\s)?icon"([^>]*)>/i', $content, $linkMatches);
        if (isset($linkMatches) && is_array($linkMatches) && count($linkMatches) > 0) {
            foreach ($linkMatches[0] as $linkMatch) {
                preg_match('/href="([^"]*)"/i', $linkMatch, $match);
                if (isset($match) && is_array($match) && count($match) > 0) {
                    $favicon = $match[1];
                    if ($bookmark->favicon) {
                        $bookmarkFavicon = getimagesize($bookmark->favicon);
                    }
                    $faviconUrlObject = parse_url($favicon);
                    if (isset($faviconUrlObject['host'])) {
                        $faviconUrl = $favicon;
                    } else {
                        $faviconUrl = $hostUrl . '/' . ltrim($favicon, '/');
                    }
                    $matchFavicon = getimagesize($faviconUrl);
                    if (!$bookmark->favicon || $bookmarkFavicon[0] > $matchFavicon[0]) {
                        $bookmark->favicon = $faviconUrl;
                    }
                }
            }
        }
        if (!$bookmark->favicon) {
            if (file_get_contents($hostUrl . '/favicon.ico')) {
                $bookmark->favicon = $hostUrl . '/favicon.ico';
            }
        }

        $bookmark->save();

        return $bookmark;
    }
}