<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/6/15
 * Time: 15:45
 */

namespace app\models;


use yii\db\ActiveRecord;

class Soa extends ActiveRecord
{
    public static function tableName()
    {
        return 'soa';
    }

    public function attributeLabels()
    {
        return [
            'soa_name' => '接口名称',
            'project_id' => '项目名称',
            'url' => '接口URL',
            'soa_desc' => '接口描述',
            'mock' => '模拟请求',
            'response' => '接口响应'
        ];
    }

    public function rules()
    {
        return [
            [['soa_name','url','soa_desc','mock','response'],'trim'],
            [['soa_name','project_id','url','soa_desc','mock','response'],'required','on' => 'add'],
            [['user_id'],'number'],
            [['is_valid'],'boolean'],
            [['project_id'],'default','value' => 0],
            [['created_at','updated_at'],'default','value' => time()],
            [['request_field','request_param','response_field','response_param'],'default','value' => ''],
            ['user_id', 'default', 'value' => \Yii::$app->user->identity->id], //操作用户
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['add'] = ['soa_name','project_id','url','soa_desc','mock','response'] ;
        return $scenarios;
    }

    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getProject(){
        return $this->hasOne(SoaForm::className(),['id' => 'project_id']);
    }
}