<?php
/**
 * 订单保养项
 * @link http://mqc.lcbint.com
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/7/14
 * Time: 18:17
 */

namespace app\models;


use yii\db\ActiveRecord;

class MaintenanceItem extends ActiveRecord
{
    public static function getDb()
    {
        return \Yii::$app->get('lcb_db');
    }

    public static function tableName()
    {
        return 'or_order_maintenance_item';
    }

    public function attributes()
    {
        return [
            'order_id',
            'maintenance_items_info',//保养项目说明，json格式
            'maintenance_items',//保养项目：对应 'maintenance_plan_id' 格式为以逗号隔开的 car_maintenance_item id（item_id1,item_id2,item_id3）
            'maintenance_plan_id',//保养方案ID
            'process_status',//订单处理状态：0、未处理，1、订单确认（S[可选]），2、到店，3、开始保养，4、保养完工
            'store_id',
            'contact_user_mobile',//联系人手机
            'discount_type',//折扣类型,0:白闲,1:夜场,2:忙时
            'brand_type_name',//车型
            'store_name',//店铺
            'contact_user_mobile',//订单电话
            'appoint_time',//预约时间
            'actual_mileage',//保养里程
            'car_number',//车牌号
            'finish_time' //完成时间
        ];
    }
}