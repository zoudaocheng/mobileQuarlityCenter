<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/10/6
 * Time: 19:34
 */

namespace app\models;


use yii\db\ActiveRecord;

class MqcOwner extends ActiveRecord
{
    public static function tableName()
    {
        return 'owner';
    }

    public function attributes()
    {
        return [
            'mobile',
            'register',
            'updated_at',
        ]; //只需要返回手机号一个字段
    }
}