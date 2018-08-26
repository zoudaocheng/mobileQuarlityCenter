<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/7/27
 * Time: 9:48
 */

namespace app\models;


use yii\db\ActiveRecord;

class CarBrandType extends ActiveRecord
{
    public static function getDb()
    {
        return \Yii::$app->get('lcb_car_db');
    }

    public static function tableName()
    {
        return 'car_brand_type';
    }

    public function attributes()
    {
        return [
            'id',
            'level',//1|brand|品牌,2|product|厂商,3|model|车型,4|year|年份,5|style|款式
            'name',
            'path'
        ];
    }
}