<?php

namespace backend\controllers;

use backend\components\SphinxClient;
use backend\models\Goods;
use backend\models\GoodsDayCount;
use backend\models\GoodsIntro;
use backend\models\GoodsSearchForm;
use xj\uploadify\UploadAction;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class GoodsController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = new GoodsSearchForm();
        $query = Goods::find();
        if($keyword = \Yii::$app->request->get('keyword')){
            $cl = new SphinxClient();
            $cl->SetServer ( '127.0.0.1', 9312);
            $cl->SetConnectTimeout ( 10 );
            $cl->SetArrayResult ( true );
            $cl->SetMatchMode ( SPH_MATCH_ALL);
            $cl->SetLimits(0, 1000);
            $res = $cl->Query($keyword, 'goods');
            if(!isset($res['matches'])){
                $query->where(['id'=>0]);
            }else{
                $ids = ArrayHelper::map($res['matches'],'id','id');
                $query->where(['in','id',$ids]);
            }
        }
        $pager = new Pagination([
            'totalCount'=>$query->count(),
            'pageSize'=>5
        ]);
        $models = $query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['models'=>$models,'pager'=>$pager,'model'=>$model]);
    }
    public function actionCreate()
    {
        $goods = new Goods();
        $goods_intro=new GoodsIntro();
        if($goods->load(\Yii::$app->request->post())&&$goods_intro->load(\Yii::$app->request->post())){
            if($goods->validate()){
                $day=date('Y-m-d',time());
                $day2=date('Ymd',time());
                $count=GoodsDayCount::findOne(['day'=>$day]);
                if (!$count){
                    $count=new GoodsDayCount();
                    $count->day=date('Y-m-d',time());
                    $count->save();
                }
                $count->count++;
                $goods->sn=$day2.str_pad($count->count,5,0,STR_PAD_LEFT);
                $goods->create_time=time();
                $goods->save();
                $goods_intro->goods_id=$goods->id;
                $count->save();
                $goods_intro->save();
                \Yii::$app->session->setFlash('success','商品添加成功');
                return $this->redirect(['goods/index']);
            }
        }
        return $this->render('create',['goods'=>$goods,'goods_intro'=>$goods_intro]);
    }
    public function actionUpdate($id)
    {
        $goods =Goods::findOne(['id'=>$id]);
        $goods_intro =GoodsIntro::findOne(['goods_id'=>$id]);
        if($goods==null||$goods_intro==null){
            throw new NotFoundHttpException('商品不存在');
        }
        if($goods->load(\Yii::$app->request->post())&&$goods_intro->load(\Yii::$app->request->post())){
            if($goods->validate()){
                $goods->save();
                $goods_intro->save();
                \Yii::$app->session->setFlash('success','商品修改成功');
                return $this->redirect(['goods/index']);
            }
        }
        return $this->render('create',['goods'=>$goods,'goods_intro'=>$goods_intro]);
    }
    public function actionView($id)
    {
        $goods = Goods::findOne($id);
        $goodsintro=GoodsIntro::findOne(['goods_id'=>$id]);
        return $this->render('view',['goods'=>$goods,'goodsintro'=>$goodsintro]);
    }
    public function actionDelete($id)
    {
        $model = Goods::findOne(['id'=>$id]);
        if($model==null){
            throw new NotFoundHttpException('商品不存在');
        }
        $model->status=0;
        $model->save();
        \Yii::$app->session->setFlash('success','商品删除成功');
        return $this->redirect(['goods/index']);
    }
    public function actions() {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
            ],
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
                'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
                /*'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filename = sha1_file($action->uploadfile->tempName);
                    return "{$filename}.{$fileext}";
                },*/
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    $action->output['fileUrl'] = $action->getWebUrl();
                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                },
            ],
        ];
    }
    public function actionTest(){
        $cl = new SphinxClient();
        $cl->SetServer ( '127.0.0.1', 9312);
        $cl->SetConnectTimeout ( 10 );
        $cl->SetArrayResult ( true );
        $cl->SetMatchMode ( SPH_MATCH_ALL);
        $cl->SetLimits(0, 1000);
        $info = '联想';//需要搜索的词
        $res = $cl->Query($info, 'goods');
        var_dump($res);
    }

}
