<?php
namespace backend\models;


use yii\base\Model;
use yii\rbac\Permission;

class PermissionForm extends Model{
    public $name;
    public $description;
    public function rules()
    {
        return [
            [['name','description'],'required']
        ];
    }
    public function attributeLabels()
    {
        return [
            'name'=>'权限名称',
            'description'=>'权限描述'
        ];
    }
    public function addPermission(){
        $authManager=\Yii::$app->authManager;
        if ($authManager->getPermission($this->name)){
            $this->addError('权限已存在');
        }else{
            $permission=$authManager->createPermission($this->name);
            $permission->description=$this->description;
            return $authManager->add($permission);
        }
    }


    public function loadData(Permission $permission){
        $this->name=$permission->name;
        $this->description=$permission->description;
    }

    public function editPermission($name){
        $permission=\Yii::$app->authManager->getPermission($name);
        if ($name!=$this->name && \Yii::$app->authManager->getPermission($this->name) ){
            $this->addError('name','权限已存在');
        }else{
            $permission->name=$this->name;
            $permission->description=$this->description;
            return \Yii::$app->authManager->update($name,$permission);
        }
    }

}
