<?php

namespace frontend\controllers;

use frontend\models\Cart;
use frontend\models\LoginForm;
use frontend\models\Member;
use Flc\Alidayu\Client;
use Flc\Alidayu\App;
use Flc\Alidayu\Requests\AlibabaAliqinFcSmsNumSend;
use Flc\Alidayu\Requests\IRequest;
use yii\web\User;

class MemberController extends \yii\web\Controller
{
    public $layout = 'login';
    //用户注册
    public function actionRegister()
    {
        $model = new Member();
        if($model->load(\Yii::$app->request->post())){
            if($model->validate()){
                $model->password_hash = \Yii::$app->getSecurity()->generatePasswordHash($model->password);
                $model->last_login_time=time();
                $model->created_at=time();
                $model->status=1;
                $model->last_login_ip=\Yii::$app->request->userIP;
                if ($model->save()){
                    \Yii::$app->session->setFlash('success','用户注册成功');
                    return $this->redirect(['goods/index']);
                }
            }
        }
        return $this->render('regist',['model'=>$model]);
    }
    //用户登录
    public function actionLogin(){
        $model = new LoginForm();
        if ($model->load(\Yii::$app->request->post()) && $model->login()) {
            //登录成功，操作数据库同步数据
            //先获取cookie中的购物车数据
            $cookies = \Yii::$app->request->cookies;
            $cookie = $cookies->get('cart');
            if($cookie == null){
                //cookie中没有数据
                $cart = [];
            }else{
                //如果有数据就反序列化
                $cart = unserialize($cookie->value);
            }
            $model2=new Cart();
            $member_id=\Yii::$app->user->id;
            foreach ($cart as $k=>$cartChild){
//                echo '数量:'.$k;
                $oldgoods=$model2::find()->where(['goods_id'=>$k,'member_id'=>$member_id])->one();
                if ($oldgoods){
                    $oldgoods->amount=$oldgoods->amount+$cartChild;
                    $oldgoods->save();
                }else{
                    $model2->amount=$cartChild;
                    $model2->goods_id=$k;
                    $model2->member_id=$member_id;
                    if(!$model2->isNewRecord){
                        $model2->isNewRecord=true;
                        $model2->id=null;
                    }
                    $model2->save();
                }
            }
            \Yii::$app->response->getCookies()->remove($cookie);
            //同步完成
            \Yii::$app->session->setFlash('success','登录成功');
            return $this->goBack();
        } else {
            \Yii::$app->user->setReturnUrl(\Yii::$app->request->referrer);
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }
    public function actionLogout()
    {
        \Yii::$app->user->logout();
        \Yii::$app->session->setFlash('success','注销成功');
        return $this->redirect(['goods/index']);
    }
    public function actionSendSms()
    {
        //确保上一次发送短信间隔超过1分钟
        $tel = \Yii::$app->request->post('tel');
        if(!preg_match('/^1[34578]\d{9}$/',$tel)){
            echo '电话号码不正确';
            exit;
        }
        $code = rand(1000,9999);
        $result = \Yii::$app->sms->setNum($tel)->setParam(['code' => $code])->send();
        if($result){
            \Yii::$app->cache->set('tel_'.$tel,$code,5*60);
            echo 'success'.$code;
        }else{
            echo '发送失败';
        }
    }
}
