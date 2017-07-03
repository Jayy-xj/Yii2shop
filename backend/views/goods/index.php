<?php
/* @var $this yii\web\View */
// view层

?>

    <h1>商品列表</h1>
<?=\yii\bootstrap\Html::a('添加商品','create',['class'=>'btn btn-success'])?>
<?php
$form = \yii\bootstrap\ActiveForm::begin([
    'method' => 'get',
    //get方式提交,需要显式指定action
    'action'=>\yii\helpers\Url::to(['goods/index']),
    'options'=>['class'=>'form-inline']
]);
echo $form->field($model,'name')->textInput(['placeholder'=>'商品名','name'=>'keyword'])->label(false);
echo $form->field($model,'sn')->textInput(['placeholder'=>'货号'])->label(false);
echo $form->field($model,'minPrice')->textInput(['placeholder'=>'￥'])->label(false);
echo $form->field($model,'maxPrice')->textInput(['placeholder'=>'￥'])->label('-');
echo \yii\bootstrap\Html::submitButton('搜索');
\yii\bootstrap\ActiveForm::end();
?>
    <table class="table">
        <tr>
            <th>id</th>
            <th>名称</th>
            <th>货号</th>
            <th>logo</th>
            <th>商品分类</th>
            <th>品牌</th>
            <th>市场价格</th>
            <th>商品价格</th>
            <th>库存</th>
            <th>是否在售</th>
            <th>状态</th>
            <th>创建时间</th>
            <th>操作</th>
        </tr>
        <?php foreach($models as $model):?>
            <tr>
                <td><?=$model->id?></td>
                <td><?=$model->name?></td>
                <td><?=$model->sn?></td>
                <td><?=\yii\bootstrap\Html::img($model->logo,['height'=>20])?></td>
                <td><?=$model->goodsCategory->name?></td>
                <td><?=$model->brand->name?></td>
                <td><?=$model->market_price?></td>
                <td><?=$model->shop_price?></td>
                <td><?=$model->stock?></td>
                <td><?=$model->is_on_sale?'在售':'下架'?></td>
                <td><?=$model->status?'正常':'回收'?></td>
                <td><?=date('Y-m-d,H:m:s',$model->create_time)?></td>
                <td>
                    <?=\yii\bootstrap\Html::a('编辑',['goods/update','id'=>$model->id],['class'=>'btn btn-success'])?>
                    <?=\yii\bootstrap\Html::a('查看',['goods/view','id'=>$model->id],['class'=>'btn btn-info'])?>
                    <?=\yii\bootstrap\Html::a('删除',['goods/delete','id'=>$model->id],['class'=>'btn btn-danger'])?>
                    <?=\yii\bootstrap\Html::a('相册列表',['goods-img/index','goods_id'=>$model->id],['class'=>'btn btn-warning'])?>
                </td>
            </tr>
        <?php endforeach;?>
    </table>
<?=\yii\widgets\LinkPager::widget(['pagination'=>$pager])?>