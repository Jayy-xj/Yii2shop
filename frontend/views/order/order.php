<!-- 页面头部 start -->

<div class="header w990 bc mt15">
    <div class="logo w990">
        <h2 class="fl"><a href="index.html"><img src="/images/logo.png" alt="京西商城"></a></h2>
        <div class="flow fr flow2">
            <ul>
                <li>1.我的购物车</li>
                <li class="cur">2.填写核对订单信息</li>
                <li>3.成功提交订单</li>
            </ul>
        </div>
    </div>
</div>
<!-- 页面头部 end -->

<div style="clear:both;"></div>
<form action="<?=\yii\helpers\Url::to(['order/add'])?>  " method="post">
<!-- 主体部分 start -->
<div class="fillin w990 bc mt15">
    <div class="fillin_hd">
        <h2>填写并核对订单信息</h2>
    </div>
    <div class="fillin_bd">
        <!-- 收货人信息  start-->
        <div class="address">
            <h3>收货人信息</h3>
            <div class="address_info">
                <?php foreach ($queres as $query) {
                    if ($query->status==1){
                        echo '<p>';
                        echo '<input type="radio" checked value="' . $query->id . '" name="address_id"/>' . $query->username . '&nbsp;' . $query->tel . '&nbsp;' . \frontend\models\Locations::getProvince($query->province) . '&nbsp;' . \frontend\models\Locations::getCity($query->city) . '&nbsp;' . \frontend\models\Locations::getDistrict($query->district) . '&nbsp;' . $query->detail . '&nbsp;';
                        echo '</p>';
                    }else{
                        echo '<p>';
                        echo '<input type="radio" value="' . $query->id . '" name="address_id"/>' . $query->username . '&nbsp;' . $query->tel . '&nbsp;' . \frontend\models\Locations::getProvince($query->province) . '&nbsp;' . \frontend\models\Locations::getCity($query->city) . '&nbsp;' . \frontend\models\Locations::getDistrict($query->district) . '&nbsp;' . $query->detail . '&nbsp;';
                        echo '</p>';
                    }
                }
                ?>
            </div>
        </div>
        <!-- 收货人信息  end-->

        <!-- 配送方式 start -->
        <div class="delivery">
            <h3>送货方式 </h3>
            <div class="delivery_select">
                <table>
                    <thead>
                    <tr>
                        <th class="col1">送货方式</th>
                        <th class="col2">运费</th>
                        <th class="col3">运费标准</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $send=0?>
                    <?php foreach ($send_ways as $send_way) {
                        if ($send==0){
                            echo '<tr >';
                            echo ' <td>';
                            echo ' <input type="radio" checked name="delivery" value="' . $send_way['delivery_id'] . '" />' . $send_way['delivery_name'];
                            echo '</td>';
                            echo '<td class="price">' . $send_way['delivery_price'] . '</td>';
                            echo '<td>满600包邮</td>';
                            echo '</tr>';
                        }else{
                            echo '<tr >';
                            echo ' <td>';
                            echo ' <input type="radio" name="delivery" value="' . $send_way['delivery_id'] . '" />' . $send_way['delivery_name'];
                            echo '</td>';
                            echo '<td class="price">' . $send_way['delivery_price'] . '</td>';
                            echo '<td>满600包邮</td>';
                            echo '</tr>';
                        }
                        $send++;
                    }
                    ?>
                    </tbody>
                </table>

            </div>
        </div>
        <!-- 配送方式 end -->

        <!-- 支付方式  start-->
        <div class="pay">
            <h3>支付方式 </h3>
            <div class="pay_select">
                <table>
                    <?php $pay=0?>
                    <?php foreach ($pay_ways as $pay_way) {
                        if ($pay==1){
                            echo ' <tr >';
                            echo ' <td class="col1">';
                            echo ' <input type="radio"  checked  name="pay" value="' . $pay_way['payment_id'] . '" />' . $pay_way['payment_name'];
                            echo '</td>';
                            echo '<td>'.$pay_way['payment_intro'].'</td>';
                            echo '</tr>';
                        }else{
                            echo ' <tr >';
                            echo ' <td class="col1">';
                            echo ' <input type="radio" name="pay" value="' . $pay_way['payment_id'] . '" />' . $pay_way['payment_name'];
                            echo '</td>';
                            echo '<td>'.$pay_way['payment_intro'].'</td>';
                            echo '</tr>';
                        }
                        $pay++;
                    }

                    ?>
                </table>
            </div>
        </div>
        <!-- 支付方式  end-->

        <!-- 发票信息 start-->
        <div class="receipt none">
            <h3>发票信息 </h3>
            <div class="receipt_select ">
                <form action="">
                    <ul>
                        <li>
                            <label for="">发票抬头：</label>
                            <input type="radio" name="type" checked="checked" class="personal" />个人
                            <input type="radio" name="type" class="company"/>单位
                            <input type="text" class="txt company_input" disabled="disabled" />
                        </li>
                        <li>
                            <label for="">发票内容：</label>
                            <input type="radio" name="content" checked="checked" />明细
                            <input type="radio" name="content" />办公用品
                            <input type="radio" name="content" />体育休闲
                            <input type="radio" name="content" />耗材
                        </li>
                    </ul>
                </form>

            </div>
        </div>
        <!-- 发票信息 end-->

        <!-- 商品清单 start -->
        <div class="goods">
            <h3>商品清单</h3>
            <table>
                <thead>
                <tr>
                    <th class="col1">商品</th>
                    <th class="col3">价格</th>
                    <th class="col4">数量</th>
                    <th class="col5">小计</th>
                </tr>
                </thead>
                <tbody>
                <?php  $totals=null;$total=null;$amounts=null  ?>
                <?php foreach($models as $model):?>
                <tr>

                    <td class="col1"><a href=""><?=\yii\helpers\Html::img('http://admin.yii2shop.com/'.$model['logo'])?></a>  <strong><a href=""><?=$model['name']?></a></strong></td>
                    <td class="col3">￥<?=$model['shop_price']?></td>
                    <td class="col4"><?=$model['amount']?></td>
                    <td class="col5"><span>￥<?=($model['shop_price']*$model['amount'])?></span></td>
                </tr>
                    <?php
                    $total=($model['shop_price']*$model['amount']);
                    $totals+=($model['shop_price']*$model['amount']);
                    $amounts+=$model['amount'];
                    echo '<input type="hidden" name="goods_id[]" value="'.$model['id'].'"/>'
                    ?>
                <?php endforeach;?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5">
                        <ul>
                            <li>
                                <span><?=$amounts?>件商品，总商品金额：</span>
                                <input type="hidden" name="totals" value="<?=$totals?>">
                                <em id="S-price"><?=$totals?></em>
                            </li>
                            <li>
                                <span>运费：</span>
                                <em id="Y-price"></em>
                            </li>
                            <li>
                                <span>应付总额：</span>
                                <em class="Z-price"></em>
                            </li>
                        </ul>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
        <!-- 商品清单 end -->

    </div>

    <div class="fillin_ft">

        <p>应付总额：<strong class="Z-price"></strong></p>
<!--        <a href=""><span> <input type="submit" value="提交订单" ></span></a>-->
        <input type="submit" value="提交订单" >
        <input name="_csrf-frontend" type="hidden" id="_csrf-frontend" value="<?= Yii::$app->request->csrfToken ?>">
    </div>
</div>
<!-- 主体部分 end -->
</form>
<script src="/js/jquery-1.8.3.min.js" type="text/javascript" charset="utf-8" ></script>
<script >

$("input[name='delivery']").click(function(){
     ns=$(this).closest('tr').find('td.price').text();
    $('#Y-price').text('￥'+ns);
     allprice=$('#S-price').text();
    final=(Number(allprice))-(Number(ns));
    $('.Z-price').text('￥'+final);
})
$("input[name='delivery']:first").click();
</script>