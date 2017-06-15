<?php

namespace backend\controllers;

use backend\models\GoodsImg;
use yii\data\Pagination;
use xj\uploadify\UploadAction;

class GoodsImgController extends \yii\web\Controller
{
    public function actionIndex($goods_id)
    {
        $query = GoodsImg::find()->andFilterWhere(['like', 'goods_id', $goods_id]);
        $pager = new Pagination([
            'totalCount'=>$query->count(),
            'pageSize'=>2
        ]);
        $goods_imgs = $query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['goods_imgs'=>$goods_imgs,'pager'=>$pager,'goods_id'=>$goods_id]);
    }
    public function actionCreate($goods_id)
    {
        $goods_imgs = new GoodsImg();
        if($goods_imgs->load(\Yii::$app->request->post())){
            if($goods_imgs->validate()){
                $goods_imgs->goods_id=$goods_id;
                $goods_imgs->save();
                \Yii::$app->session->setFlash('success','图片添加成功');
                return $this->redirect(['goods-img/index','goods_id'=>$goods_id]);
            }
        }
        return $this->render('create',['goods_imgs'=>$goods_imgs,'goods_id'=>$goods_id]);
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

}
