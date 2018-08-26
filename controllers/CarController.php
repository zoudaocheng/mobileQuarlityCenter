<?php
/**
 * 汽车控制器
 * @link http://mqc.lcbint.com
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/7/14
 * Time: 15:13
 */

namespace app\controllers;


use app\models\CarCity;
use yii\data\ActiveDataProvider;

class CarController extends CommController
{
    public function actionCarCities($page = 1)
    {
        $query = CarCity::find();
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC,
                ]
            ],
        ]);
        return $this->render('car-city',['provider' => $provider]);
    }
}