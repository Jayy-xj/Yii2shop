<?php
/* @var $this yii\web\View */
// view层

?>

    <h1>商品列表</h1>
<?=\yii\bootstrap\Html::a('添加商品','create',['class'=>'btn btn-success'])?>
<?= \yii\bootstrap\Html::beginForm(['goods/serch'], 'get', ['enctype' => 'multipart/form-data']) ?>
<?= \yii\bootstrap\Html::textInput('data','',['placeholder'=>'名称、货号、id查询'])?>
<?=\yii\bootstrap\Html::submitButton('搜索',['class'=>'btn btn-sm btn-info']);?>
<?= \yii\bootstrap\Html::endForm() ?>
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
        <?php foreach($goods as $good):?>
            <tr>
                <td><?=$good->id?></td>
                <td><?=$good->name?></td>
                <td><?=$good->sn?></td>
                <td><?=\yii\bootstrap\Html::img($good->logo,['height'=>20])?></td>
                <td><?=$good->goodsCategory->name?></td>
                <td><?=$good->brand->name?></td>
                <td><?=$good->market_price?></td>
                <td><?=$good->shop_price?></td>
                <td><?=$good->stock?></td>
                <td><?=$good->is_on_sale?'在售':'下架'?></td>
                <td><?=$good->status?'正常':'回收'?></td>
                <td><?=date('Y-m-d,H:m:s',$good->create_time)?></td>
                <td>
                    <?=\yii\bootstrap\Html::a('编辑',['goods/update','id'=>$good->id],['class'=>'btn btn-success'])?>
                    <?=\yii\bootstrap\Html::a('查看',['goods/view','id'=>$good->id],['class'=>'btn btn-info'])?>
                    <?=\yii\bootstrap\Html::a('删除',['goods/delete','id'=>$good->id],['class'=>'btn btn-danger'])?>
                    <?=\yii\bootstrap\Html::a('相册列表',['goods-img/index','goods_id'=>$good->id],['class'=>'btn btn-warning'])?>
                </td>
            </tr>
        <?php endforeach;?>
    </table>
<?=\yii\widgets\LinkPager::widget(['pagination'=>$pager])?>