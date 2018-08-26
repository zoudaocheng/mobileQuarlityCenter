<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/8/2
 * Time: 11:38
 */
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\LinkPager;
?>
<table id="" class="table table-striped table-bordered responsive">
    <thead>
    <tr>
        <th>编号</th>
        <th>发布项目</th>
        <th>发布概要</th>
        <th>发布明细</th>
        <th>版本号</th>
        <th>提测时间</th>
        <th>添加时间</th>
        <th>提测人员</th>
        <th>发布生产</th>
        <th>邮件状态</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($provider->models as $publish) { ?>
        <tr>
            <td class="center"><?= $publish['id'] ?></td>
            <td class="center"><?= Html::encode($publish->projectType['name'].' - '.$publish->project['name']) ?></td>
            <td class="center"><?= Html::encode(mb_substr($publish['headline'], 0,20,'utf-8'))?>
                <?=mb_strlen($publish['headline'])>20?'<a href="javascript:void(0);" data-placement="top" data-toggle="popover" data-content="'.Html::encode($publish['headline']).'" title="发布概要"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>全部</a>':""?></td>
            <td class="center"><?= Html::encode(mb_substr($publish['content'], 0,20,'utf-8'))?>
                <?=mb_strlen($publish['content'])>20?'<a href="javascript:void(0);" data-placement="top" data-toggle="popover" data-content="'.Html::encode($publish['content']).'" title="发布明细"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>全部</a>':""?></td>
            <td class="center"><?= Html::encode($publish['version']) ?></td>
            <td class="center"><?= Html::encode($publish['lift_time']) ?></td>
            <td class="center"><?= date('Y-m-d H:i:s',$publish['created_at']) ?></td>
            <td class="center"><?= Html::encode($publish['lifter']) ?></td>
            <td class="center"><?= $publish['publish_status']?'已发布':'未发布' ?></td>
            <td class="center"><?= $publish['status']?'已发送':'未发送' ?></td>
            <td class="center">
                <?php if(!$publish['status']) { ?>
                    <a class="btn btn-info btn-sm" href="#" data-toggle="modal" data-target="#myDialog" data-data='<?=  Json::encode($publish->attributes)?>'>
                        <i class="glyphicon glyphicon-edit icon-white"></i>
                        点击编辑
                    </a>
                    <a class="btn btn-danger btn-sm" href="javascript:void(0);" onclick="del(<?= $publish['id'] ?>)">
                        <i class="glyphicon glyphicon-fire icon-white"></i>
                        邮件通知
                    </a>
                <?php }else{ echo '项目已上线';}?>
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