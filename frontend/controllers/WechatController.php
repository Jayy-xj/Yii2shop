<?php

namespace frontend\controllers;

use EasyWeChat\Foundation\Application;
use yii\web\Controller;

class WechatController extends Controller{
    public $enableCsrfValidation = false;


    public function actionIndex(){
        $app = new Application(\Yii::$app->params['wechat']);
        $response = $app->server->serve();
        $response->send();
        echo '测试';
    }
}