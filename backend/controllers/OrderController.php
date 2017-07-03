<?php

namespace backend\controllers;

use frontend\models\Order;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;

class OrderController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $query = Order::find();
        $pager = new Pagination([
            'totalCount'=>$query->count(),
            'pageSize'=>5
        ]);
        $orders = $query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['orders'=>$orders,'pager'=>$pager]);
    }
    public function actionUpdate($id)
    {
        $order =Order::findOne(['id'=>$id]);
        if( $order==null){
            throw new NotFoundHttpException('订单不存在');
        }
                $order->status=1;
                $order->save();
                \Yii::$app->session->setFlash('success','发货成功');
                return $this->redirect(['order/index']);
    }
    public function actionEsc($id)
    {
        $order =Order::findOne(['id'=>$id]);
        if( $order==null){
            throw new NotFoundHttpException('订单不存在');
        }
        $order->status=2;
        $order->save();
        \Yii::$app->session->setFlash('success','取消成功');
        return $this->redirect(['order/index']);
    }
}
