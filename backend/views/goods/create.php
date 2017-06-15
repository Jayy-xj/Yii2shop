<?php $form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($goods,'name');
echo $form->field($goods,'logo')->hiddenInput();
echo \yii\bootstrap\Html::fileInput('test',null,['id'=>'test']);
echo \xj\uploadify\Uploadify::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'test',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'width' => 120,
        'height' => 40,
        'onUploadError' => new \yii\web\JsExpression(<<<EOF
function(file, errorCode, errorMsg, errorString) {
    console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
}
EOF
        ),
        'onUploadSuccess' => new \yii\web\JsExpression(<<<EOF
function(file, data, response) {
    data = JSON.parse(data);
    if (data.error) {
        console.log(data.msg);
    } else {
        console.log(data.fileUrl);
        //将上传成功后的图片地址(data.fileUrl)写入img标签
        $("#img_logo").attr("src",data.fileUrl).show();
        //将上传成功后的图片地址(data.fileUrl)写入logo字段
        $("#goods-logo").val(data.fileUrl);
    }
}
EOF
        ),
    ]
]);
if($goods->logo){
    echo \yii\helpers\Html::img('@web'.$goods->logo,['height'=>50]);
}else{
    echo \yii\helpers\Html::img('',['style'=>'display:none','id'=>'img_logo','height'=>'50']);
};
echo $form->field($goods,'goods_category_id')->dropDownList(\backend\models\Goods::getCategoryOptions(),['prompt'=>'=请选择分类=']);
echo $form->field($goods,'brand_id')->dropDownList(\backend\models\Goods::getBrandOptions(),['prompt'=>'=请选择品牌=']);
echo $form->field($goods,'market_price');
echo $form->field($goods,'shop_price');
echo $form->field($goods_intro,'content')->widget('kucha\ueditor\UEditor',[]);
echo $form->field($goods,'stock');
echo $form->field($goods,'is_on_sale')->radioList(\backend\models\Goods::$sale_options);
echo $form->field($goods,'status')->radioList(\backend\models\Goods::$status_options);
echo $form->field($goods,'sort');
//echo $form->field($goods,'content')->textarea();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();