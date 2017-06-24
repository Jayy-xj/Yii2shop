<?php

namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "locations".
 *
 * @property string $id
 * @property string $name
 * @property string $parent_id
 * @property integer $level
 */
class Locations extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'locations';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'parent_id'], 'required'],
            [['parent_id', 'level'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }
    public static function getRegion($parentId=0)
    {
        $result = static::find()->where(['parent_id'=>$parentId])->asArray()->all();
        return ArrayHelper::map($result, 'id', 'name');
    }
    public static function getProvince($id){
        $province = Locations::findOne(['id'=>$id]);
        return  $province->name;
    }
    public static function getCity($id){
        $city = Locations::findOne(['id'=>$id]);
        return  $city->name;
    }
    public static function getDistrict($id){
        $district = Locations::findOne(['id'=>$id]);
        return  $district->name;
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'parent_id' => 'Parent ID',
            'level' => 'Level',
        ];
    }
}
