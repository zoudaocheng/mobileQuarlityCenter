<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/4/29
 * Time: 11:00
 */
namespace  app\assets;
use yii\web\AssetBundle;

class SystemAsset extends AssetBundle
{
    public $jsOptions = ['async'=>'async'];
    public $js = [
        '//lybiz.sinaapp.com/index.php/home/hnims/index',
    ];
    public $depends = [
        'app\assets\AppAsset'
    ];
}