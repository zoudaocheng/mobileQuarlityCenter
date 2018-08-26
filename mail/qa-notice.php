<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2017/4/28
 * Time: 10:16
 */
use yii\helpers\Html;
$this->title = '提测通知';
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
    <div><b>Dear</b> , <b><?=$content['qa']; ?></b></div><div><br></div><div>&nbsp; &nbsp; 项目版本 <b><font color="#339966"><?=$content['version']?></font></b>&nbsp;第【<?=$content['count']?>】轮已提测,概要如下：</div><div>&nbsp; &nbsp; </div><div>&nbsp; &nbsp; 开发人员：【<?=$content['developer']?>】</div><div>&nbsp; &nbsp; 预计提测时间：<?=$content['pre_lift_time']?$content['pre_lift_time']:'临时任务' ?></div><div>&nbsp; &nbsp; 本轮提测时间：<?=date('Y-m-d H:i:s',$content['current_lift_time']?$content['current_lift_time']:time())?></div><div>&nbsp; &nbsp; 是否延迟提测：【<?php if($content['pre_lift_time'] && ($content['lift_time'] > strtotime($content['pre_lift_time'].' 23:59:59'))) {echo '<b><font color="#ff0000">是</font></b>';}else{echo '<font color="#008000"><b>否</b></font>';}?>】</div><div>&nbsp; &nbsp; 测试环境：<?=$content['environment'] ?></div><div>&nbsp; &nbsp; 发布级别：<b><font color="#ff0000"><?=$content['level']?></font></b></div><div>&nbsp; &nbsp; 预计发布时间：<?=$content['pre_publish_time']?></div><div>&nbsp; &nbsp; 需求列表如下
    </div><div><ul><?php if (count(json_decode($content['issues']))) { foreach (json_decode($content['issues']) as $issue) { echo '<li><a href="'.$issue->uri.'">'.$issue->key.'</a> '.$issue->summary.'</li>';}}else{ echo '<b><font color="#ff0000">请告知开发人员关联版本ISSUE号</font></b>';} ?></ul></div><div>&nbsp; &nbsp; 测试建议：<?=$content['advice']?></div><div>&nbsp; &nbsp;</div><div>&nbsp; &nbsp; 请合理安排时间完成测试，有关问题可询问相关人员，如上请知晓~</div><div><br></div><div>谢谢！</div><div><br></div><div><p class="MsoNormal" align="left" style="background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><a name="_MailAutoSig"><span lang="EN-US" style=" font-size:10.0pt ; ; ;
;  ">============================================</span></a><span lang="EN-US" style="font-size: 9pt; font-family: Verdana, sans-serif;"><o:p></o:p></span></p>

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