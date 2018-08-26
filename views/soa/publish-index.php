<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/8/2
 * Time: 11:38
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = '发布通知管理';
?>
    <div class="row">
    <div class="box col-md-12">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-edit"></i>发布通知</h2>
            </div>
            <div class="box-create">
                <?php
                $form = ActiveForm::begin([
                    'id' => 'list-form',
                    'action' => Url::toRoute('soa/publish-list'),
                    'options' => ['class' => 'form-horizontal'],
                ]);
                ?>
                <div class="form-group form-group-sm">
                    <div class="col-md-1">
                        <a href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myDialog" data-data="add"><i class="glyphicon glyphicon-plus-sign"></i><span>添加发布通知</span></a>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <input name="page" value="1" type="hidden">
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
            <div class="box-content"  data-list="<?= Url::toRoute('soa/publish-list') ?>" data-del="<?= Url::toRoute('soa/publish-run') ?>">
            </div>
        </div>
    </div>
    <div class="modal fade" id="myDialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <?php
            $form = ActiveForm::begin([
                'id' => 'edit-form',
                'action' => Url::toRoute('soa/publish-edit'),
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
                            <select class="form-control" name="Publish[project_id]" id="project" >
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'headline', ['template' => '{label}<div class="col-md-3">{input}{hint}{error}</div>', 'labelOptions' => ['class' => 'control-label col-md-1', 'style' => 'padding-left:5px;padding-right:5px']]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'version', ['template' => '{label}<div class="col-md-3">{input}{hint}{error}</div>', 'labelOptions' => ['class' => 'control-label col-md-1', 'style' => 'padding-left:5px;padding-right:5px']]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'lifter', ['template' => '{label}<div class="col-md-3">{input}{hint}{error}</div>', 'labelOptions' => ['class' => 'control-label col-md-1', 'style' => 'padding-left:5px;padding-right:5px']]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'lift_time', ['inputOptions'=>['readonly'=>'readonly'],'template' => '{label}<div class="col-md-3">{input}{hint}{error}</div>', 'labelOptions' => ['class' => 'control-label col-md-1', 'style' => 'padding-left:5px;padding-right:5px']]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'content', ['template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>', 'labelOptions' => ['class' => 'control-label col-md-1', 'style' => 'padding-left:5px;padding-right:5px']])->textarea() ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'publish_status')->checkbox()->label('已发布') ?>
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
        $("#project-appointment").datepicker({
            dateFormat:'yy-mm-dd'
        });
        customDatepicker($("input[name='Publish[lift_time]']"),$("input[name='createtime_start']"),$("input[name='createtime_end']"));
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
                modal.find('.modal-title').text("添加发布内容");
                $("#publish-id").val('');
            }
            else{
                modal.find('.modal-title').text("编辑发布内容");
                data=eval(data);
                console.log(data);
                $("#publish-id").val(data.id);
                $("#publish-type_id").val(data.type_id);
                $("#publish-project_id").val(data.project_id);
                $("#publish-headline").val(data.headline);
                $("#publish-version").val(data.version);
                $("#publish-lifter").val(data.lifter);
                $("#publish-lift_time").val(data.lift_time);
                $("#publish-content").val(data.content);
                $("#publish-publish_status").prop('checked',data.publish_status==1?true:false);
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
   //获得科室列表
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
        layer.confirm('确定发送通知?', function(index){
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