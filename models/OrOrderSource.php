<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/11/29
 * Time: 16:30
 */

namespace app\models;


use yii\db\ActiveRecord;

class OrOrderSource extends ActiveRecord
{
    public static function getDb()
    {
        return \Yii::$app->get('lcb_db');
    }

    public static function tableName()
    {
        return 'or_order_source';
    }

    public function attributes()
    {
        return [
            'id',
            'order_id',
            'cp_id',
        ];
    }
}