<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/4/29
 * Time: 10:50
 * @link http://mqc.lcbint.com
 */
use app\assets\SystemAsset;

SystemAsset::register($this);
$this->title = '平台中心首页';
$this->params['breadcrumbs'][] = 'Dashboard';
?>
<div class="row">
    <div class="box col-md-4">
        <div class="box-inner homepage-box">
            <div class="box-header well">
                <h2><i class="glyphicon glyphicon-th"></i>服务器信息</h2>
            </div>
            <div class="box-content">
                <div class="list-group">
                    <div class="list-group-item">
                        <h4 class="list-group-item-heading">系统类型</h4>
                        <p class="list-group-item-text"><?= php_uname() ?></p>
                    </div>
                    <div class="list-group-item">
                        <h4 class="list-group-item-heading">解译引擎</h4>
                        <p class="list-group-item-text"><?= $_SERVER['SERVER_SOFTWARE'] ?>，Zend：<?= Zend_Version() ?></p>
                    </div>
                    <div class="list-group-item">
                        <h4 class="list-group-item-heading">数据库</h4>
                        <p class="list-group-item-text">MySql:<?= (new yii\db\Query())->select('VERSION()')->one()['VERSION()'] ?></p>
                    </div>
                    <div class="list-group-item">
                        <h4 class="list-group-item-heading">服务器</h4>
                        <p class="list-group-item-text">服务器IP：<?= GetHostByName($_SERVER['SERVER_NAME']) ?></p>
                        <p class="list-group-item-text">程序目录：<?= Yii::$app->BasePath ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="box col-md-4">
        <div class="box-inner  homepage-box">
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-user"></i>程序信息</h2>
            </div>
            <div class="box-content">
                <p><b>程序名称：</b>上海享途网络科技有限公司测试平台</p>
                <p><b>当前版本：</b><?= Yii::$app->params['version'] ?></p>
                <p><b>核心框架：</b><a href="http://www.yiiframework.com/" target="_blank">Yii PHP Framework</a></p>
                <p><b>前端模板：</b><a href="https://github.com/usmanhalalit/charisma" target="_blank">Charisma</a></p>
                <p><b>程序开发：</b><a href="http://mqc.lcbint.com" target="_blank">乐车邦质量保障组</a></p>
                <p><b>开源地址：</b><a href="https://github.com/tangjiandeng/LyHNIMS" target="_blank">GitHub</a></p>
                <p><b>版权所有：</b>乐车邦质量保障组保留该源码的所有权利</p>
            </div>
        </div>
    </div>
    <div class="box col-md-4">
        <div class="box-inner homepage-box">
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-list-alt"></i>乐车邦内部网站</h2>
            </div>
            <div class="box-content">
                <p><b>运营后台：</b><a href="http://operation.lechebang.com" target="_blank">Operation</a></p>
                <p><b>营销后台：</b><a href="http://marketing.operation.lechebang.com" target="_blank">Marketing</a></p>
                <p><b>商家后台：</b><a href="http://admin.lechebang.com" target="_blank">Admin</a></p>
                <p><b>代码仓库：</b><a href="http://git.lcbint.com" target="_blank">Git</a></p>
                <p><b>流程管理：</b><a href="http://jira.lcbint.com" target="_blank">JIRA</a></p>
                <p><b>文档管理：</b><a href="http://confluence.lcbint.com" target="_blank">Confluence</a></p>
                <p><b>用例管理：</b><a href="http://qa.lcbint.com" target="_blank">Testlink</a></p>
            </div>
        </div>
    </div>
</div>
