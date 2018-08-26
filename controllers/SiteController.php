<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\User;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['get','post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'width' => 120,
                'height' => 40,
                'padding' => 0,
                'minLength' => 4,
                'maxLength' => 4,
            ],
        ];
    }

    public function actionIndex()
    {
        Yii::$app->controller->layout = false; //命令行不执行
        if(!Yii::$app->user->isGuest){
            $this->redirect(Url::toRoute('admin/index')); //已经登录用户直接跳转
        }
        $model = new User(['scenario' => 'login']);
        if($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(Url::toRoute('admin/index'));
        }else{
            return $this->render('index',[
                    'model' => $model
                ]
            );
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
}
