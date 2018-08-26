<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/12/1
 * Time: 11:42
 * 用户权限详情表
 */

namespace app\models;


use yii\db\ActiveRecord;

class UsUserGroup extends ActiveRecord
{
    public static function getDb()
    {
        return \Yii::$app->get('lcb_authority_db');
    }

    public  static function tableName()
    {
        return 'us_user_group';
    }

    public function attributes()
    {
        return [
            'group_type',//	组类型 1 集团 2 店铺 3 地区 0 后台
            'group_id',//组id
            'role_id',//角色ID
            'user_id',//
        ];
    }

    public function getUsUserInfo() {
        return $this->hasOne(UsUserInfo::className(),['user_id' => 'user_id']);
    }

    public function getUsUser(){
        return $this->hasOne(Customer::className(),['id' => 'user_id']);
    }
}