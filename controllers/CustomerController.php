<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/5/18
 * Time: 13:45
 */

namespace app\controllers;


use app\models\Customer;
use app\models\MyCar;
use app\models\Order;
use app\models\SmsLog;
use yii\data\ActiveDataProvider;

class CustomerController extends CommController
{
    public function actionIndex(){
        $model = new Customer();
        return $this->render('index',['model' => $model]);
    }

    public function actionBaseInfo() {
        $mobile = \Yii::$app->request->get('Customer')['mobile'];
        if ($mobile){
            $rows = Customer::findByMobile($mobile);
            if($rows){
                return $this->renderPartial('base-info',['user' => $rows]);
            } else {
                $result['status'] = 0;
                $result['message'] = '用户不存在';
                return $this->renderJson($result);
            }
        }else{
            return $this->renderPartial('base-info');
        }
    }

    public function actionCarList($page = 1){
        $mobile = \Yii::$app->request->get('Customer')['mobile'];
        if($mobile) {
            $usUser = Customer::findOne(['mobile' => $mobile]);
            if($usUser->id){
                $query = MyCar::find();
                $query->andFilterWhere(['user_id' => $usUser->id]);
                $query->orderBy('deleted');
                $provider = new ActiveDataProvider([
                    'query' => $query,
                    'pagination' => [
                        'pageSize' => 20,
                    ],
                    'sort' => [
                        'defaultOrder' => [
                            'id' => SORT_DESC,
                        ]
                    ],
                ]);
                return $this->renderPartial('car-list',['provider' => $provider]);
            } else {
                $result['status'] = 0;
                $result['message'] = '用户不存在';
                return $this->renderJson($result);
            }
        }else{
            $model = new Customer();
            return $this->render('index',['model' => $model]);
        }
    }

    public function actionSmsLog(){
        $mobile = \Yii::$app->request->get('Customer')['mobile'];
        $query = SmsLog::find();
        $query->andFilterWhere(['like','mobiles',$mobile]);
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);
        return $this->renderPartial('sms-list',['provider' => $provider]);
    }

    public function actionOrderList(){
        $model = new Order();
        return $this->render('index',['model' => $model]);
    }

    public function actionVoucherList () {
        $model = new Customer();
        return $this->render('index',['model' => $model]);
    }

    /**
     * 查询用户钱包
     * @return string
     */
    public function actionWalletAccount() {
        $model = new Customer();
        return $this->render('index',['model' => $model]);
    }
}