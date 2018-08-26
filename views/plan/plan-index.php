<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2017/4/25
 * Time: 15:25
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
$this->title = '测试计划';
?>
<div class="row">
    <div class="box col-md-12">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-edit"></i>测试计划</h2>
            </div>
            <div class="box-create">
                <?php
                $form = ActiveForm::begin([
                    'id' => 'list-form',
                    'action' => Url::toRoute('assign/plan-list'),
                    'options' => ['class' => 'form-horizontal'],
                ]);
                ?>
                <div class="form-group form-group-sm">
                    <div class="col-md-1">
                        <a href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myDialog" data-data="add"><i class="glyphicon glyphicon-plus-sign"></i><span>添加计划</span></a>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <input name="page" value="1" type="hidden">
                    <label class="control-label col-md-1">项目类型</label>
                    <div class="col-md-2">
                        <?= Html::dropDownList('type_id', 0, $listProjectType, ['prompt' => '全部', 'class' => 'form-control']) ?>
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
            <div class="box-content"  data-list="<?= Url::toRoute('assign/plan-list') ?>" data-del="<?= Url::toRoute('soa/publish-project-delete') ?>">
            </div>
        </div>
    </div>
    <div class="modal fade" id="myDialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <?php
            $form = ActiveForm::begin([
                'id' => 'edit-form',
                'action' => Url::toRoute('assign/lift-plan-edit'),
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
                    <?= $form->field($model, 'id', ['template' => '{input}', 'options' => ['style' => 'display:none']])->hiddenInput()->label(false) ?>
                    <div class="form-group">
                        <?= $form->field($model, 'type_id', ['template' => '{label}<div class="col-md-2">{input}{hint}{error}</div>', 'labelOptions' => ['class' => 'control-label col-md-1', 'style' => 'padding-left:5px;padding-right:5px']])->dropDownList($listProjectType,[
                            'prompt' => '选择项目类型',
                            'onchange' => '
                                if( $(this).val() ){
                                $.post("index.php?r=soa/get-publish-project&type_id='.'"+$(this).val(),function(data){
                                console.log(data);
                                    var html =  "<option value=>请选择项目</option>";
                                    if( data ){
                                        for(var p in data){
                                            html += "<option value=\'" + data[p].id + "\'  >" + data[p].name + "</option>";
                                        }
                                    }
                                    $("#project").html(html);
                                    $(".select-project").show();
                                })
                                  }else{
                                             $(".select-project").hide();
                                  }
                                ',
                            'class' => 'form-control'
                        ]) ?>
                    </div>
                    <div class="form-group select-project" style="display:none;">
                        <label class="control-label col-md-1">选择项目</label>
                        <div class="col-md-2">
                            <select class="form-control" name="LiftPlan[project_id]" id="project" >
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'version', ['template' => '{label}<div class="col-md-4">{input}{hint}{error}</div>', 'labelOptions' => ['class' => 'control-label col-md-1', 'style' => 'padding-left:5px;padding-right:5px']]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'qa', ['template' => '{label}<div class="col-md-2">{input}{hint}{error}</div>', 'labelOptions' => ['class' => 'control-label col-md-1', 'style' => 'padding-left:5px;padding-right:5px']])->dropDownList($listQa) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'developer', ['template' => '{label}<div class="col-md-2">{input}{hint}{error}</div>', 'labelOptions' => ['class' => 'control-label col-md-1', 'style' => 'padding-left:5px;padding-right:5px']])->dropDownList($listDeveloper) ?>
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
        $("#liftPlan-appointment").datepicker({
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
                modal.find('.modal-title').text("添加计划");
                $("#liftPlan-id").val('');
            }else{
                modal.find('.modal-title').text("编辑计划");
                data=eval(data);
                $("#liftplan-id").val(data.id);
                $("#liftplan-type_id").val(data.type_id);
                $("#liftplan-version").val(data.version);
                $("#liftplan-qa").val(data.qa);
                $("#liftplan-developer").val(data.developer);
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