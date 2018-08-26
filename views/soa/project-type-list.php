<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/12/13
 * Time: 18:14
 * 项目类型列表
 */
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\LinkPager;
?>
<table id="" class="table table-striped table-bordered responsive">
    <thead>
    <tr>
        <th>编号</th>
        <th>类型</th>
        <th>类型描述</th>
        <th>添加时间</th>
        <th>更新时间</th>
        <th>添加人员</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($provider->models as $type) { ?>
        <tr>
            <td class="center"><?= $type['id'] ?></td>
            <td class="center"><?= Html::encode($type['name']) ?></td>
            <td class="center"><?= Html::encode(mb_substr($type['description'], 0,40,'utf-8'))?>
                <?=mb_strlen($type['description'])>40?'<a href="javascript:void(0);" data-placement="top" data-toggle="popover" data-content="'.Html::encode($type['description']).'" title="概要"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>全部</a>':""?></td>
            <td class="center"><?= date('Y-m-d H:i:s',$type['created_at']) ?></td>
            <td class="center"><?= date('Y-m-d H:i:s',$type['updated_at']) ?></td>
            <td class="center"><?= Html::encode($type->user->username) ?></td>
            <td class="center">
                    <a class="btn btn-info btn-sm" href="#" data-toggle="modal" data-target="#myDialog" data-data='<?=  Json::encode($type->attributes)?>'>
                        <i class="glyphicon glyphicon-edit icon-white"></i>
                        编辑
                    </a>
                    <a class="btn btn-danger btn-sm" href="javascript:void(0);" onclick="del(<?= $type['id'] ?>)">
                        <i class="glyphicon glyphicon-fire icon-white"></i>
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
