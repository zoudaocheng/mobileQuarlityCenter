<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/11/29
 * Time: 16:33
 */

namespace app\models;


use yii\db\ActiveRecord;

class OrOrderPaymentNotify extends ActiveRecord
{
    public static function getDb()
    {
        return \Yii::$app->get('lcb_db');
    }

    public static function tableName()
    {
        return 'or_order_payment_notify';
    }

    public function attributes()
    {
        return [
            'id',
            'order_id',
            'platform_id', //第三方支付平台ID,4、支付宝WAP，5、支付宝APP，6、微信H5，7、微信APP，8、银联WAP，9、银联APP
            'payment_code',//支付单号
        ];
    }
}