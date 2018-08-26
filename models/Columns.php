<?php
/**
 * 数据库 - 列表
 * Created by PhpStorm.
 * User: daocheng
 * Date: 17-8-10
 * Time: 下午3:18
 */

namespace app\models;


use yii\db\ActiveRecord;

class Columns extends ActiveRecord
{
    private $column_name;
    private $column_type;
    private $is_nullable;
    private $column_default;
    private $column_comment;

    public static function getDb()
    {
        return \Yii::$app->database;
    }

    public static function tableName()
    {
        return 'columns';
    }
}