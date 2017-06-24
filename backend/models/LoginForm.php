<?php
namespace backend\models;

use yii\base\Model;

class LoginForm extends Model{
    public $username;//用户名
    public $password_hash;//密码
    public $rememberMe ;
    public function rules()
    {
        return [
            [['username','password_hash'],'required'],
//            ['rememberMe','boolean']
            ['rememberMe','safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'password_hash'=>'密码',
            'rememberMe'=>'记住我'
        ];
    }
    public function login()
    {
       if($this->validate()){
           $user = User::findOne(['username'=>$this->username]);
           if($user){
               //用户存在 验证密码
               if(\Yii::$app->getSecurity()->validatePassword($this->password_hash,$user->password_hash)){
                   $user->last_login_time=time();
                   $user->last_login_ip=\Yii::$app->request->userIP;
                   $user->auth_key = \Yii::$app->security->generateRandomString();
                   $user->save(false);
                   \Yii::$app->user->login($user,$this->rememberMe?3600*24*7:0);
                   return true;
               }else{
                   $this->addError('password_hash','密码不正确');
               }
           }else{
               //账号不存在  添加错误
               $this->addError('username','账号不正确');
           }
       }
       return false;
    }

}