<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '文章分类列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-category-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('添加分类', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'intro:ntext',
            'sort',
            [
                'attribute' => 'status',
                'content' => function($dataProvider){
                    return \backend\models\Articlecategory::$statusOptions[$dataProvider['status']] ;
                },

            ],
            [
                'attribute' => 'is_help',
                'content' => function($dataProvider){
                    return \backend\models\Articlecategory::$statusIs_help[$dataProvider['is_help']] ;
                },

            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
