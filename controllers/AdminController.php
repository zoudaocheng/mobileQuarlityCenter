<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/4/28
 * Time: 19:17
 */

namespace app\controllers;

use app\models\User;
use yii\helpers\Url;
class AdminController extends CommController
{
    public function actionIndex(){
        return $this->render('index');
    }

    public function actionProfile(){
        $model = new User(['scenario' => 'editProfile']);
        return $this->render('profile',['model' => $model]);
    }

    public function actionEditProfile(){
        $model = new User(['scenario' => 'editProfile']);
        $result = array();
        if($model->load(\Yii::$app->request->post())){
            if($model->editProfile(\Yii::$app->user->id)){
                $result['status'] = 1;
                $result['message'] = '保存成功，请重新登录';
                $result['url'] = Url::toRoute('site/logout');
            }
        }
        $errors = $model->getFirstErrors();
        if($errors){
            $result['status'] = 0;
            $result['message'] = current($errors);
        }
        return $this->renderJson($result);
    }
}