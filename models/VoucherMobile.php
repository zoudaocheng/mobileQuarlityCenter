<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/7/19
 * Time: 10:19
 */

namespace app\models;


use yii\db\ActiveRecord;

class VoucherMobile extends ActiveRecord
{
    public static function getDb()
    {
        return \Yii::$app->get('lcb_db');
    }

    public static function tableName()
    {
        return 'mkt_voucher_mobile';
    }

    public function attributes()
    {
        return [
            'activity_id',
            'voucher_strategy_id',
            'mobile',
            'store_id',
            'give_status'
        ];
    }

    public function getCustomer() {
        return $this->hasOne(Customer::className(),['mobile' => 'mobile']);
    }
}