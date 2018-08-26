<?php
/**
 * Created by PhpStorm.
 * User: zoudaocheng
 * Date: 17/10/29
 * Time: 11:37
 * 提测检测日志MODEL
 */

namespace app\models;


use app\components\Jira;
use yii\db\ActiveRecord;

class LiftCheckLog extends ActiveRecord
{
    public static function tableName()
    {
        return 'lift_check_log';
    }

    public function attributeLabels()
    {
        return
            [
                'id' => 'check id',
                'type_id' => '项目类型',
                'project_id' => '项目',
                'plan_id' => '计划ID',
                'version_name' => '版本号',
                'version_id' => '版本ID',
                'version_link' => '版本链接',
                'version_description' => '版本描述',
                'issues' => 'ISSUES',
                'build_no' => '编译号',
                'lift_status' => '提测状态', //1成功，0失败
                'lifter' => '提测人员',
                'remark' => '备注信息',//用于描述提测失败时的原因
                'created_time' => '创建时间',
                'updated_time' => '更新时间'
            ];
    }

    public function getLiftPlan(){
        return $this->hasOne(LiftPlan::className(),['id' => 'plan_id']);
    }

    public static function insertLiftCheckLog($tmpVersion = '',$liftStatus = 1,$buildNo = 0,$lifter = 1,$remark = 'lift version check success!'){
        $_version = Jira::convertVersion($tmpVersion);//对版本号兼容(与jira中版本对应)
        $version = Jira::getVersionInfo($_version);//获取版本号详情
        $mqcVersion = Jira::getVersionForMQC($tmpVersion);//获取mqc及jira中版本对应关系
        $issues = Jira::search(['fixVersion' => $mqcVersion->jVersion]); //获取查issue的方法
        if ($version){
            $plan = LiftPlan::find()->andFilterWhere(['like','version',$mqcVersion->project])->andFilterWhere(['version_id' => $version->id])->one();//查看版本号或版本ID是否已经存在
            //判断之前是否有提测检测记录，并且没有记录planId的
            $logs = LiftCheckLog::find()
                ->andFilterWhere(['version_id' => $version->id])
                ->andFilterWhere(['like','version_name',$mqcVersion->project])
                ->andWhere(['is','plan_id',null])->all();

            if ($logs && $plan){
                foreach ($logs as $log){
                    LiftCheckLog::updateAll(['plan_id' => $plan->id],['id' => $log->id]);
                }
            }
        }else{
            $plan = null;
        }

        $project = LcbProjectPublish::findOne(['description' => $mqcVersion->project]);//查看项目信息
        $relation = new LiftCheckLog();
        $relation->type_id = $project->type_id?$project->type_id:null;
        $relation->plan_id = $plan?$plan->id:null;
        $relation->project_id = $project->id?$project->id:null;
        $relation->version_name = $version?$mqcVersion->mVersion:null; //记录的是mqc中的版本号
        $relation->version_id = $version?$version->id:null;
        $relation->version_link = $version?Jira::versionToUri($version->self):null; //需要版本号的链接
        $relation->version_description = ($version && isset($version->description))?$version->description:null;
        $relation->issues = $issues;
        $relation->build_no = $buildNo;
        $relation->lift_status = $liftStatus;
        $relation->lifter = $lifter;
        $relation->remark = $remark;
        $relation->created_time = time();
        $relation->updated_time = time();

        $relation->save();
        return true;
    }
}