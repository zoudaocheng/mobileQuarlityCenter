<?php
/**
 * @link http://mqc.lcbint.com
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/6/13
 * Time: 10:22
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = '平台中心首页';
$this->params['breadcrumbs'] = '更改资料';
?>
<div class="row">
    <div class="box col-md-12">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-edit"></i>更改资料</h2>
            </div>
            <div class="box-content">
                <?php
                $form = ActiveForm::begin([
                    'id'=>'editProfile-form',
                    'action'=> Url::toRoute('admin/edit-profile')
                ]);
                ?>
                <?= $form->field($model, 'password')->passwordInput()->label("原密码")?>
                <?= $form->field($model, 'newPassword')->passwordInput()->label("新密码") ?>
                <?= $form->field($model, 'verifyNewPassword')->passwordInput()->label("确认新密码") ?>
                <?= Html::submitButton('保存', ['class' => 'btn btn-primary']) ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
<?php
$js = <<<JS
  $("#editProfile-form").on('beforeSubmit',function(e){
        ajaxSubmitForm($(this));
        return false;
    });
JS;
?>
