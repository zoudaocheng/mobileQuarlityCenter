<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/12/26
 * Time: 18:12
 */

namespace app\models;


use yii\db\ActiveRecord;

class SmsReport extends ActiveRecord
{
    public static function getDb()
    {
        return \Yii::$app->lcb_db;
    }

    public static function tableName()
    {
        return 'sms_report';
    }

    public function attributeLabels()
    {
        return [
            'message_id' => '短信ID',
            'mobile' => '手机号码',
            'report_flag' => '状态报告',//-1、未知或未完成，0、失败，1、成功
            'report_time' => '报告时间',
            'result_code' => '状态报告结果码'
        ];
    }
}