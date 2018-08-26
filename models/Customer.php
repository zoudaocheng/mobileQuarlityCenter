<?php
/**
 * 乐车邦用户信息表
 * @link http://mqc.lcbint.com
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/7/6
 * Time: 11:43
 */

namespace app\models;


use yii\db\ActiveRecord;

class Customer extends ActiveRecord
{
    public static function getDb()
    {
        return \Yii::$app->lcb_db;
    }

    public static function tableName()
    {
        return 'us_user';
    }

    public function attributeLabels()
    {
        return [
            'mobile' => '手机号码',
        ];
    }

    public function attributes()
    {
        return [
            'id',
            'mobile',
            'mobile_status',
            'created_time',
            'login_name'
        ];
    }

    public function rules()
    {
        return [
            [['mobile'],'required'],
            [['mobile'],'trim'],
        ];
    }

    public static function findByMobile($mobile) {
        return static::findOne(['mobile' => $mobile]);
    }

    public function getVoucher() {
        return $this->hasMany(Voucher::className(),['user_id' => 'id']);
    }

    public function getMyCar() {
        return $this->hasMany(MyCar::className(),['user_id' => 'id']);
    }

    public function getUserSource() {
       return $this->hasOne(UsUserSource::className(),['user_id' => 'id']);
    }
}