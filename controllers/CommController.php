<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/4/28
 * Time: 19:18
 */

namespace app\controllers;

use app\models\Email;
use Yii;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;

class CommController extends Controller
{
    public function beforeAction($action)
    {
        $auth = Yii::$app->authManager;
        $isAjax = Yii::$app->request->getIsAjax();

        //未登录
        if(Yii::$app->user->isGuest){
            if($isAjax){
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = array(
                    'status' => -1,
                    'message' => '请先登录',
                    'url' => Yii::$app->getHomeUrl(),
                );
                return false;
            }else{
                return $this->goHome();
            }
        }

        //超级管理员
        if(Yii::$app->user->identity->username === Yii::$app->params['SuperAdmin']){
            return true;
        }
        //controller id 和 action id 组成节点，判断有否有权操作
        $action = Yii::$app->controller->id.'/'.Yii::$app->controller->action->id;
        $action = strtolower($action); //变成小写
        if(!$auth->getPermission($action)){
            return true;  //该页面没有纳入权限管理
        }
        if(!Yii::$app->user->can($action)){
            if($isAjax){
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = array(
                    'status' => -1,
                    'message' => '对不起，你无权进行此项操作',
                );
                return false;
            }else{
                throw new HttpException(403,'对不起，您现在还没获此操作的权限');
            }
        }else{
            return parent::beforeAction($action);
        }
    }

    public function renderJson($params = array())
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $params;
    }

    public function orderStatus() {
        return [
            1 => '新建',
            2 => '已支付',
            3 => '处理中',
            4 => '已完成',
            5 => '已取消'
        ];
    }

    public function paymentStatus() {
        return [
            0 => '未支付',
            1 => '正在支付',
            2 => '支付完成',
            3 => '支付失败',
        ];
    }

    public function refundStatus() {
        return [
            0 => '无退款',
            1 => '退款中',
            2 => '退款完成',
            3  => '退款失败'
        ];
    }

    public function settlementStatus() {
        return [
            1 => '未提交结算',
            2 => '已提交结算',
            3 => '已完成结算',
            4 => '未导入结算表',
            5 => '已导入结算表'
        ];
    }

    public function accountStatus () {
        return [
            1 => '正常结算',
            2 => '暂停结算',
        ];
    }

    public function Soa() {
        return [
            'pro' => '生产环境',
            'pre' => '堡垒环境',
            'soa1' => '测试SOA1',
            'soa2' => '测试SOA2',
            'soa3' => '测试SOA3',
            'soa4' => '测试SOA4',
            'soa5' => '测试SOA5',
        ];
    }

    public function Dates($start,$end) {
        $dates = array();
        $dt_start = strtotime($start);
        $dt_end = strtotime($end);
        do {
            array_push($dates,date('Y-m-d', $dt_start));
        } while (($dt_start += 86400) <= $dt_end);

        return $dates;
    }

    public function paymentPlatform() {
        return [
            '4' => '支付宝WAP',
            '5' => '支付宝APP',
            '6' => '微信H5',
            '7' => '微信APP',
            '8' => '银联WAP',
            '9' => '银联APP',
            '1600' => '百度地图',
            '1601' => '腾讯汽车',
            '1604' => '小浦周边通',
            '1605' => '广发银行APP',
            '1606' => '车轮',
            '1607' => '58到家',
            '1610' => '汽车之家APP',
            '1700' => '天猫',
            '1702' => '手淘H5'
        ];
    }

    public function appChannel() {
        return [
            'appStore' => 'App Store',
            'baidu' => '百度手机助手',
            'huawei' => '华为应用市场',
            'qihu' => '360手助',
            'tencent' => '腾讯应用宝',
            'lechebang' => '乐车邦官网',
            'wandoujia' => '豌豆夹',
            'xiaomi' => '小米',
            'bd_91zs' => '91助手',
            'bd_sousuo' => '百度搜索',
            'bd_anzhuo' => '安卓市场',
            'anzhi' => '安智市场',
            'meizu' => '魅族',
            'sanxing' => '三星',
            'sogou' => '搜狗应用市场',
            'jifeng' => '机锋市场',
            'mumayi' => '木蚂蚁',
            'jiubang' => '3G安卓市场',
            'oppo' => 'oppo软件商店',
            'dx_tianyi' => '天翼空间',
            'yd_yidong' => 'MM商店',
            'lt_wostore' => '联通沃商店',
            'jinli' => '金立软件商店',
            'liqu' => '历趣应用市场',
            'PP' => 'PP助手',
            'lianxiang' => '联想乐商店',
            'appchina' => '应用汇',
            'anruan' => '安软市场',
            'aiqiyi' => '爱奇艺',
            'vivo' => 'vivo应用商店',
            'youyi' => '优亿市场',
            'nduowang' => 'N多网',
            'pcdown' => 'PC下载站'
        ];
    }

    /**
     * 邮件发送
     * @param array $data = array();
     */
    public function Email($data = array()) {
        $flag = false;
        $message = \Yii::$app->mailer;
        $email = new Email();
        $email->subject = $data['subject'];
        $email->receiver = $data['receiver'];
        $email->content = json_encode($data['content']);
        $email->compose = $data['compose'];
        $email->created_time = time();
        $email->updated_time = time();
        $message = $message->compose($email->compose,['content' => $data['content']]);
        if(isset($data['attachment'])){
            $email->attachment = $data['attachment'];
            $message->attach($email->attachment);
        }
        if(isset($data['cc'])){
            $email->cc = $data['cc'];
            $message->setCc($email->cc);
        }
        if('test-report' == $data['compose']){
            $email->type = 2;
        }
        $message->setTo($email->receiver);
        $message->setSubject($email->subject);
        do{
            if($message->send())
                $flag = true;
            $email->status = 1; //发送成功
        }while(!$flag);
        //$email->save();//邮件数据存储
    }

    /**
     * 钱包流水明细方法
     * @return array
     */
    public function walletMethod(){
        return [
            '1' => '增加金额',
            '2' => '减少金额',
            '3' => '冻结金额',
            '4' => '解冻金额'
        ];
    }

    /**
     * 钱包流水来源
     * @return array
     */
    public function walletRelated(){
        return [
            '1' => 'SA抢单',
            '2' => 'Activity',
            '3' => '充值',
            '4' => '取现',
            '5' => '消费',
            '6' => '扣税',
            '7' => '邀请有礼'
        ];
    }
}