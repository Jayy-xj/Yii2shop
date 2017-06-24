<?php

namespace frontend\controllers;

use frontend\models\LoginForm;
use frontend\models\Member;
use Flc\Alidayu\Client;
use Flc\Alidayu\App;
use Flc\Alidayu\Requests\AlibabaAliqinFcSmsNumSend;
use Flc\Alidayu\Requests\IRequest;
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
            \Yii::$app->session->setFlash('success','登录成功');
            return $this->redirect(['goods/index']);
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }
    public function actionLogout()
    {
        \Yii::$app->user->logout();
        \Yii::$app->session->setFlash('success','注销成功');
        return $this->redirect(['layout/index']);
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
