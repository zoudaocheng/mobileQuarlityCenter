<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/7/11
 * Time: 17:31
 */

namespace app\controllers;


use app\models\Mobile;
use app\models\MobileBrand;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class MobileController extends CommController
{
    public function actionIndex() {
        $listBrand = ArrayHelper::map(MobileBrand::find()->all(),'id','brand');
        $listPlatform = [1 => '安卓',2=> 'iOS',3 => 'Pad'];
        return $this->render('index',[
                'model' => new Mobile(),
                'listBrand' => $listBrand,
                'listPlatform' => $listPlatform
            ]
        );
    }

    public function actionMobileEdit() {
        $data = \Yii::$app->request->post('Mobile');
        $result = array();
        if (is_numeric($data['id']) && $data['id'] > 0) {
            $model = Mobile::findOne($data['id']);
            if (!$model) {
                $result['status'] = 0;
                $result['message'] = '未找到该手机!';
            }
        } else {
            $model = new Mobile();
        }
        if ($model->load(\Yii::$app->request->post())) {
            if ($model->save()) {
                $result['status'] = 1;
                $result['message'] = '保存成功!';
            }
        }
        $errors = $model->getFirstErrors();
        if($errors) {
            $result['status'] = 0;
            $result['message'] = current($errors);
        }
        return $this->renderJson($result);
    }

    public function actionMobileList($page = 1) {
        $query = Mobile::find();
        $platform = \Yii::$app->request->get('platform');
        if ($platform) {
            $query->andFilterWhere(['platform' => $platform]);
        }
        $query->andFilterWhere(['brand' => \Yii::$app->request->get('brand')]);
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
        return $this->renderPartial('mobile-list',['provider' => $provider]);
    }

    public function actionMobileDelete($id) {
        $result = array();
        $model = Mobile::findOne($id);
        if ($model){
            $model->delete();
            $result['status'] = 1;
            $result['message'] = '删除成功';
        } else {
            $result['status'] = 0;
            $result['message'] = '未找到ID为' . $id . '的手机设备!';
        }
        return $this->renderJson($result);
    }
}