<?php
/**
 * 订单SA
 * @link http://mqc.lcbint.com
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/7/14
 * Time: 18:21
 */

namespace app\models;


use yii\db\ActiveRecord;

class SaOrder extends ActiveRecord
{
    public static function getDb()
    {
        return \Yii::$app->get('lcb_db');
    }

    public static function tableName()
    {
        return 'sa_order_record';
    }

    public function attributes()
    {
        return [
            'order_id',
            'awardTime',
            'process_status',
            'sa_mobile',
            'sa_name',
            'sa_user_id',
            'source',//订单SA来源(1:抢单，2：系统设置[门店抢单功能开放]，3：系统设置[门店抢单功能关闭])
        ];
    }
}