<?php
/**
 * Created by PhpStorm.
 * User: zoudaocheng
 * Date: 17/10/30
 * Time: 09:47
 */

namespace app\models;
use yii\db\ActiveRecord;

class WalletDetailLog extends ActiveRecord
{
    public static function getDb()
    {
        return \Yii::$app->lcb_db;
    }

    public static function tableName()
    {
        return 'wallet_detail_log';
    }

    public function attributeLabels()
    {
        return [
            'amount' => '变化金额',
            'app_code' => '渠道',
            'create_time' => '创建时间',
            'host' => '调用地址',
            'id' => 'ID',
            'method' => '变化方法',
            'order_id' => '订单号',
            'related_id' => '关联ID',
            'related_type' => '关联方法',
            'remark' => '备注说明',
            'user_id' => '用户ID',
        ];
    }

    public function getCustomer(){
        return $this->hasOne(Customer::className(),['id' => 'user_id']);
    }

    public function getOrder(){
        return $this->hasOne(Order::className(),['id' => 'order_id']);
    }

    public function getApplication(){
        return $this->hasOne(CommApplication::className(),['app_code' => 'app_code']);
    }
}