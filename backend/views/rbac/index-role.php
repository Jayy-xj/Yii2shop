<h1>角色列表</h1>
<?=\yii\bootstrap\Html::a('添加角色','add-role',['class'=>'btn btn-success'])?>
<table class="table table-responsive table-bordered">
    <tr>
        <th>角色名称</th>
        <th>角色描述</th>
        <th>权限</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
        <tr>
            <td><?=$model->name?></td>
            <td><?=$model->description?></td>
            <td><?php
                foreach (Yii::$app->authManager->getPermissionsByRole($model->name) as $permission){
                    echo $permission->description;
                    echo '  ';
                }
                ?></td>
            <td><?=\yii\bootstrap\Html::a('修改',['rbac/edit-role','name'=>$model->name],['class'=>'btn btn-info '])?>
                <?=\yii\bootstrap\Html::a('删除',['rbac/delete-role','name'=>$model->name],['class'=>'btn btn-warning '])?></td>
        </tr>
    <?php endforeach;?>
</table>
