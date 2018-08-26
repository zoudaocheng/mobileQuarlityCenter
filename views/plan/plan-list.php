<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2017/4/25
 * Time: 15:38
 */
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\LinkPager;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
?>
<table id="" class="table table-striped table-bordered responsive">
    <thead>
    <tr>
        <th>编号</th>
        <th>项目名称</th>
        <th>版本号</th>
        <th>版本描述</th>
        <th>项目计划</th>
        <th>预计提测</th>
        <th>实际提测</th>
        <th>预计发布</th>
        <th>实际发布</th>
        <th>QA负责人</th>
        <th>开发人员</th>
        <th>发布状态</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($provider->models as $publish) { ?>
        <tr>
            <td class="center"><?= $publish['id'] ?></td>
            <td class="center"><?= Html::encode($publish->projectType['name'].' - '.$publish->project['name']) ?></td>
            <td class="center"><a href="<?=\app\components\Jira::versionToUri($publish['version_uri'])?>" target="_blank" ><?= Html::encode($publish['version']) ?></a></td>
            <td class="center"><?= Html::encode($publish['version_description']) ?></td>
            <td class="center"><?= 1==$publish['plan_type']?'计划内':'计划外提测' ?></td>
            <td class="center"><?= $publish['pre_lift_time']?></td>
            <td class="center"><?= $publish['lift_time']?date('Y-m-d H:i:s',$publish['lift_time']):'未提测' ?></td>
            <td class="center"><?= $publish['pre_publish_time']?$publish['pre_publish_time']:(1==$publish['plan_type']?'待排期':'计划外提测') ?></td>
            <td class="center"><?= $publish['publish_time']?date('Y-m-d H:i:s',$publish['publish_time']):'待发布' ?></td>
            <td class="center"><?= $publish->tester['realname'] ?></td>
            <td class="center"><?= $publish->lifter['realname'] ?></td>
            <td class="center"><?= $publish['publish_status']?'已发布':'未发布' ?></td>
            <td class="center">
                <?php if(!$publish['publish_status']) { ?>
                    <a class="btn btn-info btn-sm" href="#" data-toggle="modal" data-target="#myDialog" data-data='<?=  Json::encode($publish->attributes)?>'>
                        <i class="glyphicon glyphicon-edit icon-white"></i>
                        点击编辑
                    </a>
                    <br>
                <?php }?>
                <a class="btn btn-danger btn-sm ppl-see-test" data-url='<?= $publish['id'] ?>'>
                    <i class="glyphicon glyphicon-fire icon-white">查看提测记录</i>
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
        $('.ppl-see-test').click(function (argument) {
            $.ajax({
                type: "POST",
                url: "http://mqc.lechebang.com/index.php?r=assign/lift-detail&plan_id=" + $(this).attr("data-url"),
                success: function(html){
                    $("body").append(html)
                }
            });
        })
    })
</script>