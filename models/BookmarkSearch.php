<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * BookmarkSearch represents the model behind the search form of `app\models\Bookmark`.
 */
class BookmarkSearch extends Bookmark
{
    public $search_content;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['favicon', 'url', 'title', 'meta_description', 'meta_keywords', 'password_hash', 'created_at', 'search_content'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return array_merge(['search_content' => 'Поиск', parent::attributeLabels()]);
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Bookmark::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
        ]);

        if (!empty($this->search_content)) {
            $query->andWhere("MATCH(url, title, meta_description, meta_keywords) AGAINST(:search_content IN NATURAL LANGUAGE MODE)", ['search_content' => $this->search_content]);
        }

        $query->andFilterWhere(['like', 'favicon', $this->favicon])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'meta_description', $this->meta_description])
            ->andFilterWhere(['like', 'meta_keywords', $this->meta_keywords])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash]);

        return $dataProvider;
    }
}
