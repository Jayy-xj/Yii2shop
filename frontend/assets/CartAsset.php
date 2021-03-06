<?php
namespace frontend\assets;

use yii\web\AssetBundle;

class CartAsset extends AssetBundle{
    public $basePath = '@webroot';//静态资源的硬盘路径
    public $baseUrl = '@web';//静态资源的url路径
    //需要加载的css文件
    public $css = [
        'style/base.css',
        'style/global.css',
        'style/header.css',
        'style/cart.css',
        'style/footer.css',
        "style/fillin.css",
    ];
    //需要加载的js文件
    public $js = [
        "js/cart1.js",
        'js/cart2.js'
    ];
    //和其他静态资源管理器的依赖关系
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}