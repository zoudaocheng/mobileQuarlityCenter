<?php
/**
 * @link http://mqc.lcbint.com
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/7/11
 * Time: 15:18
 */

namespace app\models;


use yii\db\ActiveRecord;

class Mobile extends ActiveRecord
{
    public static function tableName()
    {
        return 'mobile';
    }

    public function attributeLabels()
    {
        return [
            'id' => '手机编号',
            'brand' => '手机品牌',
            'type' => '手机型号',
            'pixels' => '手机像素',
            'ip' => '手机IP',
            'version' => '固件版本',
            'imei' => 'IMEI号',
            'serial' => '序列号',
            'platform' => '平台',
            'owner' => '持有者',
            'remark' => '备注',
            'status' => '状态'
        ];
    }

    public function getBrands()
    {
        return $this->hasOne(MobileBrand::className(),['id' => 'brand']);
    }
}