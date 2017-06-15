<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ArticleCategory */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '文章分类列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-category-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('修改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '你确定要删除此分类吗?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'intro:ntext',
            'sort',
            [
                'attribute'=>'status',
                'value' =>  function($dataProvider){
                    return \backend\models\ArticleCategory::$statusOptions[$dataProvider['status']] ;
                },
            ],
            [
                'attribute'=>'is_help',
                'value' =>  function($dataProvider){
                    return \backend\models\ArticleCategory::$statusOptions[$dataProvider['is_help']] ;
                },
            ],
        ],
    ]) ?>

</div>
