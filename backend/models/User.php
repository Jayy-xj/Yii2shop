<?php

namespace backend\models;


use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $last_login_time
 * @property string $last_login_ip
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $repassword;
    public $oldpassword;
    public $newpassword;
    public $roles=[];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password_hash'], 'required'],
            [['status', 'created_at', 'updated_at', 'last_login_time'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email', 'last_login_ip'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
            ['roles','safe'],
//            ['repassword', 'compare','compareAttribute'=>'password_hash'],
            [['password_reset_token'], 'unique'],
            ['oldpassword', 'validateCheck'],
        ];
    }
//自定义rule验证旧密码
    public function validateCheck(){
        $users = User::findOne(['username'=>$this->username]);
        if ($users===null){
            throw new NotFoundHttpException('用户不存在');
        }
        $pw=\Yii::$app->getSecurity()->validatePassword($this->oldpassword,$users->password_hash);
            if(!$pw){
                $this->addError('oldpassword','旧密码不正确');
            }
    }
    public static function  getRoleOption(){
        $role=\Yii::$app->authManager->getRoles();
        return ArrayHelper::map($role,'name','description');
    }
    public function addUser(){
        $authManager=\Yii::$app->authManager;
                foreach ($this->roles as $roleName){
                    $role=$authManager->getRole($roleName);
                    if ($roleName){
                        $authManager->assign($role,$this->id);
                    }
                    return true;
                }
    }
    public function updateUser($id){
                \Yii::$app->authManager->revokeAll($id);
                foreach ($this->roles as $roleName){
                    $role=\Yii::$app->authManager->getRole($roleName);
                    if ($roleName){
                        \Yii::$app->authManager->assign($role,$this->id);
                    }
                    return true;
                }
    }
    public function loadData($id){
        $this->roles=ArrayHelper::map( \Yii::$app->authManager->getRolesByUser($id),'name','name');

    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户姓名',
            'last_login_time' => '最后登录时间',
            'last_login_ip' => '最后登录ip',
            'password_hash' => '密码',
//            'repassword'=>'确认密码',
//            'newrepassword'=>'确认密码',
            'oldpassword'=>'旧密码',
            'newpassword'=>'新密码',
        ];
    }

    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
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
