<?php

namespace frontend\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "member".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $email
 * @property string $tel
 * @property integer $last_login_time
 * @property integer $last_login_ip
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Member extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public $password;//密码明文
    public $checkpassword;//确认密码
    public $oldpassword;//旧密码
    public $code;//验证码
    public $mes;//用户协议
    public $smsCode;//短信验证码
    public static function tableName()
    {
        return 'member';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username','email','tel','checkpassword','password'], 'required','on'=>'register'],
            [['username'], 'string', 'max' => 50],
            [['email'], 'email', 'message'=>'邮箱格式不正确'],
            [['password','checkpassword'], 'required','on'=>'register'],
            [['code'],'captcha','captchaAction'=>'site/captcha','on' => ['register','login']],
            ['tel','match','pattern'=>'/^1[34578]\d{9}$/','message'=>'电话号码格式不正确'],
            ['checkpassword', 'compare','compareAttribute'=>'password','message'=>'两次密码不一致','on'=>'register'],
            //验证短信验证码
            ['smsCode','validateSms','on'=>'register'],
        ];
    }
    //验证短信验证码
    public function validateSms()
    {
        //缓存里面没有该电话号码
        $value = Yii::$app->cache->get('tel_'.$this->tel);
        if(!$value || $this->smsCode != $value){
            $this->addError('smsCode','验证码不正确');
        }
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return [
            'register' => ['username', 'email', 'tel','code','checkpassword','password', 'smsCode'],
           'login' => ['code'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'auth_key' => 'Auth Key',
            'password_hash' => '密码',
            'email' => '邮箱：',
            'tel' => '电话',
            'last_login_time' => '最后登录时间',
            'last_login_ip' => '最后登录ip',
            'status' => '账号状态',
            'created_at' => '添加时间',
            'updated_at' => '修改时间',
            'password' => '密码',
            'code' => '验证码',
            'checkpassword' => '确认密码',
            'smsCode' => '手机验证码',
        ];
    }
//验证
    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    //得到id
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    //得到数据库中的authkey
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    //验证authkey
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
}
