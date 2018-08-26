<?php
/**
 * @link http://mqc.lechebang.com
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/8/17
 * Time: 8:17
 */

namespace app\models;


use yii\db\ActiveRecord;

class MktUserBehavior extends ActiveRecord
{
    public static function getDb()
    {
        return \Yii::$app->get('lcb_log_db');
    }

    public static function tableName()
    {
        return 'mkt_user_behavior_log';
    }

    public function attributes()
    {
        return [
            'id',
            'app_code',
            'user_id',
            'activity_id',
            'alliance_id',
            'site_id',
            'channel_id',
            'store_id',
            'order_id',
            'event_type',
            'event_content',
            'event_time',
            'created_time',
        ];
    }

    public function attributeLabels()
    {
        return [
            'app_code' => '应用渠道',
            'user_id' => '用户编号',
            'activity_id' => '活动(act_id)',
            'alliance_id' => '一级渠道信息(alliance_id)',
            'site_id' => '二级渠道信息(site_id)',
            'channel_id' => '三级渠道信息(channel_id)',
            'store_id' => '店铺',
            'order_id' => '订单编号',
            'event_type' => '用户行为类型',
            'event_content' => '用户行为内容',
            'event_time' => '行为发生时间',
            'created_time' => '记录日志时间',
        ];
    }

    public function rules()
    {
        return [
            [['activity_id','alliance_id','site_id','channel_id'],'integer']
        ];
    }

    public function getOrder()
    {
        return $this->hasOne(Order::className(),['id' => 'order_id']);
    }

    public function getStore()
    {
        return $this->hasOne(ShopStore::className(),['id' => 'store_id']);
    }

    public function getApplication()
    {
        return $this->hasOne(CommApplication::className(),['app_code' => 'app_code']);
    }
}