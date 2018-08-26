<?php
/**
 * 优惠券控制器
 * @link http://mqc.lechebang.com
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/7/26
 * Time: 17:41
 */

namespace app\controllers;

use app\models\Customer;
use app\models\Voucher;
use yii\data\ActiveDataProvider;

class VoucherController extends CommController
{
    public function actionVoucherList($page = 1)
    {
        $data = \Yii::$app->request->get('Customer');
        if (Customer::findByMobile($data['mobile'])) {
            $query = Voucher::find();
            $query->leftJoin('us_user', 'us_user.id = mkt_voucher.user_id');
            $query->andFilterWhere(['us_user.mobile' => $data['mobile']]);
            $provider = new ActiveDataProvider([
                'query'      => $query,
                'pagination' => [
                    'pageSize' => 20,
                ],
                'sort'       => [
                    'defaultOrder' => [
                        'end_time' => SORT_DESC,
                    ]
                ],
            ]);
            return $this->renderPartial('voucher-list', ['provider' => $provider]);
        }else {
            $result['status'] = 0;
            $result['message'] = '用户不存在!';
            return $this->renderJson($result);
        }
    }
}