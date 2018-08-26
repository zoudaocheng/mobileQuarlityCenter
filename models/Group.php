<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/5/16
 * Time: 15:32
 */

namespace app\models;


use yii\db\ActiveRecord;

class Group extends ActiveRecord
{
    const TYPE_USER  = 0;// 普通开发者
    const TYPE_ADMIN = 1;// 管理员

    public static function tableName()
    {
        return 'group';
    }

    public function rules()
    {
        return [
            [['project_id', 'user_id'], 'required'],
            [['project_id', 'user_id', 'type'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'project_id' => '项目编号',
            'user_id'    => '用户编号',
            'type'       => '类型',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser(){
        return $this->hasOne(User::className(),['id' => 'user_id']);
    }

    /**
     * 项目添加用户
     * @param     $projectId
     * @param     $userIds
     * @param int $type
     * @return bool
     */
    public static function addGroupUser($projectId,$userIds,$type = Group::TYPE_USER){
        $existUids = Group::find()
            ->select(['user_id'])
            ->where(['project_id' => $projectId,'user_id' => $userIds])
            ->column();
        $notExists = array_diff($userIds,$existUids);
        if(empty($notExists)) return true;

        $group = new Group();
        foreach ($notExists as $uid){
            $relation = clone $group;
            $relation->attributes = [
                'project_id' => $projectId,
                'user_id' => $uid,
                'type' => $type
            ];
            $relation->save();
        }
        return true;
    }

    /**
     * 叛断该项目的审核管理员
     * @param      $uid
     * @param null $projectId
     * @return int|string
     */
    public static function isAuditAdmin($uid, $projectId = null) {
        $isAuditAdmin = static::find()
            ->where(['user_id' => $uid, 'type' => Group::TYPE_ADMIN]);
        if ($projectId) {
            $isAuditAdmin->andWhere(['project_id' => $projectId, ]);
        }
        return $isAuditAdmin->count();
    }

    /**
     * 获取用户可以审核的项目
     * @param $uid
     * @return array
     */
    public static function getAuditProjectIds($userId) {
        return static::find()
            ->select(['project_id'])
            ->where(['user_id' => $userId, 'type' => Group::TYPE_ADMIN])
            ->column();
    }
}