<?php $form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($users,'username');
echo $form->field($users,'password_hash')->passwordInput();
echo $form->field($users,'roles')->checkboxList(\backend\models\User::getRoleOption());
echo \yii\bootstrap\Html::submitButton('注册',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();