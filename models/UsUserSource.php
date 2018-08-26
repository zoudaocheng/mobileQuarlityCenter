<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/12/22
 * Time: 15:49
 */

namespace app\models;


use yii\db\ActiveRecord;

class UsUserSource extends ActiveRecord
{
    public static function getDb()
    {
        return \Yii::$app->lcb_db;
    }

    public static function tableName()
    {
        return 'us_user_source';
    }

    public function attributeLabels()
    {
        return [
            'act_id' => '活动ID',
            'alliance_id' => '联盟ID',
            'app_code' => 'appCode',
            'channel_id' => '渠道ID',
            'city' => '城市名称',
            'cp_id' => '渠道包ID',
            'user_id' => '用户ID',
            'oauth_provider' => '三方渠道信息'
        ];
    }

    public function getApplication() {
        return $this->hasOne(CommApplication::className(),['app_code' => 'app_code']);
    }
}