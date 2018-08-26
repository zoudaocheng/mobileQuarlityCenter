<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/12/1
 * Time: 11:04
 */

namespace app\models;


use yii\db\ActiveRecord;

class OrOrderComment extends ActiveRecord
{
    public static function getDb()
    {
        return \Yii::$app->get('lcb_db');
    }

    public static function tableName()
    {
        return 'or_order_comment';
    }

    public function attributes()
    {
        return [
            'actual_mileage', //保养里程
            'additional_content',//追加点评内容
            'car_full_name',//车型
            'car_number',//车牌号
            'category' ,// 类别：（对于保养订单：1、小型保养，2、中型保养，3、大型保养）
            'city_id',//保养店铺所在的城市id
            'city_name',//保养店铺所在的城市名
            'content',//评价内容
            'has_extra_service',//是否有额外服务0-没有 1- 有
            'is_recommendatory',//0：默认, 1:精选 -1：非精选
            'operator_reason',//屏蔽原因
            'operator_status',//评价状态:-1|审核不通过,0|未审核,1|机器审核通过,2|人工审核通过
            'overall_score',//整体评分
            'quality_score',//维修质量评分
            'service_score',//服务质量评分
            'speedy_score',//维修效率评分
            'store_id',//店铺ID
            'order_id',//订单ID
        ];
    }
}