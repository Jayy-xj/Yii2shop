<?php $form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($users,'username');
echo $form->field($users,'oldpassword')->passwordInput();
echo $form->field($users,'newpassword')->passwordInput();
echo $form->field($users,'roles')->checkboxList(\backend\models\User::getRoleOption());
echo \yii\bootstrap\Html::submitButton('修改',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();