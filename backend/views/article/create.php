<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Article */
/* @var $detail backend\models\ArticleDetail */

$this->title = '添加文章';
$this->params['breadcrumbs'][] = ['label' => '文章列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-create">

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
