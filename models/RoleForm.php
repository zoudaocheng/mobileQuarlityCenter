<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/5/10
 * Time: 11:23
 */

namespace app\models;


use yii\base\Model;

class RoleForm extends Model
{
    public $name;
    public $permissions;

    public function rules(){
        return [
            ['name','required'],
            ['name','trim'],
            ['permissions','required'],
            ['permissions','validatePermissions'],
            ['name','string','length' => [2,8]]
        ];
    }

    public function attributeLabels(){
        return [
            'name' => '角色名称',
            'permissions' => '分配权限',
        ];
    }

    /**
     * 权限验证
     * @param $attribute
     * @param $params
     */
    public function validatePermissions($attribute,$params) {
        if(!$this->hasErrors()){
            if(!isset($this->permissions)){
                $this->addError($attribute,'至少选择一个权限');
            }
        }
    }
}