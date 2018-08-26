<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2017/4/12
 * Time: 17:29
 */

namespace app\models;


use yii\db\ActiveRecord;

class LiftDetail extends ActiveRecord
{
    public static function tableName()
    {
        return 'lift_detail';
    }

    public function attributeLabels()
    {
        return [
            'id' => '提测ID',
            'plan_id' => '计划ID',
            'build_no' => '构建号',
            'environment' => '提测环境',
            'level' => '级别',
            'depends' => '发布依赖',
            'functions' => '功能描述',
            'addition_functions' => '追加功能描述',
            'advice' => '测试建议',
            'st_flag' => '是否自测' ,//0:未自测;1:已自测
            'unit_flag' => '单元测试',//0:未通过;1:已通过
            'issues' => '关联issue',
            'lcbint_time' => '测试完成时间',
            'lcbint_result' => '测试环境结果',//1:冒烟不过;0:不通过;1:测试通过
            'lift_mtest_time' => '发布堡垒时间',
            'mtest_time' => '堡垒完成时间',
            'mtest_result' => '堡垒测试结果',//0:不通过;1:测试通过
            'lift_pro_time' => '发布生产时间',
            'pro_time' => '生产测试结果',
            'pro_result' => '生产测试结果',//0:不通过;1:测试通过
            'created_time' => '提测时间',
            'merge_time' => '代码合并时间',
            'updated_time' => '更新时间'
        ];
    }

    public function getLiftPlan(){
        return $this->hasOne(LiftPlan::className(),['id' => 'plan_id']);
    }
}