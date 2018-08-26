<?php
/**
 * 订单乐车券
 * @link http://mqc.lcbint.com
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/7/14
 * Time: 18:17
 */

namespace app\models;


use yii\db\ActiveRecord;

class OrderCoupon extends ActiveRecord
{
    public static function getDb()
    {
        return \Yii::$app->get('lcb_db');
    }

    public static function tableName()
    {
        return 'or_order_coupon';
    }

    public function attributes()
    {
        return [
            'coupon_number',
            'order_id',
            'user_id',
            'used_status'
        ];
    }
}