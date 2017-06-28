<?php

namespace frontend\controllers;

class GameController extends \yii\web\Controller
{

    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionStart()
    {
        for ($i=1;$i<=4;$i++){
        }
        return $this->render('index');
    }
    public function actionCheck()
    {
        global $truenum;
    }
}