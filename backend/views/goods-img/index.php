<?php
/* @var $this yii\web\View */
// view层

?>

    <h1>图片列表</h1>
<?=\yii\bootstrap\Html::a('添加图片',['create','goods_id'=>$goods_id],['class'=>'btn btn-success'])?>
<?= \yii\bootstrap\Html::endForm() ?>
    <table class="table">
        <tr>
            <th>id</th>
            <th>图片</th>
            <th>操作</th>
        </tr>
        <?php foreach($goods_imgs as $goods_img):?>
            <tr>
                <td><?=$goods_img->id?></td>
                <td><?=\yii\bootstrap\Html::img($goods_img->path,['height'=>20])?></td>
                <td>
                    <?=\yii\bootstrap\Html::a('删除',['goods-img/delete','id'=>$goods_img->id],['class'=>'btn btn-danger'])?>
                </td>
            </tr>
        <?php endforeach;?>
    </table>
<?=\yii\widgets\LinkPager::widget(['pagination'=>$pager])?>