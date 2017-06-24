<?php
namespace backend\controllers;
use backend\models\PermissionForm;
use backend\models\RoleForm;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class RbacController extends Controller
{

    //权限操作开始
    public function actionAddPermission(){
        $model=new PermissionForm();
        if ($model->load(\Yii::$app->request->post())&& $model->validate()){
            if ($model->addPermission()){
                \Yii::$app->session->setFlash('success','权限添加成功');
                $this->redirect(['index-permission']);
            }
        }
        return $this->render('add-permission',['model'=>$model]);
    }
    public function actionIndexPermission()
    {
        $models=\Yii::$app->authManager->getPermissions();
        return $this->render('index-permission',['models'=>$models]);
    }
    public function actionEditPermission($name)
    {
        $permission=\Yii::$app->authManager->getPermission($name);
        if ($permission==null){
            throw new NotFoundHttpException('权限不存在');
        }
        $model=new PermissionForm();
        $model->loadData($permission);
        if ($model->load(\Yii::$app->request->post())&& $model->validate()){
            if ($model->editPermission($name)){
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect('index-permission');
            }
        }
        return $this->render('add-permission',['model'=>$model]);
    }
    public function actionDeletePermission($name){
        $permission=\Yii::$app->authManager->getPermission($name);
        if ($permission==null){
            throw new NotFoundHttpException('权限不存在');
        }
        \Yii::$app->authManager->remove($permission);
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect('index-permission');
    }
    //权限操作结束


    //角色操作开始
    public function actionIndexRole(){
        $models=\Yii::$app->authManager->getRoles();
        return $this->render('index-role',['models'=>$models]);
    }
    public function actionAddRole(){
        $model=new RoleForm();
        if ($model->load(\Yii::$app->request->post())&& $model->validate()){
            if ($model->addRole()){
                \Yii::$app->session->setFlash('success','角色添加成功');
                $this->redirect(['index-role']);
            }
        }
        return $this->render('add-role',['model'=>$model]);
    }
    public function actionDeleteRole($name){
        $role=\Yii::$app->authManager->getRole($name);
        if ($role==null){
            throw new NotFoundHttpException('角色不存在');
        }
        \Yii::$app->authManager->remove($role);
        \Yii::$app->authManager->removeChildren($role);
        \Yii::$app->session->setFlash('success','角色删除成功');
        return $this->redirect('index-role');
    }
    public function actionEditRole($name){
        $role=\Yii::$app->authManager->getRole($name);
        if ($role==null){
            throw new NotFoundHttpException('角色不存在');
        }
        $model=new RoleForm();
        $model->loadData($role);
        if ($model->load(\Yii::$app->request->post())&&$model->validate()){
            if ($model->editRole($name)){
                \Yii::$app->session->setFlash('success','修改角色成功');
                $this->redirect('index-role');
            }
        }
        return $this->render('add-role',['model'=>$model]);
    }
}