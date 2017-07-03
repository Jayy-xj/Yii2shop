<?php
namespace frontend\controllers;

use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\Goods;
use backend\models\GoodsCategory;
use frontend\models\Address;
use frontend\models\Member;
use yii\web\Controller;
use yii\web\Response;

class ApiController extends Controller
{
    public $enableCsrfValidation = false;
    public function init()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        parent::init();
    }
    //获取品牌下面的所有商品
    public function actionGetGoodsByBrand()
    {

        if($brand_id = \Yii::$app->request->get('brand_id')){
            $goods = Goods::find()->where(['brand_id'=>$brand_id])->asArray()->all();
            return ['status'=>1,'msg'=>'','data'=>$goods];
        }
        return ['status'=>'-1','msg'=>'参数不正确'];
    }
    //会员注册  POST
    public function actionUserRegister()
    {
        $request = \Yii::$app->request;
        if($request->isPost){
            $member = new Member();
            $member->username = $request->post('username');
            $member->password = $request->post('password');
            $member->email = $request->post('email');
            $member->tel = $request->post('tel');
            if($member->validate()){
                $member->password_hash = \Yii::$app->getSecurity()->generatePasswordHash($member->password);
                $member->last_login_time=time();
                $member->created_at=time();
                $member->status=1;
                $member->last_login_ip=\Yii::$app->request->userIP;
                $member->save();
                return ['status'=>'1','msg'=>'','data'=>$member->toArray()];
            }
            //验证失败
            return ['status'=>'-1','msg'=>$member->getErrors()];
        }
        return ['status'=>'-1','msg'=>'请使用post请求'];
    }
    //登录
    public function actionLogin()
    {
        $request = \Yii::$app->request;
        if($request->isPost){
            $user = Member::findOne(['username'=>$request->post('username')]);
            if($user && \Yii::$app->security->validatePassword($request->post('password'),$user->password_hash)){
                \Yii::$app->user->login($user);
                return ['status'=>'1','msg'=>'登录成功'];
            }
            return ['status'=>'-1','msg'=>'账号或密码错误'];
        }
        return ['status'=>'-1','msg'=>'请使用post请求'];
    }
    //获取当前登录用户信息
    public function actionGetCurrentUser()
    {
        if(\Yii::$app->user->isGuest){
            return ['status'=>'-1','msg'=>'请先登录'];
        }
        return ['status'=>'1','msg'=>'','data'=>\Yii::$app->user->identity->toArray()];
    }
    //修改当前登录用户密码
    public function actionUpdatePassword()
    {
        if(\Yii::$app->user->isGuest){
            return ['status'=>'-1','msg'=>'请先登录'];
        }
        $request = \Yii::$app->request;
        if($request->isPost){
            $id=\Yii::$app->user->id;
            $user = Member::findOne(['id'=>$id]);
            if($user && \Yii::$app->security->validatePassword($request->post('oldpassword'),$user->password_hash)){
                $user->password_hash = \Yii::$app->getSecurity()->generatePasswordHash($request->post('password'));
                if($user->save(false)){
                    return ['status'=>'1','msg'=>'修改成功'];
                }
            }
            return ['status'=>'-1','msg'=>'旧密码错误'];
        }
        return ['status'=>'-1','msg'=>'请使用post请求'];
    }
    //收货地址添加  POST
    public function actionLocationAdd()
    {
        $request = \Yii::$app->request;
        if(\Yii::$app->user->isGuest){
            return ['status'=>'-1','msg'=>'请先登录'];
        }
        $member_id=\Yii::$app->user->id;
        if($request->isPost){
            $address = new Address();
            $address->username = $request->post('username');
            $address->tel = $request->post('tel');
            $address->province = $request->post('province');
            $address->city = $request->post('city');
            $address->district = $request->post('district');
            $address->detail = $request->post('detail');
            $address->status = $request->post('status');
            $address->member_id = $member_id;
            $address->created_at=time();
            if($address->validate()){

                if($address->save(false)){
                    return ['status'=>'1','msg'=>'添加成功'];
                }
            }
            //验证失败
            return ['status'=>'-1','msg'=>$address->getErrors()];
        }
        return ['status'=>'-1','msg'=>'请使用post请求'];
    }
    //收货地址修改
    public function actionLocationEdit()
    {
        $address_id=6;//get传参
        $member_id=\Yii::$app->user->id;
        $model =Address::findOne(['id'=>$address_id,'member_id'=>$member_id]);
        if($model->load(\Yii::$app->request->post())){
            if($model->validate()){
                if ($model->save()){
                    return ['status'=>'1','msg'=>'修改成功'];
                }
            }
            //验证失败
            return ['status'=>'-1','msg'=>$model->getErrors()];
        }
        return ['status'=>'0','msg'=>$model];
    }
    //收货地址删除
    public function actionLocationDelete($address_id)
    {
        if(\Yii::$app->user->isGuest){
            return ['status'=>'-1','msg'=>'请先登录'];
        }
        $member_id=\Yii::$app->user->id;
        $model =Address::find()->where(['id'=>$address_id,'member_id'=>$member_id])->one();
        $model->delete();
        return ['status'=>'1','msg'=>'删除成功'];
    }
    //获取所有商品分类
    public function actionGetAllCategory(){
        $categories=GoodsCategory::find()->all();
        return ['status'=>'0','msg'=>$categories];
    }

    public function actionGetChildrenCategory($parent_id){
        $categories=GoodsCategory::find()->where(['parent_id'=>$parent_id])->all();
        return ['status'=>'0','msg'=>$categories];
    }
    public function actionGetParentCategory($child_id){
        $category=GoodsCategory::findOne(['id'=>$child_id]);
        $parent=GoodsCategory::findOne(['id'=>$category->parent_id]);
        return ['status'=>'0','msg'=>$parent];
    }
    public function actionGetCategoryGoods($category_id){
        $goods=Goods::find()->where(['goods_category_id'=>$category_id])->all();
        return ['status'=>'0','msg'=>$goods];
    }
    public function actionGetBrandGoods($brand_id){
        $goods=Goods::find()->where(['brand_id'=>$brand_id])->all();
        return ['status'=>'0','msg'=>$goods];
    }
    public function actionGetArticleCategory(){
        $article_categories=ArticleCategory::find()->all();
        return ['status'=>'0','msg'=>$article_categories];
    }
    public function actionGetArticles($category_id){
        $articles=Article::find()->where(['article_category_id'=>$category_id])->all();
        return ['status'=>'0','msg'=>$articles];
    }
    public function actionGetCategory($article_id){
        $category_id=Article::findOne(['id'=>$article_id])->article_category_id;
        $category=ArticleCategory::findOne(['id'=>$category_id]);
        return ['status'=>'0','msg'=>$category];
    }
    public function actionGetWeather($city)
    {
        $weathers = (array)simplexml_load_file('http://flash.weather.com.cn/wmaps/xml/sichuan.xml');
        $data = array();
        foreach ($weathers['city'] as $weather) {
            $data[(string)$weather['cityname']] = $weather;
        }
        $data = (array)$data;
        var_dump($data[$city]);
    }
}
?>