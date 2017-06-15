<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Article */

$this->title = '修改文章: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Articles', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="article-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'detail' => $detail,
        'article_category'=> $article_category,
        'upload' => [
            'class' => 'kucha\ueditor\UEditorAction',
        ],
    ]) ?>

</div>
