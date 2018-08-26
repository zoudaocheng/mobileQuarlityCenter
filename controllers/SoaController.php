<?php

/**
 * @link http://mqc.lcbint.com
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/6/27
 * Time: 14:51
 */

namespace app\controllers;


use app\models\LcbProjectPublish;
use app\models\LcbProjectType;
use app\models\Publish;
use app\models\Soa;
use app\models\SoaForm;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;

class SoaController extends CommController
{
    public function actionIndex(){
        $listProject = ArrayHelper::map(SoaForm::find()->all(),'id','project_name');
        return $this->render('index',[
                'model' => new SoaForm(),
                'listProject' => $listProject
            ]
        );
    }

    public function actionProjectEdit(){
        $data = \Yii::$app->request->post('SoaForm');
        $result = array();
        if(is_numeric($data['id']) && $data['id'] > 0){
            $model = SoaForm::findOne($data['id']);
            if(!$model){
                $result['status'] = 0;
                $result['message'] = '未找到该项目';
            }
        }else{
            $model = new SoaForm();
            $model->created_at = time();
        }
        if($model->load(\Yii::$app->request->post())){
            $model->updated_at = time();
            if($model->save()) {
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

    public function actionProjectList($page = 1){
        $query = SoaForm::find();
        $query->andFilterWhere(['is_valid' => \Yii::$app->request->get('is_valid')]);//是否有效的项目
        //添加的时间范围$startTime to $endTime
        if ($startTime = strtotime(\Yii::$app->request->get('createtime_start')) !== false) {
            $startTime = strtotime(\Yii::$app->request->get('createtime_start') . ' 00:00:00');
            $query->andFilterWhere(['>', 'created_at', $startTime]);
        }
        if ($endTime = strtotime(\Yii::$app->request->get('createtime_end')) !== false) {
            $endTime = strtotime(\Yii::$app->request->get('createtime_end') . ' 23:59:59');
            $query->andFilterWhere(['<', 'created_at', $endTime]);
        }
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ],
        ]);
        return $this->renderPartial('project-list', ['provider' => $provider]);
    }

    public function actionProjectDelete($id){
        $result = array();
        $model = SoaForm::findOne($id);
        if ($model){
            $model->delete();
            $result['status'] = 1;
            $result['message'] = '删除成功';
        } else {
            $result['status'] = 0;
            $result['message'] = '未找到ID为' . $id . '的项目';
        }
        return $this->renderJson($result);
    }

    public function actionSoaDelete($id) {
        $result = array();
        $model = Soa::findOne($id);
        if ($model){
            $model->delete();
            $result['status'] = 1;
            $result['message'] = '删除成功';
        } else {
            $result['status'] = 0;
            $result['message'] = '未找到ID为' . $id . '的SOA接口';
        }
        return $this->renderJson($result);
    }

    public function actionSoa() {
        $listProject = ArrayHelper::map(SoaForm::find()->all(),'id','project_name');
        return $this->render('soa-index',[
                'model' => new Soa(),
                'listProject' => $listProject
            ]
        );
    }

    public function actionSoaEdit() {
        $data = \Yii::$app->request->post('Soa');
        $result = array();
        if (is_numeric($data['id']) && $data['id'] > 0) {
            $model = Soa::findOne($data['id']);
            if (!$model) {
                $result['status'] = 0;
                $result['message'] = '未找到该记录';
            }
        } else {
            $model = new Soa();
            $model->request_param = json_encode(\app\components\Soa::name(json_decode($data['mock'])));
            $model->response_param = json_encode(\app\components\Soa::name(json_decode($data['response'])));
        }
        if ($model->load(\Yii::$app->request->post())) {
            $model->request_field = json_encode(\app\components\Soa::field(json_decode($data['mock'])));
            $model->response_field = json_encode(\app\components\Soa::field(json_decode($data['response'])));
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

    public function actionSoaList() {
        $query = Soa::find();
        $query->andFilterWhere(['is_valid' => \Yii::$app->request->get('is_valid')]);//是否有效的接口
        $query->andFilterWhere(['project_id' => \Yii::$app->request->get('project_id')]); //是否选择了项目
        if ($startTime = strtotime(\Yii::$app->request->get('createtime_start')) !== false) {
            $startTime = strtotime(\Yii::$app->request->get('createtime_start') . ' 00:00:00');
            $query->andFilterWhere(['>', 'created_at', $startTime]);
        }
        if ($endTime = strtotime(\Yii::$app->request->get('createtime_end')) !== false) {
            $endTime = strtotime(\Yii::$app->request->get('createtime_end') . ' 23:59:59');
            $query->andFilterWhere(['<', 'created_at', $endTime]);
        }
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ],
        ]);
        return $this->renderPartial('soa-list', ['provider' => $provider]);
    }

    public function actionPublish() {
        $listProjectType = ArrayHelper::map(LcbProjectType::find()->all(),'id','name');
        return $this->render('publish-index',[
                'model' => new Publish(),
                'listProjectType' => $listProjectType
            ]
        );
    }

    public function actionPublishEdit() {
        $data = \Yii::$app->request->post('Publish');
        $result = array();
        if (is_numeric($data['id']) && $data['id'] > 0) {
            $model = Publish::findOne($data['id']);
            if (!$model) {
                $result['status'] = 0;
                $result['message'] = '未找到该记录';
            }
        } else {
            $model = new Publish();
            $model->created_at = time();
            $model->user_id = \Yii::$app->user->id;
        }
        if ($model->load(\Yii::$app->request->post())) {
            $model->updated_at = time();
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

    public function actionPublishRun($id) {
        $result = array();
        $message = \Yii::$app->mailer;
        $model = Publish::findOne($id);
        $message = $message->compose('publish-single-report',['content' => $model]);
        $message->setTo('kelvin.zou@qq.com');
        $message->setSubject('发布通知');
        if ($message->send()) {
            $model->status = 1;
            $model->save();
            $result['status'] = 1;
            $result['message'] = '发送成功';
        } else {
            $result['status'] = 0;
            $result['message'] = '发送失败';
        }
        return $this->renderJson($result);
    }

    public function actionPublishList($page = 1) {
        $query = Publish::find();
        if (($type_id = \Yii::$app->request->get('type_id')) != 0) {
            $query->andFilterWhere(['=', 'type_id', $type_id]);
        }
        if ($startTime = strtotime(\Yii::$app->request->get('createtime_start')) !== false) {
            $startTime = strtotime(\Yii::$app->request->get('createtime_start') . ' 00:00:00');
            $query->andFilterWhere(['>', 'created_at', $startTime]);
        }
        if ($endTime = strtotime(\Yii::$app->request->get('createtime_end')) !== false) {
            $endTime = strtotime(\Yii::$app->request->get('createtime_end') . ' 23:59:59');
            $query->andFilterWhere(['<', 'created_at', $endTime]);
        }
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ],
        ]);
        return $this->renderPartial('publish-list', ['provider' => $provider]);
    }

    public function actionProjectTypeEdit(){
        $data = \Yii::$app->request->post('LcbProjectType');
        $result = array();
        if (is_numeric($data['id']) && $data['id'] > 0) {
            $model = LcbProjectType::findOne($data['id']);
            if (!$model) {
                $result['status'] = 0;
                $result['message'] = '未找到该记录';
            }
        }else {
            $model = new LcbProjectType();
            $model->created_at = time();
            $model->updated_at = time();
            $model->user_id = \Yii::$app->user->id;
        }
        if ($model->load(\Yii::$app->request->post())) {
            $model->updated_at = time();
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

    public function actionPublishProjectEdit() {
        $data = \Yii::$app->request->post('LcbProjectPublish');
        $result = array();
        if (is_numeric($data['id']) && $data['id'] > 0) {
            $model = LcbProjectPublish::findOne($data['id']);
            if (!$model) {
                $result['status'] = 0;
                $result['message'] = '未找到该记录';
            }
        } else {
            $model = new LcbProjectPublish();
            $model->created_at = time();
            $model->updated_at = time();
            $model->user_id = \Yii::$app->user->id;
        }
        if ($model->load(\Yii::$app->request->post())) {
            $model->updated_at = time();
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

    public function actionProjectTypeList($page = 1) {
        $query = LcbProjectType::find();
        //$query->groupBy(['status']);
        if ($startTime = strtotime(\Yii::$app->request->get('createtime_start')) !== false) {
            $startTime = strtotime(\Yii::$app->request->get('createtime_start') . ' 00:00:00');
            $query->andFilterWhere(['>', 'created_at', $startTime]);
        }
        if ($endTime = strtotime(\Yii::$app->request->get('createtime_end')) !== false) {
            $endTime = strtotime(\Yii::$app->request->get('createtime_end') . ' 23:59:59');
            $query->andFilterWhere(['<', 'created_at', $endTime]);
        }
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ],
        ]);
        return $this->renderPartial('project-type-list', ['provider' => $provider]);
    }

    public function actionPublishProjectList($page = 1) {
        $query = LcbProjectPublish::find();
        if (($type_id = \Yii::$app->request->get('type_id')) != 0) {
            $query->andFilterWhere(['=', 'type_id', $type_id]);
        }
        if ($startTime = strtotime(\Yii::$app->request->get('createtime_start')) !== false) {
            $startTime = strtotime(\Yii::$app->request->get('createtime_start') . ' 00:00:00');
            $query->andFilterWhere(['>', 'created_at', $startTime]);
        }
        if ($endTime = strtotime(\Yii::$app->request->get('createtime_end')) !== false) {
            $endTime = strtotime(\Yii::$app->request->get('createtime_end') . ' 23:59:59');
            $query->andFilterWhere(['<', 'created_at', $endTime]);
        }
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ],
        ]);
        return $this->renderPartial('publish-project-list', ['provider' => $provider]);
    }

    public function actionProjectType() {
        return $this->render('project-type-index',[
                'model' => new LcbProjectType(),
            ]
        );
    }

    public function actionPublishProject(){
        $listProjectType = ArrayHelper::map(LcbProjectType::find()->all(),'id','name');
        return $this->render('publish-project-index',[
                'model' => new LcbProjectPublish(),
                'listProjectType' => $listProjectType
            ]
        );
    }

    /**
     * 获取发布项目类型
     */
    public function actionGetPublishProject() {
        $query = LcbProjectPublish::find();
        $query->andFilterWhere(['type_id' => \Yii::$app->request->get('type_id')]);
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
}