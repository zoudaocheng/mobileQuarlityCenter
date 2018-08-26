<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/7/27
 * Time: 10:17
 */

namespace app\models;


use yii\db\ActiveRecord;

class MyCar extends ActiveRecord
{
    public static function getDb()
    {
        return \Yii::$app->get('lcb_db');
    }

    public static function tableName()
    {
        return 'mc_my_car';
    }

    public function attributes()
    {
        return [
            'id',
            'brand_type_id',
            'buy_date',
            'car_number',
            'created_time',
            'deleted',
            'engine_number',
            'is_default',
            'mileage',
            'user_id',
            'vin'
        ];
    }

    public function getCar() {
        return $this->hasOne(CarBrandType::className(),['id' => 'brand_type_id']);
    }
}