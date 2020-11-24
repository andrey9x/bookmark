<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var app\forms\BookmarkForm $bookmarkForm */

$this->title = 'Добавить закладку';
$this->params['breadcrumbs'][] = ['label' => 'Bookmarks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bookmark-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'bookmarkForm' => $bookmarkForm,
    ]) ?>

</div>
