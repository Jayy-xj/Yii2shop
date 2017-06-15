<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = '商品分类列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-category-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('添加商品分类', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,

        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            [
                "attribute" => "name",
                "value" => function ($dataProvider) {
                   return str_repeat('- ',$dataProvider['depth']).$dataProvider['name'];
                }
            ],
            [
                "attribute" => "parent_id",
                "value" => function ($dataProvider) {
                    $num=\backend\models\GoodsCategory::findOne($dataProvider['parent_id']);
                    if ($num){
                        return $num['name'];
                    }else{
                        return '顶级分类';
                    }
                }
            ],
            'intro:ntext',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
