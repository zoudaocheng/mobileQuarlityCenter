<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2017/4/12
 * Time: 17:17
 * 测试计划
 */

namespace app\models;


use yii\db\ActiveRecord;

class LiftPlan extends ActiveRecord
{
    public static function tableName()
    {
        return 'lift_plan';
    }

    public function attributeLabels()
    {
        return [
            'id' => '计划编号',
            'type_id' => '项目类型',
            'project_id' => '项目名称',
            'version' => '预计版本',
            'version_id' => '版本ID',
            'version_uri' => '版本URL',
            'version_description' => '版本描述',
            'plan_type' => '计划类型',//1:计划内；-1：计划外
            'pre_lift_time' => '预计提测',//预计提测时间
            'lift_time' => '实际提测',
            'developer' => '开发人员',//提测人员
            'pre_publish_time' => '预计发布',//预计发布时间
            'publish_time' => '实际发布',//实际发布时间
            'qa' => '测试人员',
            'publish_status' => '发布状态',
            'email_status' => '邮件状态',
            'created_time' => '添加时间',
            'updated_time' => '更新时间'
        ];
    }

    public function rules()
    {
        return [
            [['type_id','project_id','version','version_id','version_uri','version_description','plan_type','pre_lift_time','developer','pre_publish_time','qa','functions','addition_functions'],'safe']];
    }

    public function getLiftDetails() {
        return $this->hasMany(LiftDetail::className(),['plan_id' => 'id']);
    }

    public function getProject() {
        return $this->hasOne(LcbProjectPublish::className(),['id' => 'project_id']);
    }

    public function getProjectType() {
        return $this->hasOne(LcbProjectType::className(),['id' => 'type_id']);
    }

    public function getTester() {
        return $this->hasOne(User::className(),['id' => 'qa']);
    }

    public function getLifter() {
        return $this->hasOne(User::className(),['id' => 'developer']);
    }
}