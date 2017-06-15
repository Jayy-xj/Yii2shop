<?php $form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($goods_imgs,'path')->hiddenInput();
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
        $("#goodsimg-path").val(data.fileUrl);
    }
}
EOF
        ),
    ]
]);
if($goods_imgs->path){
    echo \yii\helpers\Html::img('@web'.$goods_imgs->path,['height'=>50]);
}else{
    echo \yii\helpers\Html::img('',['style'=>'display:none','id'=>'img_logo','height'=>'50']);
};
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();