<?php
/* @var $this yii\web\View */
// view层
?>
    <h1>菜单列表</h1>
<?=\yii\bootstrap\Html::a('添加菜单','create',['class'=>'btn btn-success'])?>
<?= \yii\bootstrap\Html::endForm() ?>
    <table class="table">
        <tr>
            <th>id</th>
            <th>名称</th>
            <th>路由</th>
            <th>上级菜单</th>
            <th>排序</th>
            <th>操作</th>
        </tr>
        <?php foreach($menus as $menu):?>
            <tr>
                <td><?=$menu->id?></td>
                <td><?=$menu->label?></td>
                <td><?=$menu->url?></td>
                <td><?=$menu->parent_id?></td>
                <td><?=$menu->sort?></td>
                <td>
                    <?=\yii\bootstrap\Html::a('编辑',['menu/update','id'=>$menu->id],['class'=>'btn btn-success'])?>
                    <?=\yii\bootstrap\Html::a('删除',['menu/delete','id'=>$menu->id],['class'=>'btn btn-danger'])?>
                </td>
            </tr>
        <?php endforeach;?>
    </table>
<?=\yii\widgets\LinkPager::widget(['pagination'=>$pager])?>