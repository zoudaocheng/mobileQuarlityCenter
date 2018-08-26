<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2017/4/25
 * Time: 18:11
 */
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\LinkPager;
?>
<style type="text/css">
.ppl-black-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
}
.ppl-title {
    padding: 10px 0;
}
.ppl-popdata {
    background: #fff;
    border-radius: 6px;
    position: absolute;
    top: 30px;
    left: 50%;
    -webkit-transform: translateX(-50%);
    transform: translateX(-50%);
    min-width: 80%;
    padding: 10px 20px;
    overflow:auto;
}
.ppl-close {
    text-align: right;
}
.pll-table th, .pll-table td{
    text-align: center;
}
.pll-table td.pll-td {
    text-align: left;
}
.pll-table>thead>tr>td, .pll-table>tbody>tr>td{
    vertical-align: middle;
}
</style>
<div class="ppl-black-overlay">
    <div class="ppl-popdata">
        <div class="ppl-title">
            <button type="button" class="close ppl-close"><span>×</span></button>
            <h4>测试记录</h4>
        </div>
        <table id="" class="table table-striped table-bordered responsive pll-table">
            <thead>
            <tr>
                <th>编译号</th>
                <th>测试环境</th>
                <th>级别</th>
                <th>自测</th>
                <th>单元测试</th>
                <th width="40%">ISSUE</th>
                <th>提测时间(阿里)</th>
                <th>完成时间(阿里)</th>
                <th>测试结果(阿里)</th>
                <th>发布时间(堡垒)</th>
                <th>完成时间(堡垒)</th>
                <th>测试结果(堡垒)</th>
                <th>发布时间(生产)</th>
                <th>完成时间(生产)</th>
                <th>测试结果(生产)</th>
                <th>合并代码</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($provider->models as $publish) { ?>
                <tr>
                    <td class="center"><?= $publish['build_no'] ?></td>
                    <td class="center"><?= $publish['environment'] ?></td>
                    <td class="center"><?= $publish['level'] ?></td>
                    <td class="center"><?= 1==$publish['st_flag']?'已自测':'未自测' ?></td>
                    <td class="center"><?= 1==$publish['unit_flag']?'测试通过':'测试不通过' ?></td>
                    <td class="pll-td"><?php foreach (json_decode($publish['issues']) as $issue){ if (isset($issue->type)) {echo "<a href='".$issue->uri."' target='_blank'>".$issue->key."</a> => ".$issue->summary."<br>";}} ?></td>
                    <td class="center"><?= $publish['created_time']?date('Y-m-d H:i:s',$publish['created_time']):'-' ?></td>
                    <td class="center"><?= $publish['lcbint_time']?date('Y-m-d H:i:s',$publish['lcbint_time']):'-' ?></td>
                    <td class="center"><?= (null !== $publish['lcbint_result'])?(1 == $publish['lcbint_result']?'通过':'失败'):'测试中' ?></td>
                    <td class="center"><?= $publish['lift_mtest_time']?date('Y-m-d H:i:s',$publish['lift_mtest_time']):'-' ?></td>
                    <td class="center"><?= $publish['mtest_time']?date('Y-m-d H:i:s',$publish['mtest_time']):'-' ?></td>
                    <td class="center"><?= (null !== $publish['mtest_result'])?(1== $publish['mtest_result']?'通过':'失败'):'-' ?></td>
                    <td class="center"><?= $publish['lift_pro_time']?date('Y-m-d H:i:s',$publish['lift_pro_time']):'-' ?></td>
                    <td class="center"><?= $publish['pro_time']?date('Y-m-d H:i:s',$publish['pro_time']):'-' ?></td>
                    <td class="center"><?= (null !== $publish['pro_result'])?(1== $publish['pro_result']?'通过':'失败'):'-' ?></td>
                    <td class="center"><?= $publish['merge_time']?date('Y-m-d H:i:s',$publish['merge_time']):'-' ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    $(function(){
        $('[data-toggle="popover"]').popover();
        $('.ppl-close').click(function () {
            $('.ppl-black-overlay').hide()
        })
    })
</script>
