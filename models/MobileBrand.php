<?php
/**
 * @link http://mqc.lcbint.com
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/7/11
 * Time: 17:26
 */

namespace app\models;


use yii\db\ActiveRecord;

class MobileBrand extends ActiveRecord
{
    public static function tableName()
    {
        return 'brand';
    }
}