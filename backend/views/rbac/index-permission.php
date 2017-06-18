<h1>权限列表</h1>
<?=\yii\bootstrap\Html::a('添加权限','add-permission',['class'=>'btn btn-success'])?>
<table class="table table-responsive table-bordered">
    <tr>
        <th>权限名称</th>
        <th>权限描述</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
        <tr>
            <td><?=$model->name?></td>
            <td><?=$model->description?></td>
            <td><?=\yii\bootstrap\Html::a('修改',['rbac/edit-permission','name'=>$model->name],['class'=>'btn btn-info '])?>
                <?=\yii\bootstrap\Html::a('删除',['rbac/delete-permission','name'=>$model->name],['class'=>'btn btn-warning '])?></td>
        </tr>
    <?php endforeach;?>
</table>

