<?php
/**
 * @link http://mqc.lechebang.com
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/8/17
 * Time: 10:59
 */
?>
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
        <th>取消</th>
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
    foreach ($model as $m) {
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
            <td class="center"><?= $m['cancel'] ?></td>
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
        <td class="center"><?= $totalCancel ?></td>
        <td class="center"><?= $totalJoin + $totalRegister + $totalLogin + $totalCreate + $totalPay + $totalArrived + $totalFinished + $totalCancel?></td>
    </tr>
    </tbody>
</table>
