<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bookmark".
 *
 * @property int $id
 * @property resource|null $favicon
 * @property string|null $url
 * @property string|null $title
 * @property string|null $meta_description
 * @property string|null $meta_keywords
 * @property string|null $password_hash
 * @property string $created_at
 */
class Bookmark extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bookmark';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['favicon'], 'string'],
            [['created_at'], 'safe'],
            [['url', 'title', 'meta_description', 'meta_keywords', 'password_hash'], 'string', 'max' => 255],
            [['favicon', 'url', 'title', 'meta_description', 'meta_keywords'], 'filter', 'filter' => 'trim'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'favicon' => 'Favicon',
            'url' => 'URL страницы',
            'title' => 'Заголовок страницы',
            'meta_description' => 'Meta Description',
            'meta_keywords' => 'Meta Keywords',
            'password_hash' => 'Хэш пароля',
            'created_at' => 'Дата добавления',
        ];
    }
}
