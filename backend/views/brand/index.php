<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '品牌列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="brand-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('添加品牌', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'intro:ntext',
            [
                'attribute' => 'logo',
                'content' => function($dataProvider){
                    return \yii\bootstrap\Html::img($dataProvider['logo'],['width'=>50]);
                },
            ],
            'sort',
            [
                'attribute' => 'status',
                'content' => function($dataProvider){
                    return \backend\models\Brand::$statusOptions[$dataProvider['status']] ;
                },

            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
