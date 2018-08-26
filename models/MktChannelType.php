<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/8/17
 * Time: 17:06
 */

namespace app\models;


use yii\db\ActiveRecord;

class MktChannelType extends ActiveRecord
{
    public static function getDb()
    {
        return \Yii::$app->get('lcb_market_db');
    }

    public static function tableName()
    {
        return 'mkt_channel_type';
    }
}