<?php
/**
 * 城市信息
 * @link http://mqc.lcbint.com
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/7/14
 * Time: 15:57
 */

namespace app\models;


use yii\db\ActiveRecord;

class Place extends ActiveRecord
{
    public static function getDb()
    {
        return \Yii::$app->get('lcb_db');
    }

    public static function tableName()
    {
        return 'comm_place';
    }

    public function attributes()
    {
        return [
            'id',
            'name',
        ];
    }
}