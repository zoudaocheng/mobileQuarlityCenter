<?php
/**
 * @link http://mqc.lcbint.com
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/6/28
 * Time: 18:23
 */

use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\LinkPager;
?>
<table id="" class="table table-striped table-bordered responsive">
    <thead>
    <tr>
        <th>编号</th>
        <th>项目名称</th>
        <th>SOA名称</th>
        <th>SOA-URL</th>
        <th>Mock</th>
        <th>请求参数</th>
        <th>参数类型</th>
        <th>响应Mock</th>
        <th>响应字段</th>
        <th>字段类型</th>
        <th>用户</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($provider->models as $soa) { ?>
        <tr>
            <td class="center"><?= $soa['id'] ?></td>
            <td class="center"><?= Html::encode($soa->project['project_name']) ?></td>
            <td class="center"><?= Html::encode($soa['soa_name']) ?></td>
            <td class="center"><?= Html::encode(mb_substr($soa['url'], 0,20,'utf-8'))?>
                <?=mb_strlen($soa['url'])>20?'<a href="javascript:void(0);" data-placement="top" data-toggle="popover" data-content="'.Html::encode($soa['url']).'" title="模拟请求参数"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>全部</a>':""?></td>
            <td class="center"><?= Html::encode(mb_substr($soa['mock'], 0,20,'utf-8'))?>
                <?=mb_strlen($soa['mock'])>20?'<a href="javascript:void(0);" data-placement="top" data-toggle="popover" data-content="'.Html::encode($soa['mock']).'" title="模拟请求参数"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>全部</a>':""?></td>
            <td class="center"><?= Html::encode(mb_substr($soa['request_param'], 0,10,'utf-8'))?>
                <?=mb_strlen($soa['request_param'])>10?'<a href="javascript:void(0);" data-placement="top" data-toggle="popover" data-content="'.Html::encode($soa['request_param']).'" title="请求参数"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>全部</a>':""?></td>
            <td class="center"><?= Html::encode(mb_substr($soa['request_field'], 0,10,'utf-8'))?>
                <?=mb_strlen($soa['request_field'])>10?'<a href="javascript:void(0);" data-placement="top" data-toggle="popover" data-content="'.Html::encode($soa['request_field']).'" title="请求参数及参数类型"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>全部</a>':""?></td>
            <td class="center"><?= Html::encode(mb_substr($soa['response'], 0,10,'utf-8'))?>
                <?=mb_strlen($soa['response'])>10?'<a href="javascript:void(0);" data-placement="top" data-toggle="popover" data-content="'.Html::encode($soa['response']).'" title="mock响应"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>全部</a>':""?></td>
            <td class="center"><?= Html::encode(mb_substr($soa['response_param'], 0,10,'utf-8'))?>
                <?=mb_strlen($soa['response_param'])>10?'<a href="javascript:void(0);" data-placement="top" data-toggle="popover" data-content="'.Html::encode($soa['response_param']).'" title="响应字段"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>全部</a>':""?></td>
            <td class="center"><?= Html::encode(mb_substr($soa['response_field'], 0,10,'utf-8'))?>
                <?=mb_strlen($soa['response_field'])>10?'<a href="javascript:void(0);" data-placement="top" data-toggle="popover" data-content="'.Html::encode($soa['response_field']).'" title="响应字段及类型"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>全部</a>':""?></td>
            <td class="center"><?= Html::encode($soa->user->username) ?></td>
            <td class="center">
                <!--<a class="btn btn-info btn-sm" href="#" data-toggle="modal" data-target="#myDialog" data-data='<?=  $soa['request_param'] ?>'>
                    <i class="glyphicon glyphicon-edit icon-white"></i>
                    测试
                </a>-->
                <a class="btn btn-info btn-sm" href="#" data-toggle="modal" data-target="#myDialog" data-data='<?=  Json::encode($soa->attributes)?>'>
                    <i class="glyphicon glyphicon-edit icon-white"></i>
                    编辑
                </a>
                <a class="btn btn-danger btn-sm" href="javascript:void(0);" onclick="del(<?= $soa['id'] ?>)">
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
