<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2017/1/9
 * Time: 14:42
 */

namespace app\models;


use yii\db\ActiveRecord;

class UsOauthUser extends ActiveRecord
{
    public static function getDb()
    {
        return \Yii::$app->lcb_db;
    }

    public static function tableName()
    {
        return 'us_oauth_user';
    }

    public function attributeLabels()
    {
        return [
            'created_time' => '添加时间',
            'provider' => '渠道名称',
            'user_id' => '用户ID',
            'id' => 'oauthID'
        ];
    }
}