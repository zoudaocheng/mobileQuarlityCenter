<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/8/2
 * Time: 11:15
 */

namespace app\models;


use yii\db\ActiveRecord;

class Publish extends ActiveRecord
{
    public static function tableName()
    {
        return 'publish';
    }

    public function attributeLabels()
    {
        return [
            'id' => '编号',
            'type_id' => '项目类型',
            'project_id' => '发布项目',
            'title' => '发布摘要',
            'headline' => '发布标题',
            'content' => '发布明细',
            'status' => '邮件状态', //0:未发送,1:已发送
            'created_at' => '添加时间',
            'updated_at' => '更新时间',
            'user_id' => '添加用户',
            'lift_time' => '提测时间',
            'lifter' => '提测人员',
            'publish_status' => '已发布',
            'version' => '版本号'
        ];
    }

    public function rules()
    {
        return [
            [['content','version','headline','type_id','project_id','lift_time','publish_status','lifter'],'required'],
            [['content','headline','version','lift_time','lifter'],'trim']
        ];
    }

    public function getUser() {
        return $this->hasOne(User::className(),['id' => 'user_id']);
    }

    public function getProject() {
        return $this->hasOne(LcbProjectPublish::className(),['id' => 'project_id']);
    }

    public function getProjectType(){
        return $this->hasOne(LcbProjectType::className(),['id' => 'type_id']);
    }
}