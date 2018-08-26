<?php
/**
 * Created by PhpStorm.
 * User: zoudaocheng
 * Date: 17/10/30
 * Time: 15:31
 */

namespace app\models;


use yii\db\ActiveRecord;

class WalletAccount extends ActiveRecord
{
    public static function getDb()
    {
        return \Yii::$app->lcb_db;
    }

    public static function tableName()
    {
        return 'wallet_account';
    }

    public function attributeLabels()
    {
        return [
            'available_amount' => '可用金额',
            'create_time' => '创建时间',
            'frozen_amount' => '冻结金额',
            'id' => '编号',
            'total_amount' => '总金额',
            'updated_time' => '更新时间',
            'user_id' => '用户ID'
        ];
    }

    public function getCustomer(){
        return $this->hasOne(Customer::className(),['id' => 'user_id']);
    }
}