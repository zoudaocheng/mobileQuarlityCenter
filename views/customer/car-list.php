<?php
/**
 * @link http://mqc.lcbint.com
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/7/8
 * Time: 14:36
 */
use yii\helpers\Html;
use yii\widgets\LinkPager;
?>
<table id="" class="table table-striped table-bordered responsive">
    <thead>
    <tr>
        <th>我的车编号</th>
        <th>车型</th>
        <th>车牌号</th>
        <th>行驶里程</th>
        <th>是否默认车</th>
        <th>删除状态</th>
        <th>添加时间</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($provider->models as $myCar) { $car = explode(',',$myCar->car['path'])?>
        <tr>
            <td class="center"><?= $myCar['id'] ?></td>
            <td class="center"><?= Html::encode(count($car)>1?\app\models\CarBrandType::findOne(['id' => $car[0]])['name'].' '
                    .\app\models\CarBrandType::findOne(['id' => $car[1]])['name'].' '
                    .\app\models\CarBrandType::findOne(['id' => $car[2]])['name'].' '
                    .\app\models\CarBrandType::findOne(['id' => $car[3]])['name'].' '
                    .\app\models\CarBrandType::findOne(['id' => $car[4]])['name']:'无关联车')
                ?>
            </td>
            <td class="center"><?= $myCar['car_number']?Html::encode($myCar['car_number']):'无' ?></td>
            <td class="center"><?= $myCar['mileage']?Html::encode($myCar['mileage']):0 ?></td>
            <td class="center"><?= $myCar['is_default']?'是':'否'?></td>
            <td class="center"><?= $myCar['deleted']?'已删除':'未删除'?></td>
            <td class="center"><?= date('Y-m-d H:i:s',$myCar['created_time']/1000)?></td>
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