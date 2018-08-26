<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/8/2
 * Time: 14:59
 */

namespace app\commands;


use app\components\Soa;
use app\controllers\CommController;
use app\models\LiftPlan;
use app\models\MktChannelType;
use app\models\MktUserBehavior;
use app\models\Publish;
use yii\console\Controller;
use yii\data\ActiveDataProvider;

class CronController extends Controller
{
    public function actionPublishRun() {
        $flag = false;
        $message = \Yii::$app->mailer;
        $content = LiftPlan::find()->where(['email_status' => 0,'publish_status' => 1])->all();
        if($content) {
            $message = $message->compose('publish-report',['contents' => $content]);
            //$message->setTo('product@lechebang.com');
            $message->setTo('kelvin.zou@qq.com');
            //$message->setCc(['luwubo@lechebang.com','chenmeiming@lechebang.com','jinxiaochi@lechebang.com','shenxiaoguang@lechebang.com','yujie@lechebang.com','zhangbiao@lechebang.com','zhangjian@lechebang.com','yunying@lechebang.com','xuliangyu@lechebang.com','qa@lechebang.com','lishihong@lechebang.com']);
            $message->setSubject('发布通知');
            do{
                if($message->send())
                    $flag = true;
            }while(!$flag);
            //LiftPlan::updateAll(['email_status' => 1,'updated_at' => time()],['email_status' => 0,'publish_status' => 1]);
        }
    }

    public function actionMarketCpsReport($day = 5) {
        $flag = false;
        $message = \Yii::$app->mailer;
        $cps = \Yii::$app->params['Cps'];
        $content = [];
        switch ($day) {
            case 2: //最近7天
                $dates = Soa::Dates(date('Y-m-d',(time()-8*86400)),date('Y-m-d',(time()-86400)));
                $emailTitle = '最近一周各渠道营销推广报表';
                break;
            case 3: //本月
                $first = date('Y-m',time()).'-'.'-01';
                $dates = Soa::Dates($first,date('Y-m-d',(time()-86400)));
                $emailTitle = '本月各渠道营销推广报表';
                break;
            case 4: //上个月
                $first = date('Y',time()).'-'.(date('m',time())-1).'-01';
                $dates = Soa::Dates($first,date('Y-m-d',strtotime("$first +1 month -1 day")));
                $emailTitle = '上月各渠道营销推广报表';
                break;
            default: //昨天
                $dates = Soa::Dates(date('Y-m-d',(time()-86400)),date('Y-m-d',(time()-86400)));
                $emailTitle = '昨日各渠道营销推广报表';
        }

        foreach ($cps as $data) {
            $listReport = [];
            $channel['channel'] = MktChannelType::findOne($data['site_id'])->name;
            //获取订单
            $query = MktUserBehavior::find();
            $query->andFilterWhere(['activity_id' => $data['activity_id']]);
            $query->andFilterWhere(['alliance_id' => $data['alliance_id']]);
            $query->andFilterWhere(['site_id' => $data['site_id']]);
            $query->andFilterWhere(['channel_id' => $data['channel_id']]);
            $query->andFilterWhere(['> ','order_id',0]);
            $startTime = strtotime($dates[0] . ' 00:00:00')*1000;
            $query->andFilterWhere(['>', 'created_time', $startTime]);
            $endTime = strtotime($dates[count($dates)-1] . ' 23:59:59')*1000+999; //必须+999,否则最后一秒钟的数据有可能会查询不到
            $query->andFilterWhere(['<', 'created_time', $endTime]);
            $query->groupBy(['order_id']);
            $channel['orders'] =  new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 1000000,
                ],
                'sort' => [
                    'defaultOrder' => [
                        'order_id' => SORT_DESC,
                    ]
                ],
            ]);

            //获取报表
            foreach ($dates as $date){
                $report['date'] = $date;
                $baseQuery = MktUserBehavior::find()->select(['order_id']);
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
            $channel['reports'] = $listReport;
            array_push($content,$channel);
        }
        if($content) {
            $message = $message->compose('market-cps-report',['contents' => $content]);
            $message->setTo(['wangdan@lechebang.com']);
            $message->setCc(['yunying@lechebang.com','qa@lechebang.com','chenjing@lechebang.com']);
            $message->setSubject($emailTitle);
            do{
                if($message->send())
                    $flag = true;
            }while(!$flag);
        }
    }

    /**
     * 开发人员提测邮件提醒 - 定时任务
     */
    public function actionLiftTestNotice(){
        $query = LiftPlan::find();
        $time = date('Y-m-d',time());
        $query->andWhere(['IS NOT','pre_lift_time',null]);
        $query->andWhere(['IS','lift_time',null]);
        $query->andFilterWhere(['<',"UNIX_TIMESTAMP(pre_lift_time)",strtotime($time)]);
        $query->andFilterWhere(['publish_status' => 0,'plan_type' => 1]);
        $plans = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 1000,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);
        if($plans->models){
            foreach ($plans->models as $plan){
                $email = [
                    'content' => [
                        'version' => $plan->version,
                        'pre_lift_time' => $plan->pre_lift_time,
                        'developer' => $plan->lifter->realname,
                    ],
                    'subject' => '[项目延期未提测]'.$plan->version,
                    'compose' => 'lift-test-notice',
                    'receiver' => [$plan->lifter->username.'@lechebang.com',$plan->projectType->projectManager['username'].'@lechebang.com'],
                    'cc' => ['qa@lechebang.com','tech_management@lechebang.com']
                ];
                CommController::email($email);
            }
        }
    }
}