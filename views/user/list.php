<?php
/**
 * @link http://mqc.lcbint.com
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/5/10
 * Time: 13:32
 */
use yii\helpers\Html;
use yii\helpers\Json;
?>
<table id="" class="table table-striped table-bordered responsive">
    <thead>
    <tr>
        <th>姓名</th>
        <th>用户名</th>
        <th>角色</th>
        <th>创建时间</th>
        <th>最后登录</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($model as $user) {
        $user = $user->toArray();
        ?>
        <tr>
            <td class="center"><?= Html::encode($user['realname']) ?></td>
            <td class="center"><?= Html::encode($user['username']) ?></td>
            <td class="center"><?= implode(',', $user['roles'])?></td>
            <td class="center"><?= date('Y-m-d H:i:m',$user['created_at'])?></td>
            <td class="center"><?= date('Y-m-d H:i:m',$user['updated_at'])?></td>
            <td class="center"><?= $user['enabled'] == 1 ? '<span class="label-success label label-default">正常</span>' : '<span class="label-default label">禁用</span>' ?></td>
            <td class="center">
                <?php if(Yii::$app->user->can('user/edit')){?>
                    <a class="btn btn-info" href="#" data-toggle="modal" data-target="#myDialog" data-data='<?= Json::encode($user) ?>'>
                        <i class="glyphicon glyphicon-edit icon-white"></i>
                        编辑
                    </a>
                <?php }?>
                <?php if(Yii::$app->user->can('user/del')){?>
                    <a class="btn btn-danger" href="javascript:void(0);" onclick="del(<?= $user['id'] ?>)">
                        <i class="glyphicon glyphicon-trash icon-white"></i>
                        删除
                    </a>
                <?php }?>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>