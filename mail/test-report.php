<?php
/**
 * 测试报告邮件
 * 收件人：开发人员 、各 leader 及 QA团队
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2017/4/28
 * Time: 10:14
 */
use yii\helpers\Html;
$this->title = '测试报告';
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
    <div><b>Dear</b> , <b><?=$content['developer'] ?></b></div><div><br></div><div>&nbsp; &nbsp; 您的项目版本 <b><font color="#ff0000"><?=$content['version'] ?></font></b>&nbsp;第【<?=$content['count']?>】 轮测试完成，概要如下：</div><div>&nbsp; &nbsp;&nbsp;</div><div>&nbsp; &nbsp; 首次提测时间：<?=date('Y-m-d H:i:s',$content['lift_time']) ?></div><div>&nbsp; &nbsp; 本轮提测时间：<?=date('Y-m-d H:i:s',$content['current_lift_time']) ?></div><div>&nbsp; &nbsp; 测试环境：<?=$content['environment']?></div><div>&nbsp; &nbsp; 本轮测试结果：<?php if(0 == $content['result']) { echo '<font color="#ff0000"><b>测试失败</b></font><b>';}else{echo '<font color="#008000">测试通过</font></b>'; } ?></div><div>&nbsp; &nbsp; 构建号：【<?=$content['build_no'] ?>】</div><div>&nbsp; &nbsp; 迭代次数：【<?=$content['count']?>】</div><div>&nbsp; &nbsp; BUG列表如下
    </div><div><ul><?php if (count(json_decode($content['issues']))) { foreach (json_decode($content['issues']) as $issue) { if ('Bug' == $issue->type) echo '<li><a href="'.$issue->uri.'">'.$issue->key.'</a>&nbsp;解决状态['.$issue->resolution.']'.'&nbsp;- '.$issue->summary.'</li>';}} ?></ul></div><div>&nbsp; &nbsp;</div><div>&nbsp; &nbsp; <font color="#ff0000"><?php if(3 <= $content['count']) {echo '<b>注意</b></font>：由于您的项目版本已经【'.$content['count'].'】次迭代测试不通过，如超过3次该版本未测试通过，为节约测试资源，该项目版本测试优先级将降低，请知晓~<img src="https://rescdn.qqmail.com/zh_CN/images/mo/DEFAULT2/5.gif"></div><div><br></div><div>谢谢！</div>';} ?><div><br></div><div><p class="MsoNormal" align="left" style="background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><a name="_MailAutoSig"><span lang="EN-US" style="font-size:10.0pt;font-family:&quot;Verdana&quot;,sans-serif;
color:#1F497D;mso-no-proof:yes">============================================</span></a><span lang="EN-US" style="font-size: 9pt; font-family: Verdana, sans-serif;"><o:p></o:p></span></p>

        <p class="MsoNormal" align="left" style="background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><span lang="EN-US" style="font-size:10.0pt;
font-family:&quot;Verdana&quot;,sans-serif;color:#1F497D;mso-no-proof:yes">Best Regards!</span><span lang="EN-US" style="font-size:12.0pt;
font-family:&quot;Verdana&quot;,sans-serif;color:#1F497D;mso-no-proof:yes">&nbsp;</span><span lang="EN-US" style="font-size: 9pt; font-family: Verdana, sans-serif;"><o:p></o:p></span></p>

        <p class="MsoNormal" align="left" style="background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><b><span style="font-size:10.0pt;mso-ascii-font-family:
宋体;mso-hansi-font-family:宋体;color:teal;mso-no-proof:yes">上海享途网络科技有限公司 - 测试组</span></b></p>

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