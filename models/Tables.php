<?php
/**
 * 数据库表名
 * Created by PhpStorm.
 * User: daocheng
 * Date: 17-8-10
 * Time: 下午3:17
 */

namespace app\models;


use yii\db\ActiveRecord;

class Tables extends ActiveRecord
{
    private $table_schema;
    private $table_name;
    private $table_comment;

    public static function getDb()
    {
        return \Yii::$app->database;
    }

    public static function tableName()
    {
        return 'tables';
    }
}