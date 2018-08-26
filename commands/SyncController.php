<?php
/**
 * Created by PhpStorm.
 * User: daocheng
 * Date: 17-9-6
 * Time: 下午5:31
 */

namespace app\commands;


use app\components\Jira;
use app\models\LcbProjectPublish;
use app\models\LcbProjectType;
use app\models\LiftPlan;
use yii\db\Exception;

class SyncController extends CommController
{
    public function actionSyncVersion(){
        $versions = Jira::getVersionPlan();//获取jira中的版本列表
        foreach ($versions as $version){
            if (isset($version->startDate) && $version->startDate && isset($version->releaseDate) && $version->releaseDate && isset($version->description) && $version->description){
                //releaseDate在当前时间之后
                if (strtotime($version->releaseDate.' 23:59:59') >= time()){
                    //判定该版本号是否存在
                    $query = LiftPlan::find()->andFilterWhere(['version_id' => $version->id])->one();
                    $tmp = explode('.',str_replace('_','.',$version->name));//转换为数组
                    if (!$query){
                        foreach ($tmp as $key => $value){
                            if (is_numeric($value))
                                unset($tmp[$key]);
                        }
                        $transaction = \Yii::$app->db->transaction;
                        try{
                            $plan = new LiftPlan();
                            $plan->type_id = LcbProjectType::findOne(['name' => $tmp[0]])->id;
                            if(!LcbProjectPublish::findOne(['description' => implode('.',$tmp)])){
                                //项目不存在时 - 添加项目
                                $project = new LcbProjectPublish();
                                $project->type_id = $plan->type_id;
                                $project->name = '初始化项目名称(新项目待定)';
                                $project->description = implode('.',$tmp);
                                $project->user_id = 1;//默认由管理员添加
                                $project->created_at = time();
                                $project->updated_at = time();
                                $project->save();
                            }
                            $plan->project_id = LcbProjectPublish::findOne(['description' => implode('.',$tmp)])->id;
                            $plan->version = in_array('rn',$tmp)?str_replace('rn','ios',$version->name):$version->name;
                            $plan->version_id = $version->id;
                            $plan->version_uri = $version->self;
                            $plan->version_description = $version->description;
                            if ('soa' == $tmp[0])
                                $plan->qa = 26;//发给贺晨
                            if ('php' == $tmp[0])
                                $plan->qa = 27;//发给冯伟
                            if ('rn' == $tmp[0] || 'h5' == $tmp[0])
                                $plan->qa = 35;//发给阳新宇
                            if ('cpaint' == $tmp[1] || 'duijie' == $tmp[1])
                                $plan->qa = 32;//对接项目发送给黄锦杰
                            $plan->plan_type = 1;
                            $plan->pre_lift_time = $version->startDate;
                            $plan->pre_publish_time = $version->releaseDate;
                            $plan->created_time = time();
                            $plan->updated_time = time();
                            $plan->save();//计划保存
                            /**
                             * 特殊逻辑（react native）版本需要再次提交针对 android 的处理
                             */
                            if (in_array('rn',$tmp)){
                                $plan->version = str_replace('rn','android',$version->name);
                                $plan->save();
                            }
                        }catch (Exception $exc) {
                            $transaction->rollBack();
                        }
                    } else {
                        //如果存在，则判断该版本号是否已经变更 - 版本未发布并且版本号不相同则进行变更版本号,且如果为react native 则后续人工在MQC系统中进行修改版本号
                        if (!$query->publish_status && $version->name != $query->version && !in_array('rn',$tmp)){
                            $query->version = $version->name;
                            $query->version_description = $version->description;
                            $query->pre_lift_time = $version->startDate;
                            $query->pre_publish_time = $version->releaseDate;
                            $query->updated_time = time();
                            $query->save();
                        }
                    }
                }
            }
        }
    }
}