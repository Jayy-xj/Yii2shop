<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\GoodsCategory */

$this->title = '修改商品分类: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '商品分类列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="goods-category-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'categories'=>$categories,
        'upload' => [
            'class' => 'kucha\ueditor\UEditorAction',
        ],
    ]) ?>

</div>
