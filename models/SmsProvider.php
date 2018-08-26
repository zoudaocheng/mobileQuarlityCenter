<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/12/26
 * Time: 18:09
 */

namespace app\models;


use yii\db\ActiveRecord;

class SmsProvider extends ActiveRecord
{
    public static function getDb()
    {
        return \Yii::$app->lcb_db;
    }

    public static function tableName()
    {
        return 'sms_provider';
    }

    public function attributeLabels()
    {
        return [
            'id' => '提供商编号',
            'name' => '名称',
            'report_flag' => '获取状态报告', //0 - 不获取，1 - 获取
        ];
    }
}