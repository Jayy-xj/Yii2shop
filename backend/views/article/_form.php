<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Article */
/* @var $detail backend\models\ArticleDetail */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="article-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'intro')->textInput() ?>
    <?= $form->field($detail,'content')->widget('kucha\ueditor\UEditor',[]); ?>

    <?=$form->field($model,'article_category_id')->dropDownList(\yii\helpers\ArrayHelper::map($article_category,'id','name'),['prompt'=>'请选择分类'])?>

    <?= $form->field($model, 'sort')->textInput() ?>

    <?= $form->field($model, 'status')->radioList([-1=>'删除',1=>'显示',0=>'隐藏']) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
