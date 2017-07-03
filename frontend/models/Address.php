<?php

namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "address".
 *
 * @property integer $id
 * @property string $username
 * @property string $tel
 * @property integer $status
 * @property integer $created_at
 * @property integer $province
 * @property integer $city
 * @property integer $district
 * @property integer $detail
 * @property integer $member_id
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $defaultMe;//设为默认
    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username','tel','status','detail','district','province','city'], 'required'],
            [['status', 'created_at'], 'integer'],
            [['username'], 'string', 'max' => 50],
            [['tel'], 'string', 'max' => 11],
//            [['province','city','district','detail'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'tel' => '电话',
            'status' => '状态',
            'created_at' => '添加时间',
            'detail' => '详细地址',
            'defaultMe' => '设为默认地址',
            'district' => '区',
            'province' => '省',
            'city' => '市',
            'member_id'=>'用户ID',
        ];
    }

}
