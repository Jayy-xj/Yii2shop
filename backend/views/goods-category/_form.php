<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
/**
 * @var $this \yii\web\View
 */
/* @var $this yii\web\View */
/* @var $model backend\models\GoodsCategory */
/* @var $form yii\widgets\ActiveForm */
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',['depends'=>\yii\web\JqueryAsset::className()]);
$zNodes = \yii\helpers\Json::encode($categories);
$js = new \yii\web\JsExpression(
    <<<JS
var zTreeObj;
    // zTree 的参数配置
    var setting = {
        data: {
            simpleData: {
                enable: true,
                idKey: "id",
                pIdKey: "parent_id",
                rootPId: 0
            }
        },
        callback: {
		    onClick: function(event, treeId, treeNode) {
                //选中节点的id作为parent_id
                $("#goodscategory-parent_id").val(treeNode.id);
            }
	    }
    };
    // zTree 的数据属性
    var zNodes = {$zNodes};
    zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
    zTreeObj.expandAll(true);//此属性展开所有节点
    //获取当前节点的父节点（根据id查找）
    var node = zTreeObj.getNodeByParam("id", $("#goodscategory-parent_id").val(), null);
    zTreeObj.selectNode(node);//选中当前节点的父节点
JS
);
$this->registerJs($js);
?>

<div class="goods-category-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model,'parent_id')->hiddenInput() ?>
    <?= '<ul id="treeDemo" class="ztree"></ul>'?>
    <?= $form->field($model,'intro')->widget('kucha\ueditor\UEditor',[]); ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
