<?php
/**
 * @link http://mqc.lcbint.com
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/6/27
 * Time: 18:22
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
$this->title = 'SOA管理';
?>
    <div class="row">
    <div class="box col-md-12">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-edit"></i>SOA管理</h2>
            </div>
            <div class="box-create">
                <?php
                $form = ActiveForm::begin([
                    'id' => 'list-form',
                    'action' => Url::toRoute('soa/soa-list'),
                    'options' => ['class' => 'form-horizontal'],
                ]);
                ?>
                <div class="form-group form-group-sm">
                    <div class="col-md-1">
                        <a href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myDialog" data-data="add"><i class="glyphicon glyphicon-plus-sign"></i><span>添加SOA</span></a>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <input name="page" value="1" type="hidden">
                    <div class="form-group form-group-sm">
                        <div class="checkbox">
                            <label class="control-label col-md-1">
                                <input name="is_valid" value="1"  type="checkbox">有效SOA
                            </label>
                        </div>
                    </div>
                    <label class="control-label col-md-1">项目</label>
                    <div class="col-md-2">
                        <?= Html::dropDownList('project_id', 0, $listProject, ['prompt' => '全部', 'class' => 'form-control']) ?>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <label class="control-label col-md-1">添加时间</label>
                    <div class="col-md-2">
                        <input class="form-control" type="text" name="createtime_start" readonly="readonly">
                    </div>
                    <label class="control-label col-md-1">到</label>
                    <div class="col-md-2">
                        <input class="form-control" type="text" name="createtime_end" readonly="readonly">
                    </div>
                    <div class="col-md-2">
                        <a href="#" onclick="getList(1)" class="btn btn-default btn-block" ><i class="glyphicon glyphicon-search"></i><span>查询</span></a>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
            <div class="box-content"  data-list="<?= Url::toRoute('soa/soa-list') ?>" data-del="<?= Url::toRoute('soa/soa-delete') ?>">
            </div>
        </div>
    </div>
    <div class="modal fade" id="myDialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <?php
            $form = ActiveForm::begin([
                'id' => 'edit-form',
                'action' => Url::toRoute('soa/soa-edit'),
                'options' => ['class' => 'form-horizontal'],
                'fieldConfig' => [
                    'options' => ['class' => NULL],
                ],
            ]);
            ?>
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Modal title</h4>
                </div>
                <div class="modal-body">
                    <?= $form->field($model, 'id', ['template' => '{input}', 'options' => ['stype' => 'display:none']])->hiddenInput()->label(false) ?>
                    <div class="form-group">
                        <?= $form->field($model, 'project_id', ['template' => '{label}<div class="col-md-2">{input}{hint}{error}</div>', 'labelOptions' => ['class' => 'control-label col-md-1', 'style' => 'padding-left:5px;padding-right:5px']])->dropDownList($listProject) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'soa_name', ['template' => '{label}<div class="col-md-4">{input}{hint}{error}</div>', 'labelOptions' => ['class' => 'control-label col-md-1', 'style' => 'padding-left:5px;padding-right:5px']]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'url', ['template' => '{label}<div class="col-md-4">{input}{hint}{error}</div>', 'labelOptions' => ['class' => 'control-label col-md-1', 'style' => 'padding-left:5px;padding-right:5px']]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'soa_desc', ['template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>', 'labelOptions' => ['class' => 'control-label col-md-1', 'style' => 'padding-left:5px;padding-right:5px']])->textarea() ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'mock', ['template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>', 'labelOptions' => ['class' => 'control-label col-md-1', 'style' => 'padding-left:5px;padding-right:5px']])->textarea() ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'response', ['template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>', 'labelOptions' => ['class' => 'control-label col-md-1', 'style' => 'padding-left:5px;padding-right:5px']])->textarea() ?>
                    </div>
                    <div class="form-group">
                        <div class="col-md-2">
                            <?= $form->field($model, 'is_valid')->checkbox()->label('有效SOA') ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <?= Html::submitButton('保存', ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
<?php
$js = <<<JS
    $(function(){
        $("#soa-appointment").datepicker({
            dateFormat:'yy-mm-dd'
        });
        customDatepicker($("input[name='createtime_start']"),$("input[name='createtime_end']"));
        getList(1);
        $('#myDialog').on('show.bs.modal', function (event) {
            if(!event.relatedTarget){//判断是不是时间选择触发的事件
                return false;
            }
            var button = $(event.relatedTarget);
            var data = button.data('data');
            var modal = $(this);
            $("#edit-form")[0].reset();//重置表单
            if(data=='add')
            {
                modal.find('.modal-title').text("添加SOA");
                $("#soa-id").val('');
            }else{
                modal.find('.modal-title').text("编辑SOA");
                data=eval(data);
                $("#soa-id").val(data.id);
                $("#soa-project_id").val(data.project_id);
                $("#soa-soa_name").val(data.soa_name);
                $("#soa-url").val(data.url);
                $("#soa-is_valid").prop('checked',data.is_valid==1);
                $("#soa-soa_desc").val(data.soa_desc);
                $("#soa-mock").val(data.mock);
                $("#soa-response").val(data.response);
                $("#soa-request_field").val(data.request_field);
                $("#soa-request_param").val(data.request_param);
                $("#soa-response_field").val(data.response_field);
                $("#soa-response_param").val(data.response_param);
                $("#record-note").val(data.note);
            }
        });
        $("#edit-form").on('beforeSubmit',function(e){
            ajaxSubmitForm($(this),function(data){
                if(data.status==1){
                    getList();
                    $('#myDialog').modal('hide');
                }
            });
            return false;
        });
   });
   //获得项目列表
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
            $(".box-content").html(data);
        }
    });
   }
   window.del=function(id){
        layer.confirm('确定删除?', function(index){
            layer.close(index);
            $.ajax({
                url: $(".box-content").data("del"),
                data:{
                    id:id
                },
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
                    if (data.status == 1)
                    {
                        layer.alert(data.message, {icon: 6},function(index){
                            getList(null);
                            layer.close(index);
                        });
                    }
                    else {
                        layer.alert(data.message, {icon: 5}, function (index) {
                            layer.close(index);
                        });
                    }
                }
            });  
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