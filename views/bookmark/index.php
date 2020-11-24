<?php

use app\models\Bookmark;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BookmarkSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Закладки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bookmark-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить закладку', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Скачать excel', ['download-excel'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'created_at',
            [
                'attribute' => 'favicon',
                'format' => 'raw',
                'value' => static function(Bookmark $bookmark) {
                    return Html::img($bookmark->favicon, ['style' => 'width: 16px']);
                },
            ],
            'url:url',
            'title',
            'meta_description',
            'meta_keywords',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}{delete}',
            ],
        ],
    ]); ?>


</div>
