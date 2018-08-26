<?php
/**
 * @link http://mqc.lcbint.com
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/7/4
 * Time: 15:01
 */

namespace app\models;


use yii\db\ActiveRecord;

class Operation extends ActiveRecord
{
    public $strategy_id;
    public $activity_id;
    public $mobile;
    public $order_number;
    public $reason;
    public $password;
    public $content;
    public $order;
    //推送消息字段
    public $app_code;
    public $app_id;
    public $category;
    public $notify_flag;
    public $send_time;
    public $title;
    public $url;
    public $tags;
    public $user_id;


    public function rules()
    {
        return [
            [['strategy_id','activity_id','mobile','order_number','reason','password','content','title','url'],'required'],
            [['strategy_id','activity_id','mobile','order_number','reason','password','content','title','url'],'trim'],
            [['strategy_id','activity_id','mobile'],'required','on' => 'voucher'], //优惠券发放
            [['reason','order_number'],'required','on' => 'order'], //订单状态回滚
            [['mobile','content'],'required','on' => 'sendSms'], //发送短信内容
            [['mobile','password'],'required','on' => 'resetPassword'],
            [['app_code','app_id','category','content','notify_flag','send_time','title','url'],'required','on' => 'pushMessage'],
            ['user_id', 'default', 'value' => \Yii::$app->user->identity->id],
        ];
    }

    public function attributeLabels()
    {
        return [
            'strategy_id' => '策略编号',
            'activity_id' => '活动编号',
            'mobile' => '手机号码',
            'order_number' => '订单号',
            'order_id' => '订单编号',
            'reason' => '操作原因',
            'password' => '输入密码',
            'content' => '消息内容',
            'title' => '消息标题',
            'url' => '输入链接',
            'send_time' => '发送时间',
            'tags' => '标签',
            'user_id' => '用户ID'
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['voucher'] = ['strategy_id','activity_id','mobile'];
        $scenarios['order'] = ['reason','order_number'];
        $scenarios['resetPassword'] = ['password','mobile'];
        $scenarios['sendSms'] = ['mobile','content'];
        $scenarios['pushMessage'] = ['app_code','app_id','category','content','notify_flag','send_time','title','url'];
        return $scenarios;
    }
}