<?php
/**
 * 店铺结算账号模型
 * @link http://mqc.lcbint.com
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/7/14
 * Time: 15:49
 */

namespace app\models;


use yii\db\ActiveRecord;

class OrSettlementAccount extends ActiveRecord
{
    public static function getDb()
    {
        return \Yii::$app->get('lcb_db');
    }

    public static function tableName()
    {
        return 'or_settlement_account';
    }

    public function attributes()
    {
        return [
            'account_name', //户名
            'account_status',//账号状态 1正常 2暂停结算
            'branch_bank_name',
            'bank_name',
            'city_id',
            'seller_id', //结算商家ID 集团ID或者店铺ID
            'seller_name',// 结算账户名称
            'seller_type',//结算商家类型 1集团 2店铺
            'settlement_days',//结算账期
            'settlement_type',//结算方式 1集团结算 2店铺独立结算 3渠道结算
        ];
    }

    public function getPlace()
    {
        return $this->hasOne(Place::className(),['id' => 'city_id']);
    }
}