<?php
namespace frontend\models;

use yii\base\Model;

class LoginForm extends Model{
    public $username;//用户名
    public $password;//密码
    public $code;//密码
    public $rememberMe ;
    public function rules()
    {
        return [
            [['username','password','code'],'required'],
            [['code'],'captcha','captchaAction'=>'site/captcha'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'password'=>'密码',
            'code'=>'验证码',
            'rememberMe'=>'记住我'
        ];
    }
    public function login()
    {
        if($this->validate()){
//            var_dump($this->username);exit;
            $member = Member::findOne(['username'=>$this->username]);
            if($member){
                //用户存在 验证密码
                if(\Yii::$app->security->validatePassword($this->password,$member->password_hash)){
                    $member->last_login_time=time();
                    $member->last_login_ip=\Yii::$app->request->userIP;
                    $member->auth_key = \Yii::$app->security->generateRandomString();
                    $member->save(false);
                    \Yii::$app->user->login($member,$this->rememberMe?3600*24*7:0);
                    return true;
                }else{
                    $this->addError('password','密码不正确');
                }
            }else{
                //账号不存在  添加错误
                $this->addError('username','账号不正确');
            }
        }
        return false;
    }

}