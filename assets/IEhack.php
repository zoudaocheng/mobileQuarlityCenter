<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/4/29
 * Time: 11:20
 */

namespace app\assets;

use yii\web\AssetBundle;
class IEhack extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $jsOptions = ['condition' => 'lt IE9','position' => \yii\web\View::POS_HEAD];
    public $js = [
        'scripts/html5.min.js',
    ];
}