<?php

namespace backend\controllers;

use backend\models\LoginForm;
use backend\models\RoleForm;
use backend\models\User;
use yii\data\Pagination;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

class UserController extends \yii\web\Controller
{
    /*
     * 添加管理员
     */
//    public function actionInit()
//    {
//
//        $user = new User();
//        $user->username = 'admin';
//        $user->password_hash = 'xj159286xj';
////        $admin->email = 'admin@admin.com';
//        $user->auth_key = \Yii::$app->security->generateRandomString();
//        $user->save();
//        return $this->redirect(['admin/login']);
//        //注册完成后自动帮用户登录账号
//        //\Yii::$app->user->login($user);
//    }
    public function actionIndex()
    {
        $query = User::find();
        $pager = new Pagination([
            'totalCount'=>$query->count(),
            'pageSize'=>2
        ]);
        $users = $query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['users'=>$users,'pager'=>$pager]);
    }

    public function actionCreate()
    {
        $users = new User();
        if($users->load(\Yii::$app->request->post())){
            if($users->validate()){
                $users->password_hash = \Yii::$app->getSecurity()->generatePasswordHash($users->password_hash);
                $users->last_login_time=time();
                $users->last_login_ip=\Yii::$app->request->userIP;
                if ($users->save()&& $users->addUser()){
                    \Yii::$app->session->setFlash('success','用户添加成功');
                    \Yii::$app->user->login($users);
                    return $this->redirect(['user/index']);
                }
            }
        }
        return $this->render('create',['users'=>$users]);
    }
    public function actionUpdate($id){
        $users =User::findOne(['id'=>$id]);
        if($users==null){
            throw new NotFoundHttpException('用户不存在');
        }
        $users->loadData($id);
        if($users->load(\Yii::$app->request->post())){
            if($users->validate()){
                $users->password_hash = \Yii::$app->getSecurity()->generatePasswordHash($users->newpassword);
                if ($users->save()&& $users->updateUser($id)) {
                    \Yii::$app->session->setFlash('success', '信息修改成功');
                    return $this->redirect(['user/index']);
                }
            }
        }
        return $this->render('update',['users'=>$users]);
    }
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index','create','update','delete','login'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
//                'actions' => [
//                    'logout' => ['post'],
//                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }


    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            \Yii::$app->session->setFlash('success','登录成功');
            return $this->redirect(['user/index']);
        }
        $model = new \backend\models\LoginForm();
        if ($model->load(\Yii::$app->request->post()) && $model->login()) {
            \Yii::$app->session->setFlash('success','登录成功');
            return $this->redirect(['user/index']);
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }
    public function actionDelete($id)
    {
        $users =User::findOne(['id'=>$id]);
        if($users==null){
            throw new NotFoundHttpException('用户不存在');
        }
        $users->delete();
        \Yii::$app->authManager->revokeAll($id);
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['user/index']);
    }
    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
       \Yii::$app->user->logout();
        \Yii::$app->session->setFlash('success','注销成功');
        return $this->redirect(['user/login']);
    }
}
