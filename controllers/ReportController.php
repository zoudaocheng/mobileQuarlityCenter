<?php
/**
 * @link http://mqc.lechebang.com
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/8/17
 * Time: 12:04
 */

namespace app\controllers;

use app\models\MktUserBehavior;

class ReportController extends CommController
{
    public function actionMarketCps() {
        return $this->render('market-cps',[
            'model' => new MktUserBehavior()
        ]);
    }

    public function actionMarketCpsReport() {
        $listReport = [];
        $data = \Yii::$app->request->get('MktUserBehavior');
        $startDate = \Yii::$app->request->get('startdate')?\Yii::$app->request->get('startdate'):date('Y-m-d',time());
        $endDate = \Yii::$app->request->get('enddate')?\Yii::$app->request->get('enddate'):date('Y-m-d',time());
        $dates = $this->Dates($startDate,$endDate);
        foreach ($dates as $date) {
            $report['date'] = $date;
            $baseQuery = MktUserBehavior::find();
            $baseQuery->andWhere(['>','created_time',strtotime($date.' 00:00:00')*1000]);
            $baseQuery->andWhere(['<','created_time',strtotime($date.' 23:59:59')*1000+999]);
            $baseQuery->andFilterWhere(['activity_id' => $data['activity_id']]);
            $baseQuery->andFilterWhere(['alliance_id' => $data['alliance_id']]);
            $baseQuery->andFilterWhere(['site_id' => $data['site_id']]);
            $baseQuery->andFilterWhere(['channel_id' => $data['channel_id']]);
            $tempQuery = clone $baseQuery;
            $report['join'] = $tempQuery->andFilterWhere(['event_type' => 'soa.user.event.join'])->count();
            $tempQuery = clone $baseQuery;
            $report['register'] = $tempQuery->andFilterWhere(['event_type' => 'soa.user.event.register'])->count();
            $tempQuery = clone $baseQuery;
            $report['login'] = $tempQuery->andFilterWhere(['event_type' => 'soa.user.event.login'])->count();
            $tempQuery = clone $baseQuery;
            $report['create'] = $tempQuery->andFilterWhere(['event_type' => 'soa.order.status.1.1.1'])->count();
            $tempQuery = clone $baseQuery;
            $report['pay'] = $tempQuery->andFilterWhere(['event_type' => 'soa.order.status.1.1.2'])->count();
            $tempQuery = clone $baseQuery;
            $report['arrived'] = $tempQuery->andFilterWhere(['event_type' => 'soa.order.status.1.1.3'])->count();
            $tempQuery = clone $baseQuery;
            $report['finished'] = $tempQuery->andFilterWhere(['event_type' => 'soa.order.status.1.1.4'])->count();
            $tempQuery = clone $baseQuery;
            $report['cancel'] = $tempQuery->andFilterWhere(['event_type' => 'soa.order.status.1.1.5'])->count();
            array_push($listReport,$report);
        }
        return $this->renderPartial('market-cps-report',['model' => $listReport]);
    }
}