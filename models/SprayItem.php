<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2017/3/20
 * Time: 10:17
 */

namespace app\models;


use yii\db\ActiveRecord;

class SprayItem extends ActiveRecord
{
    public static function getDb()
    {
        return \Yii::$app->get('lcb_db');
    }

    public static function tableName()
    {
        return 'or_order_spray_item';
    }

    public function attributes()
    {
        return [
            'order_id',
            'process_status',//订单处理状态：0、未处理，1、订单确认（S[可选]），2、到店，3、开始保养，4、保养完工
            'store_id',
            'contact_user_mobile',//联系人手机
            'brand_type_name',//车型
            'store_name',//店铺
            'user_mobile',//订单电话
            'appoint_time',//预约时间
            'actual_mileage',//保养里程
            'car_number',//车牌号
            'finish_time' //完成时间
        ];
    }
}