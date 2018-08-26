<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/5/10
 * Time: 11:15
 */

namespace app\controllers;


use app\models\RoleForm;
use app\models\UserForm;
use yii\helpers\ArrayHelper;

class UserController extends CommController
{
    public function actionIndex(){
        $auth = \Yii::$app->authManager;
        $listRoles = ArrayHelper::getColumn($auth->getRoles(),'name');
        return $this->render('index',['model' => new UserForm(),'listRoles' => $listRoles]);
    }

    /**
     * 编辑用户
     * @return array
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function actionEdit(){
        $auth = \Yii::$app->authManager;
        $data = \Yii::$app->request->post('UserForm');
        $result = array();
        if(is_numeric($data['id']) && $data['id'] > 0){
            $user = UserForm::findOne($data['id']);
            if(!$user){
                $result = [
                    'status' => 0,
                    'message' => '未找到该记录',
                ];
            }else{
                $oldPassword = $user->password;
            }
        }else{
            $user = new UserForm();
        }
        if($user->load(\Yii::$app->request->post())){
            if(!$user->isNewRecord && $user->password != '******'){
                $oldPassword = \Yii::$app->security->generatePasswordHash($user->password);
            }
            if($user->save()){
                if(isset($oldPassword)){
                    UserForm::updateAll(['password' => $oldPassword],'id=:id',[':id' => $user->id]); //密码重置
                }
                //分配权限
                $auth->revokeAll($user->id);
                foreach ($user->roles as $roleName){
                    if($role = $auth->getRole($roleName)){
                        $auth->assign($role,$user->id);
                    }
                }
                $result = [
                    'status' => 1,
                    'message' => '保存成功',
                ];
            }
        }

        $errors = $user->getFirstErrors();
        if($errors){
            $result = [
                'status' => 0,
                'message' => current($errors),
            ];
        }
        return $this->renderJson($result);
    }

    public function actionList(){
        $auth = \Yii::$app->authManager;
        $model = UserForm::find()->all();
        return $this->renderPartial('list',['model' => $model]);
    }

    /**
     * 删除用户
     * @param $id
     * @return array
     * @throws \Exception
     */
    public function actionDel($id){
        $model = UserForm::findOne($id);
        $model->delete();
        $result = [
            'status' => 1,
            'message' => '删除成功',
        ];
        return $this->renderJson($result);
    }

    public function actionRole(){
        $auth = \Yii::$app->authManager;
        $permissions = [
            ['name' => 'User', 'description' => '权限管理', 'child' => [
                ['name' => 'user/index', 'description' => '用户管理', 'child' => [
                    ['name' => 'user/list', 'description' => '查看用户'],
                    ['name'=>'user/create','description'=>'添加用户'],
                    ['name' => 'user/edit', 'description' => '添加/编辑用户'],
                    ['name' => 'user/del', 'description' => '删除用户'],

                ]],
                ['name' => 'user/role', 'description' => '角色管理', 'child' => [
                    ['name' => 'user/rolelist', 'description' => '查看角色'],
                    ['name'=>'user/rolecreate','description'=>'添加角色'],
                    ['name' => 'user/roleedit', 'description' => '添加/编辑角色'],
                    ['name' => 'user/roledel', 'description' => '删除角色'],
                ]]
            ]],
        ];

        return $this->render('role',['model' => new RoleForm(),'permissions' => $permissions]);
    }

    public function actionRolelist(){
        $model = \Yii::$app->authManager->getRoles();
        return $this->renderPartial('rolelist',['model' => $model]);
    }

    public function actionRoleedit(){
        $auth = \Yii::$app->authManager;
        $model = new RoleForm();
        $result = array();
        if($model->load(\Yii::$app->request->post())){
            $role = $auth->getRole($model->name);
            if(!$role){
                $role = $auth->createRole($model->name);
                $auth->add($role);
            }
            //分配权限
            $oldPermissions = array();
            if($auth->getPermissionsByRole($role->name)){
                $oldPermissions = ArrayHelper::getColumn($auth->getPermissionsByRole($role->name),'name');
            }
            is_array($model->permissions)?$newPermissions = $model->permissions:$newPermissions = array();
            $intersection = array_intersect($newPermissions,$oldPermissions); //计算交集
            $newPermissions = array_diff($newPermissions,$intersection);//需要增加的权限
            $oldPermissions = array_diff($oldPermissions,$intersection);//需要删除的权限

            foreach ($newPermissions as $new){
                $auth->addChild($role,$auth->getPermission($new));
            }
            foreach ($oldPermissions as $old){
                $auth->removeChild($role,$auth->getPermission($old));
            }
            $result = [
                'status' => 1,
                'message' => '保存成功',
            ];
        }

        $errors = $model->getFirstErrors();
        if($errors){
            $result = [
                'status' => 0,
                'message' => current($errors),
            ];
        }
        return $this->renderJson($result);
    }

    public function actionGetpermissionsbyrole($name) {
        $result = array();
        $auth = \Yii::$app->authManager;
        $permissions = $auth->getPermissionsByRole($name);
        if($permissions){
            foreach ($permissions as $permission){
                $result[] = $permission->name;
            }
        }
        return $this->renderJson($result);
    }

    public function actionRoledel($name){
        $auth = \Yii::$app->authManager;
        $role = $auth->getRole($name);
        if($role){
            $auth->remove($role);
        }
        $result = [
            'status' => 1,
            'message' => '删除成功',
        ];
        return $this->renderJson($result);
    }

    private function getChild($item){
        $auth = \Yii::$app->authManager;
        $item = (array)$item;
        if($children = $auth->getChildren($item['name'])){
            foreach ($children as $child){
                $item['child'][] = $this->getChild($child);
            }
            return $item;
        } else {
            return $item;
        }
    }
}