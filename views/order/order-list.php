<?php
/**
 * 订单列表页面
 * @link http://mqc.lcbint.com
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/7/15
 * Time: 14:50
 */
use yii\helpers\Html;
use yii\widgets\LinkPager;
?>
<table id="" class="table table-striped table-bordered responsive">
    <thead>
    <tr>
        <th>订单ID</th>
        <th>订单号</th>
        <th>订单手机</th>
        <th>渠道</th>
        <th>城市</th>
        <th>店铺名称</th>
        <th>支付策略</th>
        <th>乐享价</th>
        <th>支付金额</th>
        <th>订单状态</th>
        <th>支付状态</th>
        <th>退款状态</th>
        <th>创建时间</th>
        <th>SA</th>
        <th>SA手机</th>
        <th>SA来源</th>
        <th>券号</th>
        <th>券状态</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($provider->models as $order) { ?>
        <tr>
            <td class="center"><?= $order['id'] ?></td>
            <td class="center"><?= $order['order_number'];?></td>
            <td class="center"><?= $order->maintenanceItem['contact_user_mobile'];?></td>
            <td class="center"><?= Html::encode($order->application['name']) ?></td>
            <td class="center"><?= Html::encode($order->place['name']) ?></td>
            <td class="center"><?= Html::encode($order->store['store_name']) ?></td>
            <td class="center"><?= 1==$order['payment_policy']?'直接支付':2==$order['payment_policy']?'优惠券支付':'内测' ?></td>
            <td class="center"><?= intval($order['sale_amount']/100).' 元' ?></td>
            <td class="center"><?= intval($order['pay_amount']/100).' 元' ?></td>
            <td class="center"><?php
                if(1 == $order['order_status']){
                    echo '新增';
                }elseif(2 == $order['order_status']){
                    echo '已支付';
                }elseif(3 == $order['order_status']){
                    echo '处理中';
                }elseif(4 == $order['order_status']){
                    echo '已完成';
                }elseif(5 == $order['order_status']){
                    echo '已取消';
                } ?>
            </td>
            <td class="center"><?php
                if(1 == $order['payment_status']){
                    echo '正在支付';
                }elseif(2 == $order['payment_status']){
                    echo '支付完成';
                }elseif(3 == $order['payment_status']){
                    echo '支付失败';
                }elseif(0 == $order['payment_status']){
                    echo '未支付';
                }?>
            </td>
            <td class="center"><?php
                if(1 == $order['refund_status']){
                    echo '退款中';
                }elseif(2 == $order['refund_status']){
                    echo '退款完成';
                }elseif(3 == $order['refund_status']){
                    echo '退款失败';
                }elseif(0 == $order['refund_status']) {
                    echo '无退款';
                }?>
            </td>
            <td class="center"><?= date('Y-m-d H:i:s',$order['created_time']/1000)?></td>
            <td class="center"><?= $order->sa['sa_name']?$order->sa['sa_name']:'无'?></td>
            <td class="center"><?= $order->sa['sa_mobile']?$order->sa['sa_mobile']:'无'?></td>
            <td class="center"><?php
                if(1 == $order->sa['source'])
                {
                    echo 'SA抢单';
                }elseif(2 == $order->sa['source'])
                {
                    echo '自动分配';
                }else{
                    echo '抢单关闭';
                }
                ?>
            </td>
            <td class="center"><?= $order->orderCoupon['coupon_number']?$order->orderCoupon['coupon_number']:'无'?></td>
            <td class="center"><?php
                if(1 == $order->orderCoupon['used_status']){
                    echo '未使用';
                }elseif(2 == $order->orderCoupon['used_status']){
                    echo '已确认';
                }elseif(3 == $order->orderCoupon['used_status']){
                    echo '已使用';
                }elseif(4 == $order->orderCoupon['used_status']){
                    echo '已取消';
                } elseif(0 == $order->orderCoupon['used_status']){
                    echo '未激活';
                }
                ?>
            </td>
        </tr>
    <?php } ?>
    </tbody>
    <tfoot>
    <tr>
        <td colspan="10">
            <button class="btn btn-default pull-left" style="display: inline-block" disabled="disabled">(当前<?= $provider->count ?>条/共<?= $provider->totalCount ?>条)</button>
            <?=
            LinkPager::widget([
                'pagination' => $provider->pagination,
                'linkOptions' => ['onclick' => 'return goPage(this)'],
                'options' => ['class' => 'pagination pull-left', 'style' => 'margin:0px']
            ]);
            ?>
        </td>
    </tr>
    </tfoot>
</table>
<script type="text/javascript">
    $(function(){
        $('[data-toggle="popover"]').popover();
    })
</script>