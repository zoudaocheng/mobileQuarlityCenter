<?php
/**
 * @link http://mqc.lcbint.com
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/7/11
 * Time: 18:24
 */
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\LinkPager;
?>
<table id="" class="table table-striped table-bordered responsive">
    <thead>
    <tr>
        <th>手机编号</th>
        <th>手机品牌</th>
        <th>手机型号</th>
        <th>分辨率</th>
        <th>IP地址</th>
        <th>类别</th>
        <th>固件版本</th>
        <th>IMEI号</th>
        <th>序列号</th>
        <th>手机在哪</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($provider->models as $mobile) { ?>
        <tr>
            <td class="center"><?= $mobile['id'] ?></td>
            <td class="center"><?= Html::encode($mobile->brands['brand']) ?></td>
            <td class="center"><?= Html::encode($mobile['type']) ?></td>
            <td class="center"><?= Html::encode($mobile['pixels']) ?></td>
            <td class="center"><?= Html::encode($mobile['ip']) ?></td>
            <td class="center"><?= Html::encode($mobile['platform']==1?'安卓':($mobile['platform']==2?'iOS':'Pad')) ?></td>
            <td class="center"><?= Html::encode($mobile['version']) ?></td>
            <td class="center"><?= Html::encode($mobile['imei']) ?></td>
            <td class="center"><?= Html::encode($mobile['serial']) ?></td>
            <td class="center"><?= Html::encode($mobile['owner']) ?></td>
            <td class="center">
                <a class="btn btn-info btn-sm" href="#" data-toggle="modal" data-target="#myDialog" data-data='<?=  Json::encode($mobile->attributes)?>'>
                    <i class="glyphicon glyphicon-edit icon-white"></i>
                    编辑
                </a>
                <a class="btn btn-danger btn-sm" href="javascript:void(0);" onclick="del(<?= $mobile['id'] ?>)">
                    <i class="glyphicon glyphicon-trash icon-white"></i>
                    删除
                </a>
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