<?php
/**
 * Created by PhpStorm.
 * User: zoudaocheng
 * Date: 17/10/30
 * Time: 15:02
 */

namespace app\controllers;


use app\models\Customer;
use app\models\WalletAccount;
use app\models\WalletDetailLog;
use yii\data\ActiveDataProvider;

class WalletController extends CommController
{
    public function actionWalletAccount($page = 1){
        $data = \Yii::$app->request->get('Customer');
        $customer = Customer::findByMobile($data['mobile']);
        if ($customer) {
            $query = WalletDetailLog::find()
                ->andFilterWhere(['user_id' => $customer->id]);
            $provider = new ActiveDataProvider([
                'query'      => $query,
                'pagination' => [
                    'pageSize' => 20,
                ],
                'sort'       => [
                    'defaultOrder' => [
                        'create_time' => SORT_DESC,
                    ]
                ],
            ]);
            $wallet = WalletAccount::findOne(['user_id' => $customer->id]);
            return $this->renderPartial('wallet', ['provider' => $provider,'account' => $wallet]);
        }else {
            $result['status'] = 0;
            $result['message'] = '用户不存在!';
            return $this->renderJson($result);
        }
    }
}