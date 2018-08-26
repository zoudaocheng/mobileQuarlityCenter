<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/8/2
 * Time: 14:34
 */
use yii\helpers\Html;
$this->beginPage() ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
    <div class="form-group form-group-sm">
        <label class="control-label col-md-1">Dear, all:</label>
        <div class="form-group">
            <?= '今天发布内容如下:'; ?>
        </div>
        <br>
    </div>
    <div class="form-group form-group-sm">
        <label class="control-label col-md-1"><b><?=$content->title; ?></b></label>
        <div class="form-group">
            &nbsp;&nbsp;<?= $content->content; ?>
        </div>
        <br>
    </div>
    <div><p class="MsoNormal" align="left" style="background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;"><span lang="EN-US" style="font-size:10.0pt;font-family:&quot;Verdana&quot;,sans-serif;color:#1F497D">============================================</span><span lang="EN-US" style="font-size: 9pt; font-family: Verdana, sans-serif;"><o:p></o:p></span></p>

        <p class="MsoNormal" align="left" style="background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;"><span lang="EN-US" style="font-size:10.0pt;font-family:&quot;Verdana&quot;,sans-serif;color:#1F497D">Best
Regards!</span><span lang="EN-US" style="font-size:12.0pt;font-family:&quot;Verdana&quot;,sans-serif;
color:#1F497D">&nbsp;</span><span lang="EN-US" style="font-size: 9pt; font-family: Verdana, sans-serif;"><o:p></o:p></span></p>

        <p class="MsoNormal" align="left" style="background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;"><b><span style="font-size:10.0pt;mso-ascii-font-family:宋体;mso-hansi-font-family:宋体;
color:teal">上海享途网络科技有限公司</span></b><span lang="EN-US" style="font-size: 9pt; font-family: Verdana, sans-serif;"><o:p></o:p></span></p>

        <p class="MsoNormal" align="left" style="background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;"><b><span style="font-size:10.0pt;mso-ascii-font-family:宋体;mso-hansi-font-family:宋体;
color:teal">邮箱：</span></b><span lang="EN-US" style="font-size: 9pt; font-family: Verdana, sans-serif;"><a href="mailto:qa@lechebang.com"><b><span style="font-size:10.0pt;color:#09578D">qa@lechebang.com</span></b></a><o:p></o:p></span></p>

        <p class="MsoNormal" align="left" style="background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;"><b><span style="font-size:10.0pt;mso-ascii-font-family:宋体;mso-hansi-font-family:宋体;
color:teal">电话：</span></b><b><span lang="EN-US" style="font-size:10.0pt;
font-family:&quot;Times New Roman&quot;,serif;color:teal">18616384965</span></b><span lang="EN-US" style="font-size: 9pt; font-family: Verdana, sans-serif;"><o:p></o:p></span></p>

        <p class="MsoNormal" align="left" style="background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;"><b><span style="font-size:10.0pt;mso-ascii-font-family:宋体;mso-hansi-font-family:宋体;
color:gray">网址：</span></b><b><span lang="EN-US" style="font-size:10.0pt;
font-family:&quot;Verdana&quot;,sans-serif;color:gray"><a href="http://www.lechebang.com/">http://www.lechebang.com</a>&nbsp;</span></b><span lang="EN-US" style="font-size: 9pt; font-family: Verdana, sans-serif;"><o:p></o:p></span></p>

        <p class="MsoNormal" align="left" style="background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;"><b><span style="font-size:10.0pt;mso-ascii-font-family:宋体;mso-hansi-font-family:宋体;
color:gray">地址：上海市杨浦区伟德路</span></b><b><span lang="EN-US" style="font-size:10.0pt;
font-family:&quot;Times New Roman&quot;,serif;color:gray">6</span></b><b><span style="font-size:10.0pt;mso-ascii-font-family:宋体;mso-hansi-font-family:宋体;
color:gray">号（云海大厦）</span></b><b><span lang="EN-US" style="font-size:10.0pt;
font-family:&quot;Times New Roman&quot;,serif;color:gray">10</span></b><b><span style="font-size:10.0pt;mso-ascii-font-family:宋体;mso-hansi-font-family:宋体;
color:gray">楼乐车邦</span></b><span lang="EN-US" style="font-size: 9pt; font-family: Verdana, sans-serif;"><o:p></o:p></span></p></div>
    <?php $this->endBody(); ?>
    </body>
    </html>
<?php $this->endPage() ?>