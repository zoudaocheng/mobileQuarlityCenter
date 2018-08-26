<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/12/1
 * Time: 12:54
 */

namespace app\models;


use yii\db\ActiveRecord;

class UsUserInfo extends ActiveRecord
{
    public static function getDb()
    {
        return \Yii::$app->get('lcb_db');
    }

    public static function tableName()
    {
        return 'us_user_info';
    }

    public function attributes()
    {
        return [
            'user_id',
            'real_name',
            'gender',
            'nick_name',
            'face_image_url',
            'birthday'
        ];
    }
}