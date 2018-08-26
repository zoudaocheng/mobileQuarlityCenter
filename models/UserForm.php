<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/4/29
 * Time: 10:29
 */

namespace app\models;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * 用户模型，用于管理员管理其他用户
 * Class UserForm
 * @package app\models
 */
class UserForm extends ActiveRecord
{
    public $verifyPassword;
    public $roles;

    public static function tableName()
    {
        return 'user';
    }

    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password' => '密码',
            'verifyPassword' => '确认密码',
            'roles' => '角色',
            'enabled' => '启动',
            'realname' => '姓名'
        ];
    }

    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            if($this->isNewRecord){
                $this->auth_key = \Yii::$app->security->generateRandomString();
                $this->password = \Yii::$app->security->generatePasswordHash($this->password);
                $this->created_at = time();
                $this->updated_at = time();
            }
            return true;
        }else{
            return false;
        }
    }

    public function rules()
    {
        return [
            [['username','password','verifyPassword','realname'],'trim'],
            ['enabled','boolean'],
            ['username','string','length' => [2,20]],
            ['realname','string','length' => [2,20]],
            [['password','verifyPassword'],'string','length' => [4,12]],
            [['username','password','verifyPassword'],'required'],
            ['verifyPassword','compare','compareAttribute' => 'password','message' => '请重复输入密码'],
            ['username','unique'],
            ['roles','required']
        ];
    }

    public function fields()
    {
        $fields = parent::fields();
        $fields['password'] = function(){
            return '******';
        };

        $fields['roles'] = function() {
            return ArrayHelper::getColumn(\Yii::$app->authManager->getRolesByUser($this->id),'name');
        };

        unset($fields['auth_key'],$fields['access_token']);
        return $fields;
    }
}