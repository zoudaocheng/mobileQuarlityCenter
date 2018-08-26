<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/8/2
 * Time: 9:40
 */
use yii\helpers\Html;
use app\models\LiftDetail;
use app\components\Jira;
$this->beginPage() ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
    <div><b>Dear ,all</b></div><div><br></div><div><font size="2"><b><font color="#008000">今日项目发布列表如下</font></b>：</font></div>
    <?php $this->beginBody(); foreach ($contents as $content) {
        $version = Jira::convertVersion($content->version);
        $issues = json_decode(Jira::search(['fixVersion' => $version]));//获取版本对应的需求issue
        $releaseVersion = Jira::getVersionInfo($version);
        $features = '';
        $bugs = '';
        foreach ($issues as $issue){
            if ('New Feature' == $issue->type || 'Sub-Feature' == $issue->type){
                $features = $features.$issue->key.'-'.$issue->summary.'<br>';
            } else {
                $bugs = $bugs.$issue->key.'-'.$issue->summary.'<br>';
            }
        }
        ?>
        <div><ul><li>&nbsp; &nbsp;&nbsp;<b><font size="4">项目&lt;<font color="#008000"><?=$content->project['name']?></font>&gt;版本号&lt;<font color="#008000"><?=$content->version?></font>&gt;</font>：</b></li></ul></div><div>&nbsp; &nbsp; <b><font color="#008000" size="2">版本需求</font></b>：</div><div>&nbsp; &nbsp; &nbsp; &nbsp; <?=($releaseVersion && isset($releaseVersion->description))?$releaseVersion->description:'未添加版本描述'?></div><div>&nbsp; &nbsp; <font color="#ff0000"><b>版本ISSUES</b></font>：</div><div>&nbsp; &nbsp; &nbsp; &nbsp; <?=$features?$features:$bugs;?></div><div><p class="MsoNormal" align="left" style="background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><b><span style="font-size: 10pt; color: gray;"></span></b></p></div>
    <br>
    <?php }?>
    <div><p class="MsoNormal" align="left" style="background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;"><span lang="EN-US" style="font-size:10.0pt;font-family:&quot;Verdana&quot;,sans-serif;color:#1F497D">============================================</span><span lang="EN-US" style="font-size: 9pt; font-family: Verdana, sans-serif;"><o:p></o:p></span></p>

        <p class="MsoNormal" align="left" style="background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><span lang="EN-US" style=" font-size:10.0pt ; ; ; ;  ">Best Regards!</span><span lang="EN-US" style=" font-size:12.0pt ; ; ; ;  ">&nbsp;</span><span lang="EN-US" style="font-size: 9pt; font-family: Verdana, sans-serif;"><o:p></o:p></span></p>

        <p class="MsoNormal" align="left" style="background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><b><span style="font-size:10.0pt;mso-ascii-font-family:
宋体;mso-hansi-font-family:宋体;color:teal;mso-no-proof:yes">上海享途网络科技有限公司 - 测试组</span></b></p>

        <p class="MsoNormal" align="left" style="background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><b><span style="font-size:10.0pt;mso-ascii-font-family:
宋体;mso-hansi-font-family:宋体;color:teal;mso-no-proof:yes">邮箱：qa@lechebang.com</span></b></p>

        <p class="MsoNormal" align="left" style="background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><b><span style="font-size:10.0pt;mso-ascii-font-family:
宋体;mso-hansi-font-family:宋体;color:teal;mso-no-proof:yes">电话：</span></b><b><span lang="EN-US" style=" font-size:10.0pt ; ; ; ;  ">18616384965</span></b><span lang="EN-US" style="font-size: 9pt; font-family: Verdana, sans-serif;"><o:p></o:p></span></p>

        <p class="MsoNormal" align="left" style="background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><b><span style="font-size:10.0pt;mso-ascii-font-family:
宋体;mso-hansi-font-family:宋体;color:gray;mso-no-proof:yes">网址：</span></b><a href="http://www.lechebang.com"><b><span lang="EN-US" style=" font-size:10.0pt ; ; ; ;  ">http://www.lechebang.com</span></b></a><b><span lang="EN-US" style=" font-size:10.0pt ; ; ; ;
 ">&nbsp;</span></b><span lang="EN-US" style="font-size: 9pt; font-family: Verdana, sans-serif;"><o:p></o:p></span></p>

        <p class="MsoNormal" align="left" style="background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><b><span style="font-size:10.0pt;mso-ascii-font-family:
宋体;mso-hansi-font-family:宋体;color:gray;mso-no-proof:yes">地址：上海市杨浦区</span></b><font color="#808080"><span style="font-size: 13.3333px;"><b>国霞路259号绿地双创中心H1楼5楼(</b></span></font><b><span style="font-size:10.0pt;mso-ascii-font-family:
宋体;mso-hansi-font-family:宋体;color:gray;mso-no-proof:yes">乐车邦)</span></b></p></div>
    <?php $this->endBody(); ?>
    </body>
    </html>
<?php $this->endPage() ?>
