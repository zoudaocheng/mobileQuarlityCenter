<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="navbar navbar-default" role="navigation">
    <div class="navbar-inner">
        <button type="button" class="navbar-toggle pull-left animated flip">
            <span class="sr-only">展开</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand"> <img src="<?= Url::to('@web/img/logo20.png') ?>" class="hidden-xs"/>
            <span><?= Yii::$app->params['Company'] ?></span></a>
        <!-- user dropdown starts -->
        <div class="btn-group pull-right">
            <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                <i class="glyphicon glyphicon-user"></i><span class="hidden-sm hidden-xs"><?= Yii::$app->user->identity->username ?></span>
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a href="<?=  Url::toRoute('admin/profile')?>">更改资料</a></li>
                <li class="divider"></li>
                <li><a href="<?= Url::toRoute('site/logout') ?>">注销</a></li>
            </ul>
        </div>
        <!-- user dropdown ends -->
    </div>
</div>
<!-- topbar ends -->
<div class="ch-container">
    <div class="row">
        <!-- left menu starts -->
        <div class="col-sm-2 col-lg-2">
            <div class="sidebar-nav">
                <div class="nav-canvas">
                    <div class="nav-sm nav nav-stacked">

                    </div>
                    <ul class="nav nav-pills nav-stacked main-menu">
                        <li class="nav-header">管理导航</li>
                        <li><a class="ajax-link" href="<?=  Url::toRoute('admin/index')?>"><i class="glyphicon glyphicon-home"></i><span>平台首页</span></a>
                        </li>
                        <li class="accordion">
                            <a href="#"><i class="glyphicon glyphicon-magnet"></i><span>工具管理</span></a>
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="<?= Url::toRoute('operation/supply-voucher')?>">召回券补发</a></li>
                                <li><a href="<?= Url::toRoute('operation/give-voucher')?>">发放优惠券</a></li>
                                <li><a href="<?= Url::toRoute('operation/revert-order')?>">订单回滚</a></li>
                                <li><a href="<?= Url::toRoute('operation/push-message')?>">消息推送</a></li>
                                <li><a href="<?= Url::toRoute('operation/un-bind-wechat')?>">微信解绑</a></li>
                                <li><a href="<?= Url::toRoute('operation/reset-password')?>">SA密码重置</a></li>
                                <li><a href="<?= Url::toRoute('operation/send-sms')?>">补发短信</a></li>
                                <li><a href="<?= Url::toRoute('car/car-cities')?>">开通城市列表</a></li>
                            </ul>
                        </li>
                        <li class="accordion">
                            <a href="#"><i class="glyphicon glyphicon-user"></i><span>用户中心</span></a>
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="<?= Url::toRoute('customer/index')?>">用户信息</a></li>
                                <li><a href="<?= Url::toRoute('customer/car-list')?>">车辆信息</a></li>
                                <li><a href="<?= Url::toRoute('customer/order-list')?>">订单信息</a></li>
                                <li><a href="<?= Url::toRoute('customer/voucher-list')?>">优惠券信息</a></li>
                                <li><a href="<?= Url::toRoute('customer/wallet-account')?>">钱包信息</a></li>
                            </ul>
                        </li>
                        <li class="accordion">
                            <a href="#"><i class="glyphicon glyphicon-th-large"></i><span>店铺中心</span></a>
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="<?= Url::toRoute('shop/index')?>">店铺信息</a></li>
                                <li><a href="<?= Url::toRoute('order/order-search')?>">订单信息</a></li>
                            </ul>
                        </li>
                        <li class="accordion">
                            <a href="#"><i class="glyphicon glyphicon-cloud"></i><span>推广中心</span></a>
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="<?= Url::toRoute('order/cps-order')?>">订单查询</a></li>
                            </ul>
                        </li>
                        <li class="accordion">
                            <a href="#"><i class="glyphicon glyphicon-signal"></i><span>统计报表</span></a>
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="<?= Url::toRoute('report/market-cps')?>">营销推广</a></li>
                                <li><a href="<?= Url::toRoute('soa/soa')?>">营销短信</a></li>
                                <li><a href="<?= Url::toRoute('soa/soa')?>">订单抢单</a></li>
                                <li><a href="<?= Url::toRoute('soa/soa')?>">店铺订单</a></li>
                                <li><a href="<?= Url::toRoute('soa/soa')?>">CPS邀请</a></li>
                                <li><a href="<?= Url::toRoute('soa/soa')?>">订单奖励</a></li>
                                <li><a href="<?= Url::toRoute('soa/soa')?>">奖励提现</a></li>
                            </ul>
                        </li>
                        <li class="accordion">
                            <a href="#"><i class="glyphicon glyphicon-star"></i><span>设备管理</span></a>
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="<?= Url::toRoute('mobile/index')?>">手机管理</a></li>
                            </ul>
                        </li>
                        <li class="accordion">
                            <a href="#"><i class="glyphicon glyphicon-list"></i><span>SOA管理</span></a>
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="<?= Url::toRoute('soa/index')?>">项目管理</a></li>
                                <li><a href="<?= Url::toRoute('soa/soa')?>">接口管理</a></li>
                                <li><a href="<?= Url::toRoute('soa/project-type')?>">发布项目类型</a></li>
                                <li><a href="<?= Url::toRoute('soa/publish-project')?>">发布项目管理</a></li>
                                <li><a href="<?= Url::toRoute('assign/plan-index')?>">测试列表</a></li>
                                <li><a href="<?= Url::toRoute('soa/publish')?>">发布通知</a></li>
                            </ul>
                        </li>
                        <li class="accordion">
                            <a href="#"><i class="glyphicon glyphicon-list"></i><span>自动化中心</span></a>
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="http://itest.lcbint.com.local/func_test.php">接口自动化</a></li>
                            </ul>
                        </li>
                        <?php if(Yii::$app->user->can('User')){?>
                            <li class="accordion">
                                <a href="#"><i class="glyphicon glyphicon-cog"></i><span>权限管理</span></a>
                                <ul class="nav nav-pills nav-stacked">
                                    <?php if(Yii::$app->user->can('user/index')){?>
                                        <li><a href="<?= Url::toRoute('user/index')?>">用户管理</a></li>
                                    <?php }?>
                                    <?php if(Yii::$app->user->can('user/role')){?>
                                        <li><a href="<?= Url::toRoute('user/role')?>">角色管理</a></li>
                                    <?php }?>
                                </ul>
                            </li>
                        <?php }?>
                </div>
            </div>
        </div>
        <!--/span-->
        <!-- left menu ends -->
        <div id="content" class="col-lg-10 col-sm-10" style="min-height: 500px;">
            <!-- content starts -->
            <?= $content ?>
            <!-- content ends -->
        </div><!--/#content.col-md-0-->
    </div><!--/fluid-row-->
    <hr>
    <footer class="row">
        <p class="col-md-9 col-sm-9 col-xs-12 copyright">Powered by: 上海享途网络科技有限公司 V<?=  Yii::$app->params['version']?>  &copy; <a href="http://www.lechebang.com" target="_blank">乐车邦</a> 2015 - <?=date('Y')?></p>
    </footer>
</div><!--/.fluid-container-->
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
