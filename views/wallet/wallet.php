<?php
/**
 * Created by PhpStorm.
 * User: zoudaocheng
 * Date: 17/10/30
 * Time: 15:37
 */
use yii\widgets\LinkPager;
?>
<div class="row">
    <div class="box col-md-4">
        <div class="box-inner">
            <?php if(isset($account) && $account) {?>
                <div class="box-content">
                    <p><b>总金额：</b><?= $account->total_amount/100 . ' 元' ?></p>
                    <p><b>可用金额：</b><?= $account->available_amount/100 .' 元' ?></p>
                    <p><b>冻结金额：</b><?= $account->frozen_amount/100 .' 元' ?></p>
                </div>
            <?php }?>
        </div>
    </div>
</div>
<table id="" class="table table-striped table-bordered responsive">
    <thead>
    <tr>
        <th>流水ID</th>
        <th>时间</th>
        <th>金额</th>
        <th>变更方法</th>
        <th>订单ID</th>
        <th>订单号</th>
        <th>车牌号/手机号</th>
        <th>来源/去处</th>
        <th>操作系统</th>
        <th>变动说明</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($provider->models as $order) { ?>
        <tr>
            <td class="center"><?= $order['id'] ?></td>
            <td class="center"><?= date('Y-m-d H:i:s',$order['create_time']/1000);?></td>
            <td class="center"><?= (1 == $order['method']?'+ ':(2 == $order['method']?'- ':'')).$order['amount']/100 .' 元 ';?></td>
            <td class="center"><?php
                if (1 == $order['method']){
                    echo '增加金额';
                }elseif (2 == $order['method']){
                    echo '减少金额';
                }elseif (3 == $order['method']){
                    echo '冻结金额';
                }elseif (4 == $order['method']){
                    echo '解冻金额';
                }elseif (5 == $order['method']){
                    echo '提现成功';
                }else{
                    echo '未知方法';
                }
                ?>
            </td>
            <td class="center"><?= $order['order_id'] ?></td>
            <td class="center"><?= $order->order['order_number']; ?></td>
            <td class="center"><?php
                if ($order['order_id']){
                    echo $order->order->maintenanceItem['car_number'];
                }
                ?>
            </td>
            <td class="center"><?php
                if (1 == $order['related_type']){
                    echo 'SA抢单奖励';
                }elseif (2 == $order['related_type']){
                    echo 'Activity';
                }elseif (3 == $order['related_type']){
                    echo '钱包充值';
                }elseif (4 == $order['related_type']){
                    echo '提现';
                }elseif (5 == $order['related_type']){
                    echo '消费捐款';
                }elseif (6 == $order['related_type']){
                    echo '扣缴税';
                }elseif (7 == $order['related_type']){
                    echo '邀请有礼';
                }else{
                    echo '未知';
                }
                ?>
            </td>
            <td class="center"><?= $order->application['name']; ?></td>
            <td class="center"><?= $order['remark'] ?></td>
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