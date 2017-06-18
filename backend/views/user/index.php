<?php
/* @var $this yii\web\View */
// view层

?>

    <h1>用户列表</h1>
<?=\yii\bootstrap\Html::a('添加用户','create',['class'=>'btn btn-success'])?>
<?= \yii\bootstrap\Html::endForm() ?>
    <table class="table">
        <tr>
            <th>id</th>
            <th>用户名</th>
            <th>密码</th>
            <th>最后登录时间</th>
            <th>最后登录ip</th>
            <th>角色</th>
            <th>操作</th>
        </tr>
        <?php foreach($users as $user):?>
            <tr>
                <td><?=$user->id?></td>
                <td><?=$user->username?></td>
                <td><?=$user->password_hash?></td>
                <td><?=date('Y-m-d H:i:s',$user->last_login_time)?></td>
                <td><?=$user->last_login_ip?></td>
                <td><?php
                    foreach (Yii::$app->authManager->getRolesByUser($user->id) as $role){
                        echo $role->name;
                        echo '  ';
                    }
                    ?></td>
                <td>
                    <?=\yii\bootstrap\Html::a('编辑',['user/update','id'=>$user->id],['class'=>'btn btn-success'])?>
                    <?=\yii\bootstrap\Html::a('删除',['user/delete','id'=>$user->id],['class'=>'btn btn-danger'])?>
                </td>
            </tr>
        <?php endforeach;?>
    </table>
<?=\yii\widgets\LinkPager::widget(['pagination'=>$pager])?>