<?php

namespace frontend\controllers;

use backend\models\Goods;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Order;
use frontend\models\OrderGoods;
use yii\db\Exception;

class OrderController extends \yii\web\Controller
{
    public function actionOrder()
    {
        if(\Yii::$app->user->isGuest){
            return $this->redirect(['member/login']);
        }
        $member_id=\Yii::$app->user->id;
        $this->layout = 'cart';
        //收货地址
        $queres=Address::find()->where(['member_id'=>$member_id])->all();
        //发货方式
        $send_ways=Order::$send_ways;
        //付款方式
        $pay_ways=Order::$pay_ways;
//        var_dump($send_ways);exit;
        //购物车商品信息开始
        $model=new Cart();
        $member_id=\Yii::$app->user->id;
        $carts=$model::find()->where(['member_id'=>$member_id])->asArray()->all();
        $models = [];
        foreach ($carts as $cart){
//                var_dump($cart['id']);
            $cart_id=$cart['goods_id'];
            $goods = Goods::findOne(['id' => $cart_id])->attributes;
            $goods['amount'] = $cart['amount'];
            $models[] = $goods;
        }
        //购物车商品信息结束
        return $this->render('order',['queres'=>$queres,'send_ways'=>$send_ways,'pay_ways'=>$pay_ways,'models'=>$models]);
    }
public function actionAdd(){
    $transaction=\Yii::$app->db->beginTransaction();
    try{
        \Yii::$app->user->setReturnUrl(\Yii::$app->request->referrer);
        $goods_ids=\Yii::$app->request->post('goods_id');
        $totals=\Yii::$app->request->post('totals');
        $address_id=\Yii::$app->request->post('address_id');
        $delivery_id=\Yii::$app->request->post('delivery');
        $pay_id=\Yii::$app->request->post('pay');
        $order=new Order();
        $order->member_id=\Yii::$app->user->id;//member_id
        $address=Address::findOne(['id'=>$address_id]);
        $order->name=$address->username;//name
        $order->tel=$address->tel;//tel
        $province_id=$address->province;
        $city_id=$address->city;
        $district_id=$address->district;
        $province=\frontend\models\Locations::getProvince($province_id);
        $city=\frontend\models\Locations::getProvince($city_id);
        $district=\frontend\models\Locations::getProvince($district_id);
        $order->province=$province;//province
        $order->city=$city;//city
        $order->area= $district;//area
        $order->address=$address->detail;//address
        $order->delivery_id=$delivery_id;//delivery_id
        $order->delivery_name=Order::$send_ways[$delivery_id]['delivery_name'];
        $order->delivery_price=Order::$send_ways[$delivery_id]['delivery_price'];
        $order->payment_id=$pay_id;
        $order->payment_name=Order::$pay_ways[$pay_id]['payment_name'];
        $order->total=$totals-$order->delivery_price;
        $order->create_time=time();
        $order->status=0;
        $order->trade_no=date('YmdHis',time()).rand(100,900);
        if($order->save()){
            $order_id=\Yii::$app->db->getLastInsertId();
            $order_goods2=new OrderGoods();
            foreach ($goods_ids as $goods_id){
                $order_goods=clone $order_goods2;
                $order_goods->order_id=$order_id;
                $order_goods->goods_id=$goods_id;
                $amount=Cart::findOne(['goods_id'=>$goods_id,'member_id'=>\Yii::$app->user->id])->amount;
                $goods=Goods::findOne(['id'=>$goods_id]);
                $order_goods->goods_name= $goods->name;
                $order_goods->goods_logo= $goods->logo;
                $order_goods->price= $goods->shop_price;
                $total= $amount*$order_goods->price;
                $order_goods->amount= $amount;
                $order_goods->total=$total;
//            if(!$order_goods->isNewRecord){
//                $order_goods->isNewRecord=true;
//                $order_goods->id=null;
//            }
                if ($order_goods->save()){
                    $stock=Goods::findOne(['id'=>$goods_id]);
                    $stock->stock=$stock->stock-$amount;
                    if ($stock->stock>=0){
                        $stock->save();
                        if ($cart=Cart::findOne(['goods_id'=>$goods_id,'member_id'=>\Yii::$app->user->id])){
                            $cart->delete();
                        }
                    }else{
                        throw new \Exception('库存不足');
                    }

                }else{
                    throw new \Exception('添加失败');
                }
            }
        }else{
            return $this->goBack();
        }
        $transaction->commit();//提交事务会真正的执行数据库操作
    }catch (Exception $e) {
        $transaction->rollback();//如果操作失败, 数据回滚
        echo $e->getMessage();
    }
    return $this->redirect(['goods/index']);
    }
}
