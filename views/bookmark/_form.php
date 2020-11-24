<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var app\forms\BookmarkForm $bookmarkForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bookmark-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($bookmarkForm, 'url')->textInput(['maxlength' => true]) ?>

    <?php //echo $form->field($bookmarkForm, 'password')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
