<?php
/**
 * 店铺控制器
 * @link http://mqc.lcbint.com
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/7/14
 * Time: 16:05
 */

namespace app\controllers;


use app\models\CarCity;
use app\models\ShopStore;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class ShopController extends CommController
{
    public function actionIndex() {
        $listPlace = ArrayHelper::map(CarCity::find()->all(),'city_id','name');
        return $this->render('index',[
            'model' => new ShopStore(),
            'listPlace' => $listPlace,
            //'orderStatus' => $this->orderStatus(),
            //'paymentStatus' => $this->paymentStatus(),
           // 'refundStatus' => $this->refundStatus(),
            'accountStatus' => $this->accountStatus(),
        ]);
    }

    public function actionShopList($page = 1) {
        $query = ShopStore::find();
        $data = \Yii::$app->request->get('ShopStore');
        $query->innerJoin('or_settlement_account','or_settlement_account.seller_id = shop_store.id');
        $query->andFilterWhere(['shop_store.deleted' => 0]);
        $query->andFilterWhere(['place_id' => \Yii::$app->request->get('place_id')]);
        $query->andFilterWhere(['or_settlement_account.account_status' => \Yii::$app->request->get('account_status')]);
        $data['store_name']?$query->andFilterWhere(['like','store_name',$data['store_name']]):null;
        $data['store_nick_name']?$query->andFilterWhere(['like','store_nick_name',$data['store_nick_name']]):null;
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC,
                ]
            ],
        ]);
        return $this->renderPartial('shop-list', ['provider' => $provider]);
    }

    public function actionPlaceStore() {
        $query = ShopStore::find();
        $query->andFilterWhere(['place_id' => \Yii::$app->request->get('place_id')]);
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10000,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC,
                ]
            ],
        ]);
        return $this->renderJson($provider->models);
    }

    public function actionShopBrand($page = 1) {
        //TODO
    }
}