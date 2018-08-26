<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/6/27
 * Time: 16:21
 */
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\LinkPager;
?>
<table id="" class="table table-striped table-bordered responsive">
    <thead>
    <tr>
        <th>项目编号</th>
        <th>项目名称</th>
        <th>项目域名</th>
        <th>项目描述</th>
        <th>添加时间</th>
        <th>修改时间</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($provider->models as $project) { ?>
        <tr>
            <td class="center"><?= Html::encode($project['id']) ?></td>
            <td class="center"><?= Html::encode($project['project_name'])?></td>
            <td class="center"><?= Html::encode($project['domain'])?></td>
            <td class="center"><?= Html::encode(mb_substr($project['project_desc'], 0,20,'utf-8'))?>
                <?=mb_strlen($project['project_desc'])>20?'<a href="javascript:void(0);" data-placement="top" data-toggle="popover" data-content="'.Html::encode($project['project_desc']).'" title="备注"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>全部</a>':""?></td>
            <td class="center"><?= $project['created_at']==0?"":'<span class="text-info">'.date('Y-m-d H:i:s',$project['created_at']).'</span>'?></td>
            <td class="center"><?= $project['updated_at']==0?"":'<span class="text-info">'.date('Y-m-d H:i:s',$project['updated_at']).'</span>'?></td>
            <td class="center">
                <a class="btn btn-info btn-sm" href="#" data-toggle="modal" data-target="#myDialog" data-data='<?=  Json::encode($project->attributes)?>'>
                    <i class="glyphicon glyphicon-edit icon-white"></i>
                    编辑
                </a>
                <a class="btn btn-danger btn-sm" href="javascript:void(0);" onclick="del(<?= $project['id'] ?>)">
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
