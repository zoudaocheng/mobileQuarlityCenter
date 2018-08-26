<?php
/**
 * 优惠券活动信息
 * @link http://mqc.lechebang.com
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/7/26
 * Time: 15:55
 */

namespace app\models;


use yii\db\ActiveRecord;

class MktActivity extends ActiveRecord
{
    public static function getDb()
    {
        return \Yii::$app->lcb_db;
    }

    public static function tableName()
    {
        return 'mkt_activity';
    }

    public function attributes()
    {
        return [
            'activity_kind',//活动种类 1：内测活动，2：红包活动 4 大促活动
            'content',//活动内容
            'activity_name',//活动的英文名，程序中取相应的service使用,
            'activity_status',//活动状态 0:未启动,1:进行中,2:已完成
            'activity_way',//活动方式 1:页面链接 2:短信 3:消息推送 4:优惠券 5:4S店召回 6:电子券 7:实体卡 8:微信卡券
        ];
    }
}