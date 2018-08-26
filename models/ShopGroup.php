<?php
/**
 * 店铺集团信息
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/7/14
 * Time: 18:27
 */

namespace app\models;


use yii\db\ActiveRecord;

class ShopGroup extends ActiveRecord
{
    public static function getDb()
    {
        return \Yii::$app->get('lcb_db');
    }

    public static function tableName()
    {
        return 'shop_group';
    }

    public function attributes()
    {
        return [
            'id',
            'group_name', // 集团名称
            'contacts_info',//联系人 JSON格式
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '集团编号',
            'group_name' => '集团名称',
            'contacts_info' => '集团联系人'
        ];
    }
}