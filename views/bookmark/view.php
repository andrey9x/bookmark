<?php

use app\models\Bookmark;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Bookmark */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Bookmarks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="bookmark-view">

    <h1><?= Html::encode($model->url) ?></h1>

    <p>
        <?= Html::a('Список', ['index'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'id',
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
        ],
    ]) ?>

</div>
