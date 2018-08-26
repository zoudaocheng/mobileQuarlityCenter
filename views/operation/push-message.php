<?php
/**
 * 消息推送页面
 * @link http://mqc.lcbint.com
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/7/24
 * Time: 13:39
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
$this->title = '消息推送';
?>
    <div class="row">
    <div class="box col-md-12">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-edit"></i>消息推送</h2>
            </div>
            <div class="box-create">
                <?php
                $form = ActiveForm::begin([
                    'id' => 'operation-form',
                    'action' => Url::toRoute('operation/push-message'),
                    'options' => ['class' => 'form-horizontal'],
                ]);
                ?>
                <div class="form-group form-group-sm">
                    <label class="control-label col-md-1">推送环境</label>
                    <div class="col-md-2">
                        <?= Html::dropDownList('soa', 'pro', $listSoa, ['class' => 'form-control']) ?>
                    </div>
                    <label class="control-label col-md-1">推送平台</label>
                    <div class="col-md-2">
                        <?= Html::dropDownList('platform', '1', $listPlatForm, ['class' => 'form-control']) ?>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <label class="control-label col-md-1">推送应用</label>
                    <div class="col-md-2">
                        <?= Html::dropDownList('app_id', 1, $listAppId, ['class' => 'form-control']) ?>
                    </div>
                    <label class="control-label col-md-1">推送类别</label>
                    <div class="col-md-2">
                        <?= Html::dropDownList('category', 1, $listCategory, [ 'class' => 'form-control']) ?>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <label class="control-label col-md-1">通知类型</label>
                    <div class="col-md-2">
                        <?= Html::dropDownList('notify_flag', 3, $listNotifyFlag, [ 'class' => 'form-control']) ?>
                    </div>
                    <label class="control-label col-md-1">设备类型</label>
                    <div class="col-md-2">
                        <?= Html::dropDownList('phone_type', 3, $listPhoneType, [ 'class' => 'form-control']) ?>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <div class="form-group">
                        <?= $form->field($model, 'tags', ['template' => '{label}<div class="col-md-4">{input}{hint}{error}</div>', 'labelOptions' => ['class' => 'control-label col-md-1', 'style' => 'padding-left:5px;padding-right:5px']]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'mobile', ['template' => '{label}<div class="col-md-4">{input}{hint}{error}</div>', 'labelOptions' => ['class' => 'control-label col-md-1', 'style' => 'padding-left:5px;padding-right:5px']]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'user_id', ['template' => '{label}<div class="col-md-4">{input}{hint}{error}</div>', 'labelOptions' => ['class' => 'control-label col-md-1', 'style' => 'padding-left:5px;padding-right:5px']]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'title', ['template' => '{label}<div class="col-md-4">{input}{hint}{error}</div>', 'labelOptions' => ['class' => 'control-label col-md-1', 'style' => 'padding-left:5px;padding-right:5px']]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'send_time', ['template' => '{label}<div class="col-md-4">{input}{hint}{error}</div>', 'labelOptions' => ['class' => 'control-label col-md-1', 'style' => 'padding-left:5px;padding-right:5px']]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'url', ['template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>', 'labelOptions' => ['class' => 'control-label col-md-1', 'style' => 'padding-left:5px;padding-right:5px']]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'content', ['template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>', 'labelOptions' => ['class' => 'control-label col-md-1', 'style' => 'padding-left:5px;padding-right:5px']])->textarea() ?>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <label class="control-label col-md-1">推送城市</label>
                    <?php foreach ($listPlace as $key => $value) { ;?>
                        <input type="checkbox"  name="place[]" value="<?=intval($key)?>"  checked="checked">
                        <?= $value ?>
                    <?php }?>
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
                    //getList();
                    $('#myDialog').modal('hide');
                }
            });
            return false;
        });
   });
JS;
$this->registerJs($js);
?>