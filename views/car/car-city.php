<?php
/**
 * 开通城市列表
 * @link http://mqc.lcbint.com
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/7/14
 * Time: 15:04
 */
use yii\helpers\Html;
use yii\widgets\LinkPager;
$this->title = '开通城市列表';
?>
<table id="" class="table table-striped table-bordered responsive">
    <thead>
    <tr>
        <th>城市编号</th>
        <th>城市</th>
        <th>是否开启代驾</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($provider->models as $city) { ?>
        <tr>
            <td class="center"><?= $city['city_id'] ?></td>
            <td class="center"><?= Html::encode($city['name']) ?></td>
            <td class="center"><?= $city['drive_status']?'是':'否' ?></td>
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