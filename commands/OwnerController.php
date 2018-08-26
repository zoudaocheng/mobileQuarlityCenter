<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/10/6
 * Time: 19:44
 */

namespace app\commands;


use app\models\Customer;
use app\models\MqcOwner;
use yii\console\Controller;

class OwnerController extends Controller
{
    /**
     * 更新已经注册的手机号码
     */
    public function actionUpdateRegisterStatus () {
        foreach (MqcOwner::find()->distinct('mobile')->where(['registered' => 0])->orderBy('id')->each(1000) as $owner) {
            if(Customer::findByMobile($owner->mobile)){
                $owner->registered = 1;
                $owner->updated_at = date('Y-m-d H:i:s', time());
                $owner->save();
            }
        }
    }
}