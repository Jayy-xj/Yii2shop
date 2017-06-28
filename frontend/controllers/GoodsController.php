<?php

namespace frontend\controllers;

use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsIntro;
use frontend\models\Cart;
use Yii;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;

class GoodsController extends \yii\web\Controller
{
    public $layout = 'list';
    public function actionList($goods_category_id)
    {
        $cate=GoodsCategory::findOne(['id'=>$goods_category_id]);
        $categories = GoodsCategory::findAll(['parent_id'=>$goods_category_id]);
        $goods=null;
        if ($cate->depth==0){
            $goods=null;
            foreach ($categories as $depth){
                $depth2 = GoodsCategory::findAll(['parent_id'=>$depth->id]);
                foreach ($depth2 as $cate2){
                    $goods[]=Goods::find()->where(['goods_category_id'=>$cate2->id])->all();
                }
            }

        }
        if ($cate->depth==1){
            $goods=null;
            foreach ($categories as $cate2){
                $goods[]=Goods::find()->where(['goods_category_id'=>$cate2->id])->all();
            }
        }
        if ($cate->depth==2){
            $goods[]=Goods::find()->where(['goods_category_id'=>$goods_category_id])->all();
        }
        return $this->render('list',['categories'=>$categories,'cate'=>$cate,'goods'=>$goods]);
    }

    public function actionGoods(){
        $goods = Goods::findOne(['id'=>\Yii::$app->request->get('id')]);
        $this->layout = 'list';
        return $this->render('goods',['goods'=>$goods]);
    }

    public function actionIndex()
    {
        $this->layout = 'index';
        return $this->render('index');
    }
    public function actionView($goods_id)
    {
        $this->layout = 'view';
        $goodsintro=GoodsIntro::findOne(['goods_id'=>$goods_id]);
        $goods=Goods::findOne(['id'=>$goods_id]);
        return $this->render('view',['goods'=>$goods,'goodsintro'=>$goodsintro]);
    }
    //添加到购物车
    public function actionAdd()
    {
        $goods_id = Yii::$app->request->post('goods_id');
        $amount = Yii::$app->request->post('amount');
        $goods = Goods::findOne(['id'=>$goods_id]);
        if($goods==null){
            throw new NotFoundHttpException('商品不存在1');
        }
            //未登录和登录
            //先获取cookie中的购物车数据
            $cookies = Yii::$app->request->cookies;
            $cookie = $cookies->get('cart');
            if($cookie == null){
                //cookie中没有数据
                $cart = [];
            }else{
                //如果有数据就反序列化
                $cart = unserialize($cookie->value);
            }
            //将商品id和数量存到cookie
            $cookies = Yii::$app->response->cookies;
            //检查购物车中是否有该商品,有就累加数量值
            if(key_exists($goods->id,$cart)){
                $cart[$goods_id] += $amount;
            }else{
                //没有的话就增加
                $cart[$goods_id] = $amount;
            }
        if(Yii::$app->user->isGuest){
            $cookie = new Cookie([
                'name'=>'cart','value'=>serialize($cart)
            ]);
            $cookies->add($cookie);
        }else{
            //已登录
            //操作数据库
            $model=new Cart();
            $member_id=Yii::$app->user->id;
            foreach ($cart as $k=>$cartChild){
//                echo '数量:'.$k;
                $oldgoods=$model::find()->where(['goods_id'=>$k,'member_id'=>$member_id])->one();
                if ($oldgoods){
                    $oldgoods->amount=$oldgoods->amount+$cartChild;
                    $oldgoods->save();
                }else{
                    $model->amount=$cartChild;
                    $model->goods_id=$k;
                    $model->member_id=$member_id;
                    if(!$model->isNewRecord){
                        $model->isNewRecord=true;
                        $model->id=null;
                    }
                    $model->save();
                }
            }
            \Yii::$app->response->getCookies()->remove($cookie);
        }
        return $this->redirect(['goods/cart']);
    }
    //购物车
    public function actionCart()
    {
        $this->layout = 'cart';
        if(Yii::$app->user->isGuest) {
            //取出cookie中的数据
            $cookies = Yii::$app->request->cookies;
            $cookie = $cookies->get('cart');
            if ($cookie == null) {
                //cookie中没有购物车数据
                $cart = [];
            } else {
                $cart = unserialize($cookie->value);
            }
            $models = [];
            foreach ($cart as $good_id => $amount) {
                $goods = Goods::findOne(['id' => $good_id])->attributes;
                $goods['amount'] = $amount;
                $models[] = $goods;
            }
        }else{
            //不是游客
                $model=new Cart();
                $member_id=Yii::$app->user->id;
                $carts=$model::find()->where(['member_id'=>$member_id])->asArray()->all();
                $models = [];
                foreach ($carts as $cart){
//                var_dump($cart['id']);
                    $cart_id=$cart['goods_id'];
                    $goods = Goods::findOne(['id' => $cart_id])->attributes;
                    $goods['amount'] = $cart['amount'];
                    $models[] = $goods;
                }
        }
        return $this->render('cart', ['models' => $models]);
    }

    public function actionUpdateCart()
    {
        $goods_id = Yii::$app->request->post('goods_id');
        $amount = Yii::$app->request->post('amount');
        $goods = Goods::findOne(['id'=>$goods_id]);
        if($goods==null){
            throw new NotFoundHttpException('商品不存在');
        }
            //未登录
            //先获取cookie中的购物车数据
            $cookies = Yii::$app->request->cookies;
            $cookie = $cookies->get('cart');
            if($cookie == null){
                //cookie中没有购物车数据
                $cart = [];
            }else{
                $cart = unserialize($cookie->value);
            }
            //将商品id和数量存到cookie
            $cookies = Yii::$app->response->cookies;
            if($amount){
                $cart[$goods_id] = $amount;
            }else{
                if(key_exists($goods['id'],$cart)) unset($cart[$goods_id]);
            }
        if(Yii::$app->user->isGuest){
            $cookie = new Cookie([
                'name'=>'cart','value'=>serialize($cart)
            ]);
            $cookies->add($cookie);
        }else{
            $member_id=Yii::$app->user->id;
//            已经登录
            if ($amount==0){
                $removegoods=Cart::find()->where(['goods_id'=>$goods_id,'member_id'=>$member_id])->one();
                if ($removegoods==null){
                    throw new NotFoundHttpException('商品不存在');
                }else{
                    $removegoods->delete();
                }
            }else{
                $editgoods=Cart::find()->where(['goods_id'=>$goods_id,'member_id'=>$member_id])->one();
                if ( $editgoods==null){
                    throw new NotFoundHttpException('商品不存在');
                }else{
                    $editgoods->amount=$amount;
                    $editgoods->save();
                }
            }
            \Yii::$app->response->getCookies()->remove($cookie);
        }

    }

}
