<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/12/13
 * Time: 16:37
 * 发布项目类型
 */

namespace app\models;


use yii\db\ActiveRecord;

class LcbProjectType extends ActiveRecord
{
    public static function tableName()
    {
        return 'lcb_project_type';
    }

    public function attributeLabels()
    {
        return [
            'id' => '类型编号',
            'name' => '类型名称',
            'description' => '类型描述',
            'created_at' => '添加时间',
            'user_id' => '创建者',
            'manager' => '项目负责人',
            'updated_at' => '更新时间'
        ];
    }

    public function rules()
    {
        return [
            [['name','description','user_id'],'required'],
            [['name','description'],'trim']
        ];
    }

    public function getUser() {
        return $this->hasOne(User::className(),['id' => 'user_id']);
    }

    public function getProjectManager() {
        return $this->hasOne(User::className(),['id' => 'manager']);
    }
}