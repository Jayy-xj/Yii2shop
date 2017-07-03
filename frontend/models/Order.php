<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property integer $id
 * @property integer $member_id
 * @property string $name
 * @property string $province
 * @property string $city
 * @property string $area
 * @property string $address
 * @property string $tel
 * @property string $delivery_id
 * @property string $delivery_name
 * @property string $delivery_price
 * @property string $payment_id
 * @property string $payment_name
 * @property string $total
 * @property string $status
 * @property string $trade_no
 * @property integer $create_time
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static $send_ways=[
        ['delivery_id'=>0,'delivery_name'=>'普通快递送货上门','delivery_price'=>10.00],
        ['delivery_id'=>1,'delivery_name'=>'特快专递','delivery_price'=>40.00],
        ['delivery_id'=>2,'delivery_name'=>'加急快递送货上门','delivery_price'=>40.00],
        ['delivery_id'=>3,'delivery_name'=>'平邮','delivery_price'=>10.00],
    ];
    public static $pay_ways=[
        ['payment_id'=>0,'payment_name'=>'货到付款','payment_intro'=>'送货上门后再收款，支持现金、POS机刷卡、支票支付'],
        ['payment_id'=>1,'payment_name'=>'在线支付','payment_intro'=>'	即时到帐，支持绝大数银行借记卡及部分银行信用卡'],
        ['payment_id'=>2,'payment_name'=>'上门自提','payment_intro'=>'	自提时付款，支持现金、POS刷卡、支票支付'],
        ['payment_id'=>3,'payment_name'=>'邮局汇款','payment_intro'=>'	通过快钱平台收款 汇款后1-3个工作日到账'],
    ];
    public static function tableName()
    {
        return 'order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'create_time'], 'integer'],
            [['delivery_price', 'total'], 'number'],
            [['name', 'province', 'city', 'area'], 'string', 'max' => 20],
            [['address'], 'string', 'max' => 60],
            [['tel'], 'string', 'max' => 11],
            [['delivery_id'], 'string', 'max' => 8],
            [['delivery_name', 'payment_name'], 'string', 'max' => 50],
            [['payment_id'], 'string', 'max' => 10],
            [['trade_no'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => '用户id',
            'name' => '收货人',
            'province' => '省',
            'city' => '市',
            'area' => '县',
            'address' => '详细地址',
            'tel' => '手机号码',
            'delivery_id' => '配送方式',
            'delivery_name' => '配送名称',
            'delivery_price' => '配送价格',
            'payment_id' => '支付方式',
            'payment_name' => '支付方式名称',
            'total' => '订单金额',
            'status' => '订单状态',
            'trade_no' => '第三方支付交易号',
            'create_time' => '创建时间',
        ];
    }
}
