<!-- 页面头部 start -->
<div class="header w990 bc mt15">
    <div class="logo w990">
        <h2 class="fl"><a href="index.html"><img src="/images/logo.png" alt="京西商城"></a></h2>
        <div class="flow fr">
            <ul>
                <li class="cur">1.我的购物车</li>
                <li>2.填写核对订单信息</li>
                <li>3.成功提交订单</li>
            </ul>
        </div>
    </div>
</div>
<!-- 页面头部 end -->
<div style="clear:both;"></div>
<!-- 主体部分 start -->
<div class="mycart w990 mt10 bc">
    <h2><span>我的购物车</span></h2>
    <table>
        <thead>
        <tr>
            <th class="col1">商品名称</th>
            <th class="col3">单价</th>
            <th class="col4">数量</th>
            <th class="col5">小计</th>
            <th class="col6">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php  $total=null?>
        <?php foreach($models as $model):?>
            <tr data-goods_id="<?=$model['id']?>">
                <td class="col1"><a href=""><?=\yii\helpers\Html::img('http://admin.yii2shop.com/'.$model['logo'])?></a>  <strong><a href=""><?=$model['name']?></a></strong></td>
                <td class="col3">￥<span><?=$model['shop_price']?></span></td>
                <td class="col4">
                    <a href="javascript:;" class="reduce_num"></a>
                    <input type="text" name="amount" value="<?=$model['amount']?>" class="amount"/>
                    <a href="javascript:;" class="add_num"></a>
                </td>
                <td class="col5">￥<span><?=($model['shop_price']*$model['amount'])?></span></td>
                <td class="col6"><a href="javascript:;" class="del_goods">删除</a></td>
            </tr>
            <?php $total+=($model['shop_price']*$model['amount'])?>
        <?php endforeach;?>


        </tbody>
        <tfoot>
        <tr>
            <td colspan="6">购物金额总计： <strong>￥ <?=$total?></strong></td>
        </tr>
        </tfoot>
    </table>
    <div class="cart_btn w990 bc mt10">
        <a href="" class="continue">继续购物</a>
        <a href="<?=\yii\helpers\Url::to(['order/order'])?>" class="checkout">结 算</a>
    </div>
</div>
<!-- 主体部分 end -->
<?php
$url = \yii\helpers\Url::to(['goods/update-cart']);
$token = Yii::$app->request->csrfToken;
$this->registerJs(new \yii\web\JsExpression(
    <<<JS
           //监听+ - 按钮的点击事件
        $(".reduce_num,.add_num").click(function(){
            //console.log($(this));
            var goods_id = $(this).closest('tr').attr('data-goods_id');
            var amount = $(this).parent().find('.amount').val();
            //发送ajax post请求到site/update-cart  {goods_id,amount}
            $.post("$url",{goods_id:goods_id,amount:amount,"_csrf-frontend":"$token"});
        });
        //删除按钮
        $(".del_goods").click(function(){
            if(confirm('是否删除该商品')){
                var goods_id = $(this).closest('tr').attr('data-goods_id');
                //发送ajax post请求到site/update-cart  {goods_id,amount}
                $.post("$url",{goods_id:goods_id,amount:0,"_csrf-frontend":"$token"});
                //删除当前商品的标签
                $(this).closest('tr').remove();
            }
        });
JS

));