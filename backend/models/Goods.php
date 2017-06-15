<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "goods".
 *
 * @property integer $id
 * @property string $name
 * @property string $sn
 * @property string $logo
 * @property integer $goods_category_id
 * @property integer $brand_id
 * @property string $market_price
 * @property string $shop_price
 * @property integer $stock
 * @property integer $is_on_sale
 * @property integer $sort
 * @property integer $status
 * @property integer $crate_time
 *
 * @property GoodsCategory $goodsCategory
 * @property Brand $brand
 * @property GoodsIntro[] $goodsIntros
 */
class Goods extends \yii\db\ActiveRecord
{
    public static $status_options = [1=>'正常',0=>'回收站'];
    public static $sale_options = [1=>'在售',0=>'下架'];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','goods_category_id', 'brand_id','market_price','shop_price','stock'], 'required'],
            [['goods_category_id', 'brand_id', 'stock', 'is_on_sale', 'sort', 'status', 'create_time'], 'integer'],
            [['market_price', 'shop_price'], 'number'],
            [['name', 'sn'], 'string', 'max' => 20],
            [['logo'], 'string', 'max' => 255],
            [['goods_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => GoodsCategory::className(), 'targetAttribute' => ['goods_category_id' => 'id']],
            [['brand_id'], 'exist', 'skipOnError' => true, 'targetClass' => Brand::className(), 'targetAttribute' => ['brand_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '商品名称',
            'sn' => '货号',
            'logo' => 'LOGO',
            'goods_category_id' => '商品分类id',
            'brand_id' => '品牌分类',
            'market_price' => '市场价格',
            'shop_price' => '商品价格',
            'stock' => '库存',
            'is_on_sale' => '是否在售',
            'sort' => '排序',
            'status' => '状态',
            'create_time' => '创建时间',
        ];
    }
    public static function getCategoryOptions()
    {
        return ArrayHelper::map(GoodsCategory::find()->asArray()->all(),'id','name');
    }
    public static function getBrandOptions()
    {
        return ArrayHelper::map(Brand::find()->where(['status'=>1])->asArray()->all(),'id','name');
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoodsCategory()
    {
        return $this->hasOne(GoodsCategory::className(), ['id' => 'goods_category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrand()
    {
        return $this->hasOne(Brand::className(), ['id' => 'brand_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoodsIntros()
    {
        return $this->hasMany(GoodsIntro::className(), ['goods_id' => 'id']);
    }
}
