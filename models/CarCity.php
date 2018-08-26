<?php
/**
 * 开能城市Model
 * @link http://mqc.lcbint.com
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/7/14
 * Time: 14:58
 */

namespace app\models;


use yii\db\ActiveRecord;

class CarCity extends ActiveRecord
{
    public static function getDb()
    {
        return \Yii::$app->get('lcb_db');
    }

    public static function tableName()
    {
        return 'car_cities';
    }

    public function attributes()
    {
        return [
            'id',
            'city_id',
            'name',
            'drive_status'
        ];
    }

    public function attributeLabels()
    {
        return [
            'city_id' => '城市编号',
            'name' => '城市',
            'drive_status' => '代驾信息'
        ];
    }
}