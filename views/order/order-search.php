<?php
/**
 * 订单查询页面
 * @link http://mqc.lcbint.com
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/7/15
 * Time: 14:14
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
$this->title = '订单中心';
?>
<div class="row">
    <div class="box col-md-12">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-edit"></i>
                    订单查询
                </h2>
            </div>
            <div class="box-create">
                <?php
                $form = ActiveForm::begin([
                    'id' => 'list-form',
                    'action' => Url::toRoute('order/order-list'),
                    'options' => ['class' => 'form-horizontal'],
                ]);
                ?>
                <div class="form-group form-group-sm">
                    <input name="page" value="1" type="hidden">
                    <label class="control-label col-md-1">选择城市</label>
                    <div class="col-md-2">
                        <?= Html::dropDownList('place_id', 0, $listPlace, [
                            'prompt' => '全部',
                            'onchange' => '
                                if( $(this).val() ){
                                $.post("index.php?r=shop/place-store&place_id='.'"+$(this).val(),function(data){
                                console.log(data);
                                    var html =  "<option value=>请选择店铺</option>";
                                    if( data ){
                                        for(var p in data){
                                            html += "<option value=\'" + data[p].id + "\'  >" + data[p].store_name + "</option>";
                                        }
                                    }
                                    $("#shop_store").html(html);
                                    $(".select-shop-store").show();
                                })
                                  }else{
                                             $(".select-shop-store").hide();
                                  }
                                ',
                            'class' => 'form-control'])
                        ?>
                    </div>
                    <div class="form-group select-shop-store" style="display:none;">
                        <label class="control-label col-md-1">选择店铺</label>
                        <div class="col-md-2">
                            <select class="form-control" name="shop_store" id="shop_store" >
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <label class="control-label col-md-1">订单状态</label>
                    <div class="col-md-2">
                        <?= Html::dropDownList('order_status', 0, $listOrderStatus, ['prompt' => '全部', 'class' => 'form-control']) ?>
                    </div>
                    <label class="control-label col-md-1">支付状态</label>
                    <div class="col-md-2">
                        <?= Html::dropDownList('payment_status', null, $listPaymentStatus, ['prompt' => '全部', 'class' => 'form-control']) ?>
                    </div>
                    <label class="control-label col-md-1">退款状态</label>
                    <div class="col-md-2">
                        <?= Html::dropDownList('refund_status', null, $listRefundStatus, ['prompt' => '全部', 'class' => 'form-control']) ?>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <label class="control-label col-md-1">下单时间</label>
                    <div class="col-md-2">
                        <input class="form-control" type="text" name="createtime_start" readonly="readonly">
                    </div>
                    <label class="control-label col-md-1">到</label>
                    <div class="col-md-2">
                        <input class="form-control" type="text" name="createtime_end" readonly="readonly">
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <div class="form-group">
                        <?= $form->field($model, 'id', ['template' => '{label}<div class="col-md-4">{input}{hint}{error}</div>', 'labelOptions' => ['class' => 'control-label col-md-1', 'style' => 'padding-left:5px;padding-right:5px']]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'order_number', ['template' => '{label}<div class="col-md-4">{input}{hint}{error}</div>', 'labelOptions' => ['class' => 'control-label col-md-1', 'style' => 'padding-left:5px;padding-right:5px']]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'mobile', ['template' => '{label}<div class="col-md-4">{input}{hint}{error}</div>', 'labelOptions' => ['class' => 'control-label col-md-1', 'style' => 'padding-left:5px;padding-right:5px']]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'sa_mobile', ['template' => '{label}<div class="col-md-4">{input}{hint}{error}</div>', 'labelOptions' => ['class' => 'control-label col-md-1', 'style' => 'padding-left:5px;padding-right:5px']]) ?>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <div class="col-md-2">
                        <a href="#" onclick="getList(1)" class="btn btn-default btn-block" ><i class="glyphicon glyphicon-search"></i><span>查询</span></a>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
                <div class="box-content" >
                </div>
            </div>
        </div>
    </div>
    <?php
    $js = <<<JS
   //获得查询结果列表
   $(function(){
        $("#order-created_time").datepicker({
            dateFormat:'yy-mm-dd'
        });
        customDatepicker($("input[name='createtime_start']"),$("input[name='createtime_end']"));
        })
   window.getList=function(page){
       page!=null?$("#list-form input[name='page']").val(page):null; 
       $.ajax({
        url: $("#list-form").attr('action'),
        data:$("#list-form").serialize(),
        beforeSend: function () {
            layer.load();
        },
        complete: function () {
            layer.closeAll('loading');
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            layer.alert('出错拉:' + textStatus + ' ' + errorThrown, {icon: 5});
        },
        success: function (data) {
             if('object' == typeof (data)) {
                 layer.alert(data.message, {icon: 5}, function (index) {
                    layer.close(index);
                });
             } else {
                 $(".box-content").html(data);
             }             
        }
    });
   }
   window.goPage=function(obj){
          var page=$(obj).data('page')+1;
          getList(page);
          return false;
   }
JS;
    $this->registerJs($js);
    ?>
