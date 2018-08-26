<?php
/**
 * 优惠券策略信息
 * @link http://mqc.lechebang.com
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/7/26
 * Time: 15:44
 */

namespace app\models;


use yii\db\ActiveRecord;

class MktStrategy extends ActiveRecord
{
    public static function getDb()
    {
        return \Yii::$app->get('lcb_db');
    }

    public static function tableName()
    {
        return 'mkt_voucher_strategy';
    }

    public function attributes()
    {
        return [
            'id',
            'content',
            'strategy_kind',//策略种类 1抵用券 2特价券 3折扣券
            'strategy_name',//策略的英文名，程序中取相应的service使用
            'content',//策略内容,抵用券使用条款
        ];
    }
}