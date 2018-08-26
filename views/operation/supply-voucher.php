<?php
/**
 * 补发优惠券（召回券）
 * @link http://mqc.lechebang.com
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/7/26
 * Time: 19:02
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
$this->title = '发券';
?>
    <div class="row">
    <div class="box col-md-12">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-edit"></i>召回券补发</h2>
            </div>
            <div class="box-create">
                <?php
                $form = ActiveForm::begin([
                    'id' => 'operation-form',
                    'action' => Url::toRoute('operation/supply-voucher'),
                    'options' => ['class' => 'form-horizontal'],
                ]);
                ?>
                <div class="form-group form-group-sm">
                    <div class="form-group">
                        <?= $form->field($model, 'mobile', ['template' => '{label}<div class="col-md-4">{input}{hint}{error}</div>', 'labelOptions' => ['class' => 'control-label col-md-1', 'style' => 'padding-left:5px;padding-right:5px']]) ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
<?php
$js = <<<JS
    $(function(){
        $("#operation-form").on('beforeSubmit',function(e){
            ajaxSubmitForm($(this),function(data){
                if(data.status==1){
                    $('#myDialog').modal('hide');
                }
            });
            return false;
        });
   });
JS;
$this->registerJs($js);
?>