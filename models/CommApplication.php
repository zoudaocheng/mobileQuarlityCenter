<?php
/**
 * 渠道信息表
 * @link http://mqc.lechebang.com
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/7/26
 * Time: 14:13
 */

namespace app\models;


use yii\db\ActiveRecord;

class CommApplication extends ActiveRecord
{
    public static function getDb()
    {
        return \Yii::$app->get('lcb_db');
    }

    public static function tableName()
    {
        return 'comm_application';
    }

    public function attributes()
    {
        return [
            'name',
            'app_code'
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => '渠道名称',
            'app_code' => '渠道号',
        ];
    }
}