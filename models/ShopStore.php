<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/7/14
 * Time: 15:39
 */

namespace app\models;


use yii\db\ActiveRecord;

class ShopStore extends ActiveRecord
{
    public static function getDb()
    {
        return \Yii::$app->get('lcb_db');
    }

    public static function tableName()
    {
        return 'shop_store';
    }

    public function attributes()
    {
        return [
            'id',
            'store_name',
            'store_nick_name',
            'place_id',
            'is_can_use_acci',
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '店铺ID',
            'store_name' => '店铺名称',
            'store_nick_name' => '短名称',
            'is_can_use_acci' => '事故车是否开启'
        ];
    }

    public function getPlace(){
        return $this->hasOne(Place::className(),['id' => 'place_id']);
    }


    public function getSettlementAccount()
    {
        return $this->hasOne(OrSettlementAccount::className(),['seller_id' => 'id']);
    }
    
    public function getGroup() {
        return $this->hasOne(ShopGroup::className(),['id' => 'group_id']);
    }
}