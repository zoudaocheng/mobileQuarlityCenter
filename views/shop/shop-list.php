<?php
/**
 * 店铺基本信息页面
 * @link http://mqc.lcbint.com
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/7/14
 * Time: 16:52
 */
use yii\helpers\Html;
use yii\widgets\LinkPager;
?>
<table id="" class="table table-striped table-bordered responsive">
    <thead>
    <tr>
        <th>编号</th>
        <th>店铺名称</th>
        <th>店铺短名称</th>
        <th>所在城市</th>
        <th>结算城市</th>
        <th>账户名称</th>
        <th>账户状态</th>
        <th>结算银行</th>
        <th>结算类型</th>
        <th>结算账期</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($provider->models as $shop) { ?>
        <tr>
            <td class="center"><?= $shop['id'] ?></td>
            <td class="center"><?= Html::encode($shop['store_name']) ?></td>
            <td class="center"><?= Html::encode($shop['store_nick_name']) ?></td>
            <td class="center"><?= Html::encode($shop->place['name']) ?></td>
            <td class="center"><?= Html::encode($shop->settlementAccount->place['name']) ?></td>
            <td class="center"><?= Html::encode($shop->settlementAccount['seller_name']) ?></td>
            <td class="center"><?= Html::encode($shop->settlementAccount['account_status']==1?'正常结算':'暂停结算') ?></td>
            <td class="center"><?= Html::encode($shop->settlementAccount['bank_name'].' - '.$shop->settlementAccount['branch_bank_name']) ?></td>
            <td class="center"><?= Html::encode($shop->settlementAccount['seller_type'] == 1?'集团结算':'店铺结算');?></td>
            <td class="center"><?= Html::encode('T + '.$shop->settlementAccount['settlement_days']) ?></td>
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