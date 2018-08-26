<?php
/**
 * @link http://mqc.lcbint.com
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/5/11
 * Time: 11:46
 */

namespace app\models;


use yii\db\ActiveRecord;
use yii\db\Query;

class Voucher extends ActiveRecord
{
    public static function getDb()
    {
        return \Yii::$app->lcb_db;
    }

    public static function tableName()
    {
        return 'mkt_voucher';
    }

    public function attributes()
    {
        return [
            'id',
            'activity_id',
            'amount',
            'consume_order_id',
            'start_time',
            'end_time',
            'store_id',
            'strategy_id',
            'strategy_kind',
            'user_id',
            'voucher_code',
            'voucher_status', //状态 1未绑定 2已绑定 3已使用 4已过期
        ];
    }

    public function attributeLabels()
    {
        return [
            'mobile'      => '手机号',
            'strategy_id' => '策略编号',
            'activity_id' => '活动编号',
        ];
    }

    public static function  getVoucherList($data) {
        return (new Query())
            ->select(['mkt_voucher.id AS ID,mkt_voucher.strategy_id AS STRATEGY_ID,mkt_voucher.activity_id AS ACTIVITY_ID,(mkt_voucher.amount/100) AS AMOUNT,
        mkt_voucher.consume_order_id AS ORDER_ID,or_order.order_number AS ONUM,mkt_voucher.voucher_status AS STATUS,mkt_activity.content AS ACTIVITY, 
        mkt_voucher.created_time AS CREATE_AT,mkt_voucher.updated_time AS UPDATE_AT,mkt_voucher.start_time AS START_AT,mkt_voucher_strategy.content AS STRATEGY,
        mkt_voucher.start_time AS START_TIME,mkt_voucher.end_time AS END_TIME'])
            ->from('mkt_voucher')
            ->leftJoin('or_order','or_order.id = mkt_voucher.consume_order_id')
            ->leftJoin('mkt_voucher_strategy','mkt_voucher.strategy_id = mkt_voucher_strategy.id')
            ->leftJoin('mkt_activity','mkt_activity.id = mkt_voucher.activity_id')
            ->where($data)
            ->orderBy('mkt_voucher.end_time DESC')
            ->all(\Yii::$app->lcb_db);
    }

    public function getCustomer(){
        return $this->hasOne(Customer::className(),['id' => 'user_id']);
    }
    
    public function getActivity(){
        return $this->hasOne(MktActivity::className(),['id' => 'activity_id']);
    }

    public function getStrategy() {
        return $this->hasOne(MktStrategy::className(),['id' => 'strategy_id']);
    }

    public function getOrder() {
        return $this->hasOne(Order::className(),['id' => 'consume_order_id']);
    }
}