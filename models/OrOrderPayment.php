<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/11/29
 * Time: 16:17
 */

namespace app\models;


use yii\db\ActiveRecord;

class OrOrderPayment extends ActiveRecord
{
    public static function getDb()
    {
        return \Yii::$app->get('lcb_db');
    }

    public static function tableName()
    {
        return 'or_order_payment';
    }

    public function attributes()
    {
        return [
            'id',
            'order_id',
            'payment_no', //支付流水号
            'created_time'
        ];
    }
}