<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/12/13
 * Time: 16:43
 * 发布项目列表
 */

namespace app\models;


use yii\db\ActiveRecord;

class LcbProjectPublish extends ActiveRecord
{
    public static function tableName()
    {
        return 'lcb_project_publish';
    }

    public function attributeLabels()
    {
        return [
            'id' => '项目编号',
            'type_id' => '项目类型',
            'name' => '项目名称',
            'description' => '项目描述',
            'user_id' => '创建者',
            'created_at' => '添加时间',
            'updated_at' => '更新时间'
        ];
    }

    public function rules()
    {
        return [
            [['type_id','name','user_id','description'],'required'],
            [['name','description'],'trim']
        ];
    }

    /**
     * 获取发布项目类型
     */
    public function getProjectType() {
        return $this->hasOne(LcbProjectType::className(),['id' => 'type_id']);
    }

    public function getUser() {
        return $this->hasOne(User::className(),['id' => 'user_id']);
    }
}