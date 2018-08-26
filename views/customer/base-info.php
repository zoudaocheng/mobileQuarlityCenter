<?php
/**
 * @link http://mqc.lcbint.com
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/7/7
 * Time: 16:11
 */
$this->title = '用户基本信息';
?>
<div class="row">
    <div class="box col-md-4">
        <div class="box-inner  homepage-box">
            <?php if(isset($user) && $user) {?>
            <div class="box-content">
                <p><b>用户ID：</b><?=$user['id'] ?></p>
                <p><b>手机号码：</b><?=$user['mobile'] ?></p>
                <p><b>手机状态：</b><?=$user['mobile_status']?'已绑定':'未绑定' ?></p>
                <p><b>用户名：</b><?=$user['login_name']?$user['login_name']:'无' ?></p>
                <p><b>注册时间：</b><?=date('Y-m-d H:i:s',$user['created_time']/1000)?></p>
            </div>
            <?php }?>
        </div>
    </div>
</div>