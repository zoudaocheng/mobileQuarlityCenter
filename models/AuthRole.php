<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/7/15
 * Time: 16:49
 */

namespace app\models;


use yii\db\ActiveRecord;

class AuthRole extends ActiveRecord
{
    public static function getDb()
    {
        return \Yii::$app->get('lcb_authority_db');
    }

    public static function tableName()
    {
        return 'au_role';
    }
}