<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2017/5/7
 * Time: 10:25
 */

namespace app\models;


use yii\db\ActiveRecord;

class Email extends ActiveRecord
{
    public static function tableName()
    {
        return 'email';
    }

    public function attributeLabels()
    {
        return [
            'type' => '邮件类型',
            'subject' => '邮件标题',
            'compose' => '邮件模板',
            'attachment' => '邮件附件',
            'receiver' => '收件人',
            'cc' => '抄送人',
            'content' => '邮件数据',
            'status' => '发送状态'
        ];
    }
}