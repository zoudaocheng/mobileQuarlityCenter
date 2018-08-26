<?php
/**
 * 提测邮件友情提醒
 * 收件人:开发人员 、各 leader 及 QA团队
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2017/4/28
 * Time: 10:15
 */
use yii\helpers\Html;
$this->title = '提测邮件提醒';
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
    <div><b>Dear</b> , <b><?=$content['developer']?></b></div><div><br></div><div>&nbsp; &nbsp; 您开发的项目版本 <b><font color="#ff0000"><?=$content['version']?></font></b> （如版本号或计划已变更请告知）比预计提测时间【<font color="#ff0000"><b><?=$content['pre_lift_time']?></b></font>】已经 <font color="#ff0000"><b>Delay</b></font> 了 <font color="#ff0000"><b><?php echo intval((strtotime(date('Y-m-d'),time())-strtotime($content['pre_lift_time']))/(24*3600))?>&nbsp;</b></font>天，请尽快提测，以免影响测试进度从而影响上线时间</div><div><br></div><div>谢谢！</div><div><br></div><div><p class="MsoNormal" align="left" style="background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><a name="_MailAutoSig"><span lang="EN-US" style="font-size:10.0pt;font-family:&quot;Verdana&quot;,sans-serif;
color:#1F497D;mso-no-proof:yes">============================================</span></a><span lang="EN-US" style="font-size: 9pt; font-family: Verdana, sans-serif;"><o:p></o:p></span></p>

        <p class="MsoNormal" align="left" style="background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><span lang="EN-US" style="font-size:10.0pt;
font-family:&quot;Verdana&quot;,sans-serif;color:#1F497D;mso-no-proof:yes">Best Regards!</span><span lang="EN-US" style="font-size:12.0pt;
font-family:&quot;Verdana&quot;,sans-serif;color:#1F497D;mso-no-proof:yes">&nbsp;</span><span lang="EN-US" style="font-size: 9pt; font-family: Verdana, sans-serif;"><o:p></o:p></span></p>

        <p class="MsoNormal" align="left" style="background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><b><span style="font-size:10.0pt;mso-ascii-font-family:
宋体;mso-hansi-font-family:宋体;color:teal;mso-no-proof:yes">上海享途网络科技有限公司 - QA组</span></b></p>

        <p class="MsoNormal" align="left" style="background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><b><span style="font-size:10.0pt;mso-ascii-font-family:
宋体;mso-hansi-font-family:宋体;color:teal;mso-no-proof:yes">邮箱：qa@lechebang.com</span></b></p>

        <p class="MsoNormal" align="left" style="background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><b><span style="font-size:10.0pt;mso-ascii-font-family:
宋体;mso-hansi-font-family:宋体;color:teal;mso-no-proof:yes">电话：</span></b><b><span lang="EN-US" style="font-size:10.0pt;
font-family:&quot;Times New Roman&quot;,serif;color:teal;mso-no-proof:yes">18616384965</span></b><span lang="EN-US" style="font-size: 9pt; font-family: Verdana, sans-serif;"><o:p></o:p></span></p>

        <p class="MsoNormal" align="left" style="background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><b><span style="font-size:10.0pt;mso-ascii-font-family:
宋体;mso-hansi-font-family:宋体;color:gray;mso-no-proof:yes">网址：</span></b><a href="http://www.lechebang.com"><b><span lang="EN-US" style="font-size:10.0pt;
font-family:&quot;Verdana&quot;,sans-serif;color:#0563C1;mso-no-proof:yes">http://www.lechebang.com</span></b></a><b><span lang="EN-US" style="font-size:10.0pt;font-family:&quot;Verdana&quot;,sans-serif;color:gray;
mso-no-proof:yes">&nbsp;</span></b><span lang="EN-US" style="font-size: 9pt; font-family: Verdana, sans-serif;"><o:p></o:p></span></p>

        <p class="MsoNormal" align="left" style="background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><b><span style="font-size:10.0pt;mso-ascii-font-family:
宋体;mso-hansi-font-family:宋体;color:gray;mso-no-proof:yes">地址：上海市杨浦区</span></b><font color="#808080"><span style="font-size: 13.3333px;"><b>国霞路259号绿地双创中心H1楼5楼(</b></span></font><b><span style="font-size:10.0pt;mso-ascii-font-family:
宋体;mso-hansi-font-family:宋体;color:gray;mso-no-proof:yes">乐车邦)</span></b></p></div>
    <?php $this->endBody(); ?>
    </body>
    </html>
<?php $this->endPage() ?>