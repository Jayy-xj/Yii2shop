<?php

namespace backend\controllers;

use backend\models\Menu;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;

class MenuController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $query=Menu::find();
        $pager = new Pagination([
            'totalCount'=> $query->count(),
            'pageSize'=>5
        ]);
        $menus =   $query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['menus'=>$menus,'pager'=>$pager]);
    }
    public function actionCreate(){
        $menus = new Menu();
        if($menus->load(\Yii::$app->request->post())){
            if($menus->validate()){
                $menus->save();
                \Yii::$app->session->setFlash('success','菜单添加成功');
                return $this->redirect(['menu/index']);
            }
        }
        return $this->render('create',['menus'=>$menus]);
    }
    public function actionUpdate($id){
        $menus =Menu::findOne(['id'=>$id]);
        if($menus==null){
            throw new NotFoundHttpException('菜单不存在');
        }
        if($menus->load(\Yii::$app->request->post())){
            if($menus->validate()){
                $menus->save();
                \Yii::$app->session->setFlash('success','菜单修改成功');
                return $this->redirect(['menu/index']);
            }
        }
        return $this->render('create',['menus'=>$menus]);
    }
    public function actionDelete($id){
        $menus =Menu::findOne(['id'=>$id]);
        $child=Menu::find()->where(['parent_id'=>$id])->all();
        if($menus==null){
            throw new NotFoundHttpException('菜单不存在');
        }
        if ($child!=null){
            \Yii::$app->session->setFlash('error','此菜单下有子分类，不允许删除');
            \Yii::$app->user->setReturnUrl(\Yii::$app->request->referrer);
        }else{
            $menus->delete();
            \Yii::$app->session->setFlash('success','菜单删除成功');
            return $this->redirect(['menu/index']);
        }
    }
}
