<?php
/**
 * @link http://mqc.lcbint.com
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/7/7
 * Time: 15:42
 */
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
$this->title = '用户中心';
?>
    <div class="row">
    <div class="box col-md-12">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-edit"></i>
                    <?php
                    if(Yii::$app->controller->action->id === 'order-list'){ echo '订单信息';}
                    elseif(Yii::$app->controller->action->id === 'car-list'){ echo '车辆信息';}
                    elseif(Yii::$app->controller->action->id === 'voucher-list'){ echo '优惠券列表';}
                    elseif(Yii::$app->controller->action->id === 'wallet-account'){echo '钱包信息';}
                    else {echo '用户信息';}
                    ?>
                </h2>
            </div>
            <div class="box-create">
                <?php
                $form = ActiveForm::begin([
                    'id' => 'list-form',
                    'action' => Url::toRoute(Yii::$app->controller->action->id == 'order-list'?'order/order-list':(Yii::$app->controller->action->id == 'car-list'?'customer/car-list':(Yii::$app->controller->action->id == 'voucher-list'?'voucher/voucher-list':(Yii::$app->controller->action->id == 'wallet-account'?'wallet/wallet-account':'customer/base-info')))),
                    'options' => ['class' => 'form-horizontal'],
                ]);
                ?>
                <div class="form-group form-group-sm">
                    <input name="page" value="1" type="hidden">
                    <div class="form-group">
                        <?= $form->field($model, 'mobile', ['template' => '{label}<div class="col-md-4">{input}{hint}{error}</div>', 'labelOptions' => ['class' => 'control-label col-md-1', 'style' => 'padding-left:5px;padding-right:5px']]) ?>
                    </div>
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