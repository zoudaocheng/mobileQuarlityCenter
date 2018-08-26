<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2017/4/25
 * Time: 14:54
 */

namespace app\controllers;


use app\components\Jira;
use app\models\LcbProjectType;
use app\models\LiftDetail;
use app\models\LiftPlan;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class AssignController extends CommController
{
    public function actionPlanIndex() {
        $listProjectType = ArrayHelper::map(LcbProjectType::find()->all(),'id','name');
        $listQa = ArrayHelper::map(User::find()->all(),'id','realname');
        $listDeveloper = ArrayHelper::map(User::find()->all(),'id','realname');
        return $this->render('/plan/plan-index',[
                'model' => new LiftPlan(),
                'listProjectType' => $listProjectType,
                'listQa' => $listQa,
                'listDeveloper' => $listDeveloper
            ]
        );
    }

    public function actionPlanList($page = 1) {
        $query = LiftPlan::find();
        if (($type_id = \Yii::$app->request->get('type_id')) != 0) {
            $query->andFilterWhere(['=', 'type_id', $type_id]);
        }
        if ($startTime = strtotime(\Yii::$app->request->get('createtime_start')) !== false) {
            $startTime = strtotime(\Yii::$app->request->get('createtime_start') . ' 00:00:00');
            $query->andFilterWhere(['>', 'created_time', $startTime]);
        }
        if ($endTime = strtotime(\Yii::$app->request->get('createtime_end')) !== false) {
            $endTime = strtotime(\Yii::$app->request->get('createtime_end') . ' 23:59:59');
            $query->andFilterWhere(['<', 'created_time', $endTime]);
        }
        $provider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => [
                    'pageSize' => 20,
                ],
                'sort' => [
                    'defaultOrder' => [
                        'publish_status' => SORT_ASC,
                        'updated_time' => SORT_DESC,
                    ]
                ],
            ]
        );
        return $this->renderPartial('/plan/plan-list', ['provider' => $provider]);
    }

    public function actionLiftPlanEdit() {
        $data = \Yii::$app->request->post('LiftPlan');
        $version = Jira::convertVersion($data['version']);
        $_version = Jira::getVersionInfo($version);
        $result = array();
        if (is_numeric($data['id']) && $data['id'] > 0) {
            $model = LiftPlan::findOne($data['id']);
            if (!$model) {
                $result['status'] = 0;
                $result['message'] = '未找到该记录';
            }
        } else {
            $model = new LiftPlan();
            $model->version_id = $_version?$_version->id:'NULL';
            $model->version_uri = $_version?$_version->self:'NULL';
            $model->pre_lift_time = (isset($_version->startDate) && $_version->startDate)?$_version->startDate:'NULL';
            $model->pre_publish_time = (isset($_version->releaseDate) && $_version->releaseDate)?$_version->releaseDate:'NULL';
            $model->version_description = isset($_version->description)?$_version->description:'无';
            $model->created_time = time();
            $model->updated_time = time();
        }
        if ($model->load(\Yii::$app->request->post())) {
            $model->updated_time = time();
            if ($model->save()) {
                $result['status'] = 1;
                $result['message'] = '保存成功';
            }
        }
        $errors = $model->getFirstErrors();
        if ($errors) {
            $result['status'] = 0;
            $result['message'] = current($errors);
        }
        return $this->renderJson($result);
    }

    public function actionLiftDetail() {
        $query = LiftDetail::find();
        if($plan_id = \Yii::$app->request->get('plan_id')) {
            $query->andFilterWhere(['plan_id' => $plan_id]);
        }
        $provider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => [
                    'pageSize' => 50,
                ],
                'sort' => [
                    'defaultOrder' => [
                        'id' => SORT_DESC,
                    ]
                ],
            ]
        );
        return $this->renderPartial('/plan/lift-list', ['provider' => $provider]);
    }
}