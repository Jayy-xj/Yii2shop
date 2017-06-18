<?php
namespace backend\models;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\rbac\Role;

class RoleForm extends Model{
    public $name;
    public $description;
    public $permissions=[];

    public function rules()
    {
        return [
            [['name','description'],'required'],
            ['permissions','safe']
        ];
    }
    public function attributeLabels()
    {
        return [
            'name'=>'角色名称',
            'description'=>'角色描述',
            'permissions'=>'权限'
        ];
    }
    public static function  getPermissionOption(){
        $permission=\Yii::$app->authManager->getPermissions();
        return ArrayHelper::map($permission,'name','description');
    }
    public function addRole(){
        $authManager=\Yii::$app->authManager;
        if ($authManager->getRole($this->name)){
            $this->addError('角色已存在');
        }else{
            $role=$authManager->createRole($this->name);
            $role->description=$this->description;
            if ($authManager->add($role)){
                //关联权限
//                var_dump($this->permissions);exit;
                foreach ($this->permissions as $permissionName){
                    $permission=$authManager->getPermission($permissionName);
                    if ($permission){
                        $authManager->addChild($role,$permission);
                    }
                }
                return true;
            }
        }
        return false;
    }
    public function loadData(Role $role){
        $this->name=$role->name;
        $this->description=$role->description;
        $this->permissions=ArrayHelper::map( \Yii::$app->authManager->getPermissionsByRole($role->name),'name','name');
    }
    public function editRole($name){
            $role=\Yii::$app->authManager->getRole($name);
            if ($name!=$this->name && \Yii::$app->authManager->getRole($this->name)){
                $this->addError('name','用户已存在');
            }else{
                $role->name=$this->name;
                $role->description=$this->description;
                if (\Yii::$app->authManager->update($name, $role)){
                    \Yii::$app->authManager->removeChildren($role);
                    foreach ($this->permissions as $permissionName){
                        $permission=\Yii::$app->authManager->getPermission($permissionName);
                        if ($permission){
                            \Yii::$app->authManager->addChild($role,$permission);
                        }
                    }
                    return true;
                }
            }
            return false;
    }


}