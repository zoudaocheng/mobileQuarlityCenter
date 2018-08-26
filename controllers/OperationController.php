<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/7/5
 * Time: 9:54
 */

namespace app\controllers;


use app\components\Soa;
use app\models\CarCity;
use app\models\Customer;
use app\models\Operation;
use app\models\Order;
use app\models\Voucher;
use app\models\VoucherMobile;
use yii\helpers\ArrayHelper;

class OperationController extends CommController
{
    private $url = 'http://soa.tec.lcbcorp.com:10006/';

    public function actionSendSms() {
        $url = $this->url.'sms_admin/sendMessage';
        $data = \Yii::$app->request->post('Operation');
        if($data && $data['mobile'] && $data['content'])
        {
            $data = array(
                'channelId' => 0,
                'priority' => 1,
                'templateId' => 0,
                'templateName' => '',
                'model' => null,
                'mobileList' => null,
                'mobiles' => $data['mobile'],
                'sendTime' => null,
                'content' => $data['content'],
                'subPort' => '',
                'batches' => '',
                'appCode' => 300,
            );
            $soaReturn = Soa::post($url,json_encode($data))['RETURN'];
            if (200 == $soaReturn->statusCode){
                $result['status'] = 1;
                $result['message'] = '发送成功';
            }else{
                $result['status'] = 0;
                $result['message'] = $soaReturn->msg;
            }
            return $this->renderJson($result);
        }else{
            return $this->render('send-sms',[
                'model' => new Operation(),
            ]);
        }
    }

    public function actionGiveVoucher() {
        $url = $url = Soa::soa(\Yii::$app->request->post('soa')).'voucher_admin/giveVoucher';
        $data = \Yii::$app->request->post('Operation');
        $strategies = explode(',',$data['strategy_id']);
        $fail = array();
        $message = array();
        if($data) {
            foreach ($strategies as $strategy){
                $data = [
                    'mobile' => $data['mobile'],
                    'activityId' => $data['activity_id'],
                    'strategyId' => $strategy,
                    'appCode' =>  300
                ];
                $soaReturn = Soa::post($url,json_encode($data))['RETURN'];
                if (200 <> $soaReturn->statusCode) {
                    array_push($fail,$strategy);
                    array_push($message,$soaReturn->msg);
                    array_unique($message);  //去除重复值
                }
            }
            if (empty($fail)){
                $result['status'] = 1;
                $result['message'] = '发送成功';
            }else{
                $result['status'] = 0;
                $result['message'] = '策略['.implode(',',$fail).']因['.implode('|',$message).']失败';
            }
            return $this->renderJson($result);
        }else{
            return $this->render('give-voucher',[
                'model' => new Operation(),
                'listSoa' => $this->Soa()
            ]);
        }
    }

    /**
     * 重置SA登录密码
     */
    public function actionResetPassword() {
        $url = $this->url.'user_admin/updateUser';
        $data = \Yii::$app->request->post('Operation');
        if($data) {
            $data = [
                'id' => Customer::findByMobile($data['mobile'])['id'],
                'passwd' => $data['password'],
                'appCode' =>  300
            ];
            $soaReturn = Soa::post($url,json_encode($data))['RETURN'];
            if (200 == $soaReturn->statusCode){
                $result['status'] = 1;
                $result['message'] = '重置成功';
            }else{
                $result['status'] = 0;
                $result['message'] = $soaReturn->msg;
            }
            return $this->renderJson($result);
        }else{
            return $this->render('reset-password',[
                'model' => new Operation(),
            ]);
        }
    }

    /**
     * 订单状态回滚
     */
    public function actionRevertOrder()
    {
        $url = $this->url . 'mtnorder_admin/revertOrderPaid';
        $data = \Yii::$app->request->post('Operation');
        if ($data) {
            $data = [
                'orderId'  => Order::findByOrderNumber($data['order_number'])['id'] ,
                'description' => $data['reason'],
                'operatorId' => 151131,
                'operatorType' => 3,
                'appCode' => 300
            ];
            $soaReturn = Soa::post($url, json_encode($data))['RETURN'];
            if (200 == $soaReturn->statusCode) {
                $result['status'] = 1;
                $result['message'] = '回滚成功';
            } else {
                $result['status'] = 0;
                $result['message'] = $soaReturn->msg;
            }
            return $this->renderJson($result);
        } else {
            return $this->render('revert-order', [
                'model' => new Operation(),
            ]);
        }
    }

    public function actionPushMessage() {
        $url = Soa::soa(\Yii::$app->request->post('soa')).'push_admin/pushMessage';
        $data = \Yii::$app->request->post('Operation');
        $phoneType =  intval(\Yii::$app->request->get('phone_type'));
        $platform = \Yii::$app->request->post('platform');
        if ($data) {
            $data = [
                'appCode'  => 700 ,
                'appId' => intval(\Yii::$app->request->post('app_id')),
                'category' => intval(\Yii::$app->request->post('category')),
                'content' => $data['content'],
                'notifyFlag' => intval(\Yii::$app->request->post('notify_flag')),
                'sendTime' => $data['send_time'],
                'title' => $data['title'],
                'url' => (1 == $platform?'lechebang://web/p?url=':'lechebang://native/p?url=').urlencode($data['url']),
                'receiver' => [
                    'tags' => $data['tags']?explode(',', $data['tags']):[],
                    'phoneTypes' => 1 == $phoneType?['android']:(2 == $phoneType?['iOS']:['android','iOS']),
                    'mobiles' => explode(',',$data['mobile']),
                    'userIds' => $data['user_id']?explode(',',$data['user_id']):[],
                    'placeIds' => \Yii::$app->request->post('place')
                ],
            ];
            if (1 == $data['category'] || 2 ==  $data['category']) {
                unset($data['receiver']);
            }
            $soaReturn = Soa::post($url, json_encode($data))['RETURN'];
            if (200 == $soaReturn->statusCode) {
                $result['status'] = 1;
                $result['message'] = '消息推送成功';
            } else {
                $result['status'] = 0;
                $result['message'] = $soaReturn->msg;
            }
            return $this->renderJson($result);
        } else {
            return $this->render('push-message', [
                'model' => new Operation(),
                'listPlatForm' => [1 => 'WEB推送',2 => 'Native推送'],
                'listAppId' => [1 => '个推推送',5 => '友盟推送',2 => '商户版个推',6 => '商户版友盟'],
                'listCategory' => [1 => '系统公告',2 => '优惠活动',3 => '精准推送',4 => '单用户推送'],
                'listNotifyFlag' => [1 => '弹窗提醒',2 => '声音提醒',3 => '声音+弹窗'],
                'listPhoneType' => [1 => 'android',2 => 'iOS',3 => 'iOS + Android'],
                'listPlace' => ArrayHelper::map(CarCity::find()->all(),'city_id','name'),
                'listSoa' => $this->Soa(),
            ]);
        }
    }

    /**
     * 召回用户补发券
     */
    public function actionSupplyVoucher () {
        $vouchers = [];
        $fail = array();
        $message = array();
        $url = $this->url.'voucher_admin/giveVoucher';
        $request = \Yii::$app->request->post('Operation');
        if($request) {
            $mobile = $request['mobile'];
            $voucherMobile = VoucherMobile::findOne(['mobile' => $mobile,'activity_id' => 678,'give_status' => 1]);  //查询是否有召回记录
            $usUser = Customer::findOne(['mobile' => $mobile]);  //查询用户是否被默注
            if ($usUser && $voucherMobile) {
                $query = Voucher::findOne(
                    [
                        'user_id' => $usUser->id,
                        'strategy_id' => $voucherMobile->voucher_strategy_id,
                        'activity_id' => $voucherMobile->activity_id,
                    ]
                );//查询是否已经有发送召回券记录
                if(!$query)
                    array_push($vouchers,['activity_id' => $voucherMobile->activity_id,'strategy_id' => [$voucherMobile->voucher_strategy_id]]); //将优惠券放到待发券列表
                $query = Voucher::findOne(['user_id' => $usUser->id,'activity_id' => 15]);  //查询用户是否有注册券 - 待优化，需要使用find()
                if(!$query)
                    array_push($vouchers,['activity_id' => 15,'strategy_id' => [41,43,926]]);
            }
            if($vouchers) {
                foreach ($vouchers as $voucher) {
                    foreach ($voucher['strategy_id'] as $strategy) {
                        $data = [
                            'mobile' => $mobile,
                            'activityId' => $voucher['activity_id'],
                            'strategyId' => $strategy,
                            'appCode' =>  300
                        ];
                        $soaReturn = Soa::post($url,json_encode($data))['RETURN'];
                        if (200 <> $soaReturn->statusCode) {
                            array_push($fail, $strategy);
                            array_push($message, $soaReturn->msg);
                            array_unique($message);  //去除重复值
                        }
                    }
                }
                if (empty($fail)){
                    $result['status'] = 1;
                    $result['message'] = '发送成功';
                }else{
                    $result['status'] = 0;
                    $result['message'] = '策略['.implode(',',$fail).']因['.implode('|',$message).']失败';
                }
            }else {
                $result['status'] = 0;
                $result['message'] = '该用户召回券和注册券已经齐全不需要补发!';
            }
            return $this->renderJson($result);
        }else {
            return $this->render('supply-voucher',[
                'model' => new Operation(),
            ]);
        }
    }

    public function actionUnBindWechat()
    {
        $url = $this->url . 'user_admin/unBindOauthUser';
        $request = \Yii::$app->request->post('Operation');
        if ($request) {
            $usUser = Customer::findOne(['mobile' => $request['mobile']]);
            $data = [
                'userId' => $usUser->id?$usUser->id:0,
                'appCode' => 300,
                'provider' => 'wechat'
            ];
            $soaReturn = Soa::post($url, json_encode($data))['RETURN'];
            if (200 == $soaReturn->statusCode) {
                $result['status'] = 1;
                $result['message'] = '微信解绑成功';
            } else {
                $result['status'] = 0;
                $result['message'] = $soaReturn->msg;
            }
            return $this->renderJson($result);
        } else {
            return $this->render('unbind-wechat', [
                'model' => new Operation(),
            ]);
        }
    }
}