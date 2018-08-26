<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/12/26
 * Time: 17:36
 */

namespace app\models;


use yii\db\ActiveRecord;

class SmsLog extends ActiveRecord
{
    public static function getDb()
    {
        return \Yii::$app->lcb_db;
    }

    public static function tableName()
    {
        return 'sms_log';
    }

    public function attributeLabels()
    {
        return [
            'id' => '编号',
            'provider_id' => '运营商',
            'message_id' => '消息ID',
            'content' => '短信内容',
            'mobiles' => '手机号',
            'result_code' => '提供商发送结果状态码',
            'send_flag' => '发送标识', //-1、接口调用失败，0、接口返回失败，1、接口返回成功
            'template_name' => '短信类型'
        ];
    }

    public function getProvider()
    {
        return $this->hasOne(SmsProvider::className(),['id' => 'provider_id']);
    }

    public function getReport()
    {
        return $this->hasOne(SmsReport::className(),['message_id' => 'message_id'])->limit(1); //只需要返回一条数据即可
    }
}