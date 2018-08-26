<?php
/**
 * @link http://mqc.lechebang.com
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/8/17
 * Time: 17:35
 */
use yii\helpers\Html;
$this->title = '营销推广报表';
$this->beginPage() ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
    <?php $this->beginBody(); foreach ($contents as $content) { ?>
        <div class="form-group form-group-sm">
            <label class="control-label col-md-1"><b><?=$content['channel']; ?></b></label>
            <table id="" class="table table-striped table-bordered responsive">
                <thead>
                <tr>
                    <th>订单ID</th>
                    <th>订单号</th>
                    <th>订单手机</th>
                    <th>车牌号</th>
                    <th>车型</th>
                    <th>渠道</th>
                    <th>城市</th>
                    <th>店铺名称</th>
                    <th>支付策略</th>
                    <th>乐享价</th>
                    <th>支付金额</th>
                    <th>结算金额</th>
                    <th>订单状态</th>
                    <th>支付状态</th>
                    <th>退款状态</th>
                    <th>预约时间</th>
                    <th>创建时间</th>
                    <th>完成时间</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($content['orders']->models as $order) {?>
                    <tr>
                        <td class="center"><?= $order['order_id'] ?></td>
                        <td class="center"><?= $order->order['order_number'];?></td>
                        <td class="center"><?= $order->order->maintenanceItem['contact_user_mobile'];?></td>
                        <td class="center"><?= $order->order->maintenanceItem['car_number'];?></td>
                        <td class="center"><?= $order->order->maintenanceItem['brand_type_name'];?></td>
                        <td class="center"><?= Html::encode($order->order->application['name']) ?></td>
                        <td class="center"><?= Html::encode($order->order->place['name']) ?></td>
                        <td class="center"><?= Html::encode($order->order->store['store_name']) ?></td>
                        <td class="center"><?= 1==$order->order['payment_policy']?'直接支付':2==$order->order['payment_policy']?'优惠券支付':'内测' ?></td>
                        <td class="center"><?= intval($order->order['sale_amount']/100).' 元' ?></td>
                        <td class="center"><?= intval($order->order['pay_amount']/100).' 元' ?></td>
                        <td class="center"><?= intval($order->order['contract_amount']/100).' 元' ?></td>
                        <td class="center"><?php
                            if(1 == $order->order['order_status']){
                                echo '新增';
                            }elseif(2 == $order->order['order_status']){
                                echo '已支付';
                            }elseif(3 == $order->order['order_status']){
                                echo '处理中';
                            }elseif(4 == $order->order['order_status']){
                                echo '已完成';
                            }elseif(5 == $order->order['order_status']){
                                echo '已取消';
                            } ?>
                        </td>
                        <td class="center"><?php
                            if(1 == $order->order['payment_status']){
                                echo '正在支付';
                            }elseif(2 == $order->order['payment_status']){
                                echo '支付完成';
                            }elseif(3 == $order->order['payment_status']){
                                echo '支付失败';
                            }elseif(0 == $order->order['payment_status']){
                                echo '未支付';
                            }?>
                        </td>
                        <td class="center"><?php
                            if(1 == $order->order['refund_status']){
                                echo '退款中';
                            }elseif(2 == $order->order['refund_status']){
                                echo '退款完成';
                            }elseif(3 == $order->order['refund_status']){
                                echo '退款失败';
                            }elseif(0 == $order->order['refund_status']) {
                                echo '无退款';
                            }?>
                        </td>
                        <td class="center"><?= $order->order->maintenanceItem['appoint_time'];?></td>
                        <td class="center"><?= date('Y-m-d H:i:s',$order->order['created_time']/1000)?></td>
                        <td class="center"><?= $order->order->maintenanceItem['finish_time']?$order->order->maintenanceItem['finish_time']:'-';?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="form-group form-group-sm">
            <table id="" class="table table-striped table-bordered responsive">
                <thead>
                <tr>
                    <th>日期</th>
                    <th>点击链接</th>
                    <th>注册</th>
                    <th>登录</th>
                    <th>下单</th>
                    <th>支付</th>
                    <th>到店</th>
                    <th>完成</th>
                    <th>行为总数</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $totalJoin = 0;
                $totalRegister = 0;
                $totalLogin = 0;
                $totalCreate = 0;
                $totalPay = 0;
                $totalCancel = 0;
                $totalArrived = 0;
                $totalFinished = 0;
                foreach ($content['reports'] as $m) {
                    $totalBehavior = 0;
                    $totalJoin += $m['join'];
                    $totalBehavior += $m['join'];
                    $totalRegister += $m['register'];
                    $totalBehavior += $m['register'];
                    $totalLogin += $m['login'];
                    $totalBehavior += $m['login'];
                    $totalCreate += $m['create'];
                    $totalBehavior += $m['create'];
                    $totalPay += $m['pay'];
                    $totalBehavior += $m['pay'];
                    $totalCancel += $m['cancel'];
                    $totalBehavior += $m['cancel'];
                    $totalArrived += $m['arrived'];
                    $totalBehavior += $m['arrived'];
                    $totalFinished += $m['finished'];
                    $totalBehavior += $m['finished'];
                    ?>
                    <tr>
                        <td class="center"><?= $m['date'] ?></td>
                        <td class="center"><?= $m['join'] ?></td>
                        <td class="center"><?= $m['register'] ?></td>
                        <td class="center"><?= $m['login'] ?></td>
                        <td class="center"><?= $m['create'] ?></td>
                        <td class="center"><?= $m['pay'] ?></td>
                        <td class="center"><?= $m['arrived'] ?></td>
                        <td class="center"><?= $m['finished'] ?></td>
                        <td class="center"><?= $totalBehavior ?></td>
                    </tr>
                <?php } ?>
                <tr class="success">
                    <td class="center"><label>合计</label></td>
                    <td class="center"><?= $totalJoin ?></td>
                    <td class="center"><?= $totalRegister ?></td>
                    <td class="center"><?= $totalLogin ?></td>
                    <td class="center"><?= $totalCreate ?></td>
                    <td class="center"><?= $totalPay ?></td>
                    <td class="center"><?= $totalArrived ?></td>
                    <td class="center"><?= $totalFinished ?></td>
                    <td class="center"><?= $totalJoin + $totalRegister + $totalLogin + $totalCreate + $totalPay + $totalArrived + $totalFinished + $totalCancel?></td>
                </tr>
                </tbody>
            </table>
        </div>
        <hr/>
    <?php }?>
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