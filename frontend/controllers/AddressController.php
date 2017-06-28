<?php

namespace frontend\controllers;

use frontend\models\Address;

class AddressController extends \yii\web\Controller
{
    public $layout = 'address';
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionAdd()
    {
        $member_id=\Yii::$app->user->id;
        $model = new Address();
        $queres = Address::find()->where(['member_id'=>$member_id])->all();
        if($model->load(\Yii::$app->request->post())){
            if($model->validate()){
                $model->status=0;
                $model->created_at=time();
                $model->member_id=$member_id;
                if ($model->save()){
                    \Yii::$app->session->setFlash('success','添加成功');
                    return $this->redirect(['address/add']);
                }
            }
        }
        return $this->render('add',['model'=>$model,'queres'=>$queres]);
    }
    public function actionEdit($id)
    {
        $member_id=\Yii::$app->user->id;
        $queres = Address::find()->where(['member_id'=>$member_id])->all();
        $model =Address::findOne(['id'=>$id]);
        if($model->load(\Yii::$app->request->post())){
            if($model->validate()){
                $model->status=0;
                if ($model->save()){
                    \Yii::$app->session->setFlash('success','修改成功');
                    return $this->redirect(['address/add']);
                }
            }
        }
        return $this->render('add',['model'=> $model,'queres'=>$queres]);
    }
    public function actionDelete($id)
    {
        $member_id=\Yii::$app->user->id;
        $model =Address::find()->where(['id'=>$id,'member_id'=>$member_id])->one();
        $model->delete();
        return $this->redirect('add.html');
    }
    public function actionDefault($id)
    {
        $member_id=\Yii::$app->user->id;
        $models=Address::find()->where(['member_id'=>$member_id])->all();
        foreach ($models as $v){
            $v->status=0;
            $v->save();
        }
        $model =Address::find()->where(['id'=>$id,'member_id'=>$member_id])->one();
        $model->status=1;
        $model->save();
        return $this->redirect('add.html');
    }
    public function actions()
    {
        $actions=parent::actions();
        $actions['get-region']=[
            'class'=>\chenkby\region\RegionAction::className(),
            'model'=>\frontend\models\Locations::className()
        ];
        return $actions;
    }

}
