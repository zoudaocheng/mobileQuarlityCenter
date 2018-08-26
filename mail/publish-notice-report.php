<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/12/15
 * Time: 10:25
 * 每周五固定邮件（自上周五至本周四发布项目）
 *
 */
use yii\helpers\Html;
$this->title = '发布通知报告';
$this->beginPage() ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
    <?php $this->beginBody(); ?>
    <div class="form-group form-group-sm">
        <label class="control-label col-md-1"></label>
    </div>
    <p class="MsoNormal"><span lang="EN-US">Dear, All,<o:p></o:p></span></p>

    <p class="MsoNormal"><span lang="EN-US">&nbsp;</span></p>

    <p class="MsoNormal">附件为发布项目列表,请查收<span lang="EN-US"><o:p></o:p></span></p>

    <p class="MsoNormal"><span lang="EN-US">&nbsp;</span></p>

    <p class="MsoNormal" align="left" style="background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><a name="_MailAutoSig"><span lang="EN-US" style="font-size:10.0pt;font-family:&quot;Verdana&quot;,sans-serif;
color:#1F497D;mso-no-proof:yes">============================================</span></a><span lang="EN-US" style="font-size: 9pt; font-family: Verdana, sans-serif;"><o:p></o:p></span></p>

    <p class="MsoNormal" align="left" style="background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><span lang="EN-US" style="font-size: 10pt;">Best Regards!</span><span lang="EN-US" style="font-size: 12pt;">&nbsp;</span><span lang="EN-US" style="font-size: 9pt; font-family: Verdana, sans-serif;"><o:p></o:p></span></p><p class="MsoNormal" align="left" style="background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><b><span style="font-size: 10pt; color: teal;">上海享途网络科技有限公司 - 测试组</span></b></p><p class="MsoNormal" align="left" style="background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><b><span style="font-size: 10pt; color: teal;">邮箱：qa@lechebang.com</span></b></p><p class="MsoNormal" align="left" style="background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><b><span style="font-size: 10pt; color: teal;">电话：</span></b><b><span lang="EN-US" style="font-size: 10pt;">18616384965</span></b><span lang="EN-US" style="font-size: 9pt; font-family: Verdana, sans-serif;"><o:p></o:p></span></p><p class="MsoNormal" align="left" style="background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><b><span style="font-size: 10pt; color: gray;">网址：</span></b><a href="http://www.lechebang.com/"><b><span lang="EN-US" style="font-size: 10pt;">http://www.lechebang.com</span></b></a><b><span lang="EN-US" style="font-size: 10pt;">&nbsp;</span></b><span lang="EN-US" style="font-size: 9pt; font-family: Verdana, sans-serif;"><o:p></o:p></span></p><p class="MsoNormal" align="left" style="background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><b><span style="font-size: 10pt; color: gray;">地址：上海市杨浦区</span></b><font color="#808080"><span style="font-size: 13.3333px;"><b>国霞路259号绿地双创中心H1楼5楼(</b></span></font><b><span style="font-size: 10pt; color: gray;">乐车邦)</span></b></p>
    <?php $this->endBody(); ?>
    </body>
    </html>
<?php $this->endPage() ?>