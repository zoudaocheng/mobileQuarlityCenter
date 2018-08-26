<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/11/29
 * Time: 20:58
 */

namespace app\commands;


use yii\console\Controller;

class CommController extends Controller
{
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

    public function orderType() {
        return  [
            1 => '保养订单',
            2 => '通用维修',
            3 => '事故维修',
            4 => '洗车订单',
            5 => '加油订单',
            6 => '更换电瓶',
            7 => '钣喷订单',
            8 => '养护订单'
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
            'pcdown' => 'PC下载站',
            'jinritoutiao' => '今日头条',
            'lcbTest'=> '乐车邦测试渠道',
            'tencent_gdt' => '应用宝推广',
            'weixin' => '腾讯微信',
            'chuizi' => '锤子',
            'leshi' => '乐视',
            'jiubang_anzhuo' => '3G安卓市场',
            'maopaotang' => '冒泡堂平台',
            'tianyu' => '天语应用中心',
            'haixin' => '海信应用汇',
            'tusd' => '兔商店',
            'zhuoyi' => '卓易市场',
            'kusc' => '酷市场',
            'dangbei' => '当贝市场',
            'kuaiyong' => '快用手机助手',
            'tongbu' => '同步助手',
            'souhuhuisuan' => '搜狐汇算',
            'zhihuitui' => '智慧推',
            'xinlangfuyi' => '新浪扶翼',
            'guangdiantong' => '广点通',
            'wangyiyoudao' => '网易有道',
        ];
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