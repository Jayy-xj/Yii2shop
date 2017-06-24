<?php $form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($menus,'label');
echo $form->field($menus,'url');
echo $form->field($menus,'parent_id')->dropDownList(\backend\models\Menu::getParentOptions(),['prompt'=>'=请选择分类=']);
echo $form->field($menus,'sort');
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();