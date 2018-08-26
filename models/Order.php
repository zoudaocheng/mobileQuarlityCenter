<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/7/5
 * Time: 15:44
 */

namespace app\models;


use yii\db\ActiveRecord;
use yii\db\Query;

class Order extends ActiveRecord
{
    public static function getDb()
    {
        return \Yii::$app->lcb_db;
    }

    public static function tableName()
    {
        return 'or_order';
    }

    public function attributes()
    {
        return [
            'id',
            'app_code',
            'comment_status',
            'contract_amount', //店铺金额：乐车邦付给店铺的金额(结算金额)
            'contract_base_amount',//店铺基准金额：乐车邦与店铺洽谈的基准金额
            'created_time',// 下单时间
            'order_number',
            'order_status',
            'order_type',
            'outer_trade_id',//	外部订单ID
            'payment_policy',//订单支付策略：1、直接支付，2，优惠券支付，100、内测支付
            'payment_status',//支付状态：0、未支付，1、正在支付，2、支付完成，3、支付失败
            'pay_amount',//支付金额：用户向乐车邦实际支付的金额
            'pay_time',// 订单支付时间 (datetime)
            'place_id',
            'platform_id',//第三方支付平台id
            'refund_status',//退款状态：0、无退款，1、退款中，2，退款完成，3、退款失败
            'sale_amount',//卖出金额：乐车邦向用户展示的卖出金额
            'settlement_status',//结算状态：1 未提交结算 2 已提交结算 3 已完成结算 4 未导入结算表 5 已导入结算表
            'store_id',
            'user_id',
            'my_car_id',//用户车辆ID
            'deleted',//是否删除： 0 - 未删除，1 - 删除
            'mobile',
            'sa_mobile'//SA手机号码
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '订单ID',
            'order_number' => '订单号',
            'mobile' => '手机号码',
            'sa_mobile' => 'SA手机号'
        ];
    }

    public function rules()
    {
        return [
            [['id','order_number','mobile','sa_mobile'],'trim']
        ];
    }

    public static function findByOrderNumber($orderNumber) {
        return static::findOne(['order_number' => $orderNumber]);
    }

    public function getStore() {
        return $this->hasOne(ShopStore::className(),['id' => 'store_id']);
    }

    public function getPlace() {
        return $this->hasOne(Place::className(),['id' => 'place_id']);
    }

    public function getOrderCoupon() {
        return $this->hasOne(OrderCoupon::className(),['order_id' => 'id']);
    }

    public function getMaintenanceItem() {
        return $this->hasOne(MaintenanceItem::className(),['order_id' => 'id']);
    }

    public function getSprayItem (){
        return $this->hasOne(SprayItem::className(),['order_id' => 'id']);
    }

    public function getSa() {
        return $this->hasOne(SaOrder::className(),['order_id' => 'id']);
    }

    public function getUser() {
       return $this->hasOne(Customer::className(),['id' => 'user_id']);
    }

    public function getApplication () {
        return $this->hasOne(CommApplication::className(),['app_code' => 'app_code']);
    }

    public function getOrderSource (){
        return $this->hasOne(OrOrderSource::className(),['order_id' => 'id']);
    }

    public function getPaymentNo() {
        return $this->hasOne(OrOrderPayment::className(),['order_id' => 'id']);
    }

    public function getPaymentCode() {
        return $this->hasOne(OrOrderPaymentNotify::className(),['order_id' => 'id']);
    }

    public function getOrderBd() {
        return $this->hasOne(UsUserGroup::className(),['group_id' => 'store_id'])
            ->where(['group_type' => 2,'role_id' => 16]);
    }

    public function getOrderComment() {
        return $this->hasOne(OrOrderComment::className(),['order_id' => 'id']);
    }
}