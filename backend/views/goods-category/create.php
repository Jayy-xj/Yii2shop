<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\GoodsCategory */

$this->title = '添加商品分类';
$this->params['breadcrumbs'][] = ['label' => '商品分类列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-category-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'categories'=>$categories,
        'upload' => [
            'class' => 'kucha\ueditor\UEditorAction',
        ],
    ]) ?>

</div>
