<?php
/* @var $this yii\web\View */
// view层

?>

    <h1>订单列表</h1>
    <table class="table">
        <tr>
            <th>id</th>
            <th>收货人姓名</th>
            <th>收货地址</th>
            <th>电话</th>
            <th>付款方式</th>
            <th>快递方式</th>
            <th>状态</th>
            <th>订单号</th>
            <th>创建时间</th>
            <th>操作</th>
        </tr>
        <?php foreach($orders as $order):?>
            <tr>
                <td><?=$order->id?></td>
                <td><?=$order->name?></td>
                <td><?=$order->province.$order->city.$order->area.$order->address?></td>
                <td><?=$order->tel?></td>
                <td><?=$order->payment_name?></td>
                <td><?=$order->delivery_name?></td>
                <td><?=$order->status?'已发货':'待发货'?></td>
                <td><?=$order->trade_no?></td>
                <td><?=date('Y-m-d,H:m:s',$order->create_time)?></td>
                <td>
                    <?=\yii\bootstrap\Html::a('发货',['goods/update','id'=>$order->id],['class'=>'btn btn-success'])?>
                    <?=\yii\bootstrap\Html::a('取消订单',['goods/esc','id'=>$order->id],['class'=>'btn btn-warning'])?>
                </td>
            </tr>
        <?php endforeach;?>
    </table>
<?=\yii\widgets\LinkPager::widget(['pagination'=>$pager])?>