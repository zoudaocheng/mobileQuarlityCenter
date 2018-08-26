<?php
/**
 * @link http://mqc.lcbint.com
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/6/15
 * Time: 15:45
 */

namespace app\models;


use yii\db\ActiveRecord;

class SoaForm extends ActiveRecord
{
    /**
    public $id;
    public $project_name;
    public $domain;
    public $project_desc;
    public $is_valid;
    public $created_at;
    public $updated_at;
    */

    public static function tableName()
    {
        return 'lcb_project';
    }

    public function attributeLabels()
    {
        return [
            'project_name' => '项目名称',
            'domain' => '项目域名',
            'project_desc' => '项目描述'
        ];
    }

    public function rules()
    {
        return [
            //通用场景
            [['project_name','domain','project_desc'],'trim'],

            //添加项目场景
            [['project_name','domain','project_desc'],'required','on' => 'add'],
            ['project_name','string','length' => [2,20],'on' => 'add'],

            //编辑项目场景
            [['project_name','domain','project_desc'],'required','on' => 'editProject'],
            ['project_name','string','length' => [2,20],'on' => 'editProject'],
            ['user_id', 'default', 'value' => \Yii::$app->user->identity->id], //操作用户
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['add'] = ['project_name','domain','project_desc'];
        $scenarios['editProject'] = ['project_name','domain','project_desc'];
        return $scenarios;
    }

    public function getId()
    {
        return $this->id;
    }

    public static function findByProjectName($projectName){
        return static::findOne(['project_name' => $projectName]);
    }
}