<?php
/**
 * @link http://mqc.lcbint.com
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/7/10
 * Time: 15:06
 */
use yii\helpers\Html;
use yii\widgets\LinkPager;
?>
<table id="" class="table table-striped table-bordered responsive">
    <thead>
    <tr>
        <th>优惠券编号</th>
        <th>活动</th>
        <th>策略</th>
        <th>金额</th>
        <th>生效时间</th>
        <th>结束时间</th>
        <th>订单编号</th>
        <th>订单号</th>
        <th>抵用券状态</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($provider->models as $voucher) { ?>
        <tr>
            <td class="center"><?= $voucher['id'] ?></td>
            <td class="center"><?= Html::encode(mb_substr($voucher->activity['content'], 0,15,'utf-8'))?>
                <?=mb_strlen($voucher->activity['content'])>15?'<a href="javascript:void(0);" data-placement="top" data-toggle="popover" data-content="'.Html::encode($voucher->activity['content']).'" title="活动内容"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>全部</a>':""?></td>
            <td class="center"><?= Html::encode($voucher->strategy['content']) ?></td>
            <td class="center"><?= intval($voucher['amount']/100).' 元';?></td>
            <td class="center"><?= $voucher['start_time'] ?></td>
            <td class="center"><?= $voucher['end_time'] ?></td>
            <td class="center"><?= $voucher->order?$voucher->order['id']:'无' ?></td>
            <td class="center"><?= $voucher->order?$voucher->order['order_number']:'无' ?></td>
            <td class="center"><?php
                if(time() > strtotime($voucher['end_time']))
                {
                    echo '已失效';
                }else{
                    if(1 == $voucher['voucher_status']){
                        echo '未绑定';
                    }elseif(2 == $voucher['voucher_status']){
                        echo '已绑定';
                    }elseif(3 == $voucher['voucher_status']){
                        echo '已使用';
                    }elseif(4 == $voucher['voucher_status']){
                        echo '已过期';
                    }
                } ?>
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