<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2017/4/10
 * Time: 16:12
 * For Jenkins Code Integration
 */

namespace app\controllers;

use app\components\Jira;
use app\components\Wechat;
use app\models\LcbProjectPublish;
use app\models\LcbProjectType;
use app\models\LiftCheckLog;
use app\models\LiftDetail;
use app\models\LiftPlan;
use app\models\User;
use yii\base\Controller;
use yii\base\Exception;
use yii\data\ActiveDataProvider;

class LiftController extends Controller
{
    public function actionLiftApply() {
        $args = json_decode(\Yii::$app->request->rawBody);
        $version = str_replace('_','.',str_replace('-','.',$args->version));
        $mqcVersion = Jira::getVersionForMQC($version);//MQC返回的数据
        $tmp = explode('.',$mqcVersion->mVersion);//转为数组，去掉版本号中的数字，目的是拿到项目中找项目ID
        $_version = Jira::getVersionInfo($mqcVersion->jVersion);//获取版本信息
        $plan = LiftPlan::find()->andFilterWhere(['like','version',$mqcVersion->project])->andFilterWhere(['version_id' => $_version->id])->one();//查看版本号或版本ID是否已经存在
        $issues = Jira::search(['fixVersion' => $mqcVersion->jVersion]); //获取查issue的方法
        $transaction = \Yii::$app->db->beginTransaction();
        $prePlan = $plan;
        try {
            if(!$plan){
                $plan = new LiftPlan();
                $plan->type_id = LcbProjectType::findOne(['name' => $tmp[0]])->id;
                if(!LcbProjectPublish::findOne(['description' => $mqcVersion->project])){
                    //项目不存在时 - 添加项目
                    $project = new LcbProjectPublish();
                    $project->type_id = $plan->type_id;
                    $project->name = '初始化项目名称';
                    $project->description = implode('.',$mqcVersion->project);
                    $project->user_id = 1;//默认由管理员添加
                    $project->created_at = time();
                    $project->updated_at = time();
                    $project->save();
                    //TODO 需要后续完成有新项目创建时通知qa团队（邹道城）
                }
                $plan->project_id = LcbProjectPublish::findOne(['description' => $mqcVersion->project])->id;
                $plan->version = $mqcVersion->mVersion;
                $plan->version_id = $_version?$_version->id:'NULL';
                $plan->version_uri = $_version?$_version->self:'NULL';
                $plan->pre_lift_time = (isset($_version->startDate) && $_version->startDate)?$_version->startDate:'NULL';
                $plan->pre_publish_time = (isset($_version->releaseDate) && $_version->releaseDate)?$_version->releaseDate:'NULL';
                $plan->version_description = ($_version && isset($_version->description))?$_version->description:'无';
                $plan->lift_time = time();
                $plan->plan_type = -1;
                $plan->developer = (User::findOne(['username' => $args->developer])->id)?(User::findOne(['username' => $args->developer])->id):(User::findOne(['realname' => $args->developer])->id?User::findOne(['realname' => $args->developer])->id:1);
                $plan->created_time = time();
                $plan->updated_time = time();

                if (strstr($mqcVersion->jVersion,'h5') || strstr($mqcVersion->jVersion,'rn')){
                    $plan->qa = 35; //分配给阳新宇
                }elseif (strstr($mqcVersion->jVersion,'php')){
                    $plan->qa = 27;//分配给冯伟
                }elseif (strstr($mqcVersion->jVersion,'soa')){
                    $plan->qa = 26;//分配给贺晨
                }else{
                    $plan->qa = 3;//其他分配给尹安平
                }

                $plan->save();
            } else{
                /**
                 * 如果版本号有变更，则需要更新已经存储的版本号
                 * 变更版本描述
                 * 项目实际提测时间及提测人员的存储
                 */
                if ($plan->version != $mqcVersion->mVersion){
                    $plan->version = $mqcVersion->mVersion;
                    $plan->version_description = ($_version && isset($_version->description))?$_version->description:'无';
                    if ($plan->pre_publish_time != $_version->releaseDate)
                        $plan->pre_publish_time = $_version->releaseDate;
                    $plan->save();
                }
                /**
                 * 更新上一次申请测试的测试结果
                 */
                $maxId = LiftDetail::find()->andWhere(['plan_id' => $plan->id])->max('id');
                if($maxId){
                    //邮件发送测试结果
                    $model = LiftDetail::findOne($maxId);
                    $email = [
                        'content' => [
                            'project' => $mqcVersion->project,
                            'version' => $mqcVersion->mVersion,
                            'lift_time' => $plan->lift_time,
                            'issues' => $issues,
                            'current_lift_time' => $model->created_time,
                            'result' => 0,
                            'build_no' => $args->build_no,
                            'developer' => $plan->lifter->realname,
                            'count' => LiftDetail::find()->andFilterWhere(['plan_id' => $plan->id])->count(),
                        ],
                        'subject' => '[Failed Test Report]',
                        'compose' => 'test-report',
                        'receiver' => [$plan->lifter->username.'@lechebang.com',$plan->projectType->projectManager['username'].'@lechebang.com'],
                        'cc' => ['qa@lechebang.com','tech_management@lechebang.com']
                    ];
                    if(null === $model->lcbint_result){
                        $model->lcbint_result = 0;
                        $email['content']['environment'] = '阿里环境';
                        $email['subject'] = $email['subject'].' - '.'【测试环境】'.$version;
                        $email['subject'] = $email['subject'].' - 第【'.LiftDetail::find()->andFilterWhere(['plan_id' => $plan->id])->count().'】轮测试';
                    }elseif (null === $model->mtest_result) {
                        $model->mtest_result = 0;
                        $model->mtest_time = time();
                        $email['content']['environment'] = '堡垒环境';
                        $email['subject'] = $email['subject'].' - '.'【堡垒环境】'.$version;
                    }elseif(null === $model->pro_result){
                        $model->pro_result = 0;
                        $model->pro_time = time();
                        $email['content']['environment'] = '生产环境';
                        $email['subject'] = $email['subject'].' - '.'【生产环境】'.$version;
                    }
                    $model->updated_time = time();
                    $model->save();
                    CommController::email($email);//邮件发送
                }else{
                    /**
                     * 首次提交需要更新提测时间及实际开发人员
                     */
                    $plan->lift_time = time();
                    $plan->updated_time = time();
                    $plan->developer = (User::findOne(['username' => $args->developer])->id)?(User::findOne(['username' => $args->developer])->id):(User::findOne(['realname' => $args->developer])->id?User::findOne(['realname' => $args->developer])->id:1);
                    $plan->save();
                }
            }
            $model = new LiftDetail();
            $model->plan_id = LiftPlan::findOne(['version' => $mqcVersion->mVersion,'version_id' => $_version->id])->id;
            $model->build_no = $args->build_no;
            $model->environment = $args->environment;
            $model->level = $args->level;
            $model->depends = $args->depends;
            if(LiftDetail::findOne(['plan_id' => $model->plan_id])){
                $model->addition_functions = $args->functions;
            } else {
                $model->functions = $args->functions;
            }
            $model->advice = $args->advice;
            $model->st_flag = $args->st_flag;
            $model->unit_flag = $args->unit_flag;
            $model->issues = $issues; //版本相关 issues
            $model->created_time = time();
            $model->updated_time = time();
            $model->lcbint_time = time();
            if ($model->save()) {
                $result['status'] = 1;
                $result['message'] = 'success!';
                $count = LiftDetail::find()->andFilterWhere(['plan_id' => $plan->id])->count();//用于查看当前提测了多少轮
                //提测时需要通知测试人员
                $email = [
                    'content' => [
                        'project' => implode('.',$tmp),
                        'version' => $version,
                        'lift_time' => $plan->lift_time,
                        'pre_lift_time' => $plan->pre_lift_time,
                        'pre_publish_time' => $plan->pre_publish_time?$plan->pre_publish_time:date('Y-m-d',time()),
                        'current_lift_time' => $model->created_time,
                        'build_no' => $args->build_no,
                        'issues' => $issues, //需要告诉测试人员issues有哪些 - 需求及BUG（如果为BUG需要强调验证)
                        'qa' => $plan->qa?$plan->tester->realname:('php' == $tmp[0]?'冯伟':'安平'),//计划分配&&默认分配
                        'developer' => $plan->lifter->realname,
                        'count' => LiftDetail::find()->andFilterWhere(['plan_id' => $plan->id])->count(), //第几次提测
                        'environment' => $args->environment,
                        'advice' => $args->advice,
                        'level' => $args->level
                    ],
                    'subject' => '【测试任务 - 提测通知】'.$mqcVersion->mVersion.' - 第【'.LiftDetail::find()->andFilterWhere(['plan_id' => $plan->id])->count().'】轮提测',
                    'compose' => 'qa-notice',
                    'receiver' => [$plan->qa?$plan->tester->username.'@lechebang.com':('php' == $tmp[0]?'fengwei@lechebang.com':'yinanping@lechebang.com'),$plan->lifter->username.'@lechebang.com'],//计划分配 && 默认分配
                    'cc' => ($prePlan && (strpos($_version->name,'h5') || strstr($_version->name,'rn')) && $count == 1)?['qa@lechebang.com','chenjing@lechebang.com','zhangxin@lechebang.com','wanwenyun@lechebang.com','liuzhao@lechebang.com',$plan->projectType->projectManager['username'].'@lechebang.com']:['qa@lechebang.com',$plan->projectType->projectManager['username'].'@lechebang.com'],
                ];
            }
            $errors = $model->getFirstErrors();
            if($errors) {
                $result['status'] = 0;
                $result['message'] = current($errors);
            }
            $transaction->commit();
            CommController::email($email);//邮件发送
        }catch (Exception $exc) {
            $transaction->rollBack();
            $result['status'] = 0;
            $result['message'] = '提测记录申请失败';
        }
        echo json_encode($result);
    }

    /**
     * 测试结果
     */
    public function actionQaResult(){
        $args = json_decode(\Yii::$app->request->rawBody);
        $mqcVersion = Jira::getVersionForMQC($args->version);
        $plan = LiftPlan::findOne(['version' => $mqcVersion->mVersion]);//查看是否已经存在
        $model = LiftDetail::findOne(['build_no' => $args->build_no,'plan_id' => $plan->id]);
        $issues = Jira::search(['fixVersion' => $mqcVersion->jVersion]); //获取查issue的方法
        $email = [
            'content' => [
                'project' => $mqcVersion->project,
                'version' => $mqcVersion->mVersion,
                'lift_time' => $plan->lift_time,
                'current_lift_time' => $model->created_time,
                'result' => $args->result,
                'issues' => $issues,//测试结果提了哪些BUG - 用于展示缺陷列表
                'build_no' => $args->build_no,
                'developer' => $plan->lifter->realname,
                'count' => LiftDetail::find()->andFilterWhere(['plan_id' => $plan->id])->count(),
            ],
            'subject' => (1 == $args->result)?'[Pass Test Report]':'[Failed Test Report]',
            'compose' => 'test-report',
            'receiver' => [$plan->lifter->username.'@lechebang.com',$plan->projectType->projectManager['username'].'@lechebang.com'],
            'cc' => ['qa@lechebang.com','tech_management@lechebang.com']
        ];
        if('test' == $args->environment){
            $model->lcbint_time = time();
            $model->lcbint_result = $args->result;
            $email['content']['environment'] = '阿里环境';
            $email['subject'] = $email['subject'].' - '.'【测试环境】'.$mqcVersion->mVersion;
            $email['subject'] = $email['subject'].' - 第【'.LiftDetail::find()->andFilterWhere(['plan_id' => $plan->id])->count().'】轮测试';
        }
        if('mtest' == $args->environment) {
            $model->mtest_time = time();
            $email['content']['environment'] = '堡垒环境';
            $email['subject'] = $email['subject'].' - '.'【堡垒环境】'.$mqcVersion->mVersion;
            $model->mtest_result = $args->result;
            /**
             * job和tmall特殊逻辑
             */
            $tmp = explode('.',$mqcVersion->mVersion);
            if((in_array('job1',$tmp) || in_array('job2',$tmp) || in_array('tmall1',$tmp) || in_array('tmall2',$tmp)) && 1 == $args->result){
                $model->lcbint_result =1;
                $model->lift_mtest_time = time();
                $model->pro_time = time();
                $model->pro_result = $args->result;
                $model->merge_lifter = 25;
                $model->merge_time = time();
                $plan->publish_time = time();
                $plan->publish_status = 1;
                $plan->updated_time = time();
                $plan->save();
            }
        }
        if('pro' == $args->environment){
            $model->pro_time = time();
            $model->pro_result = $args->result;
            if(1 == $args->result){ //保存项目发布时间
                $plan->publish_time = time();
                $plan->publish_status = 1;
                $plan->updated_time = time();
                $plan->save();
                $email['content']['environment'] = '生产环境';
                $email['subject'] = $email['subject'].' - '.'【生产环境】'.$mqcVersion->mVersion;
            }
        }
        $model->updated_time = time();
        if ($model->save()) {
            $result['status'] = 1;
            $result['message'] = 'success!';
        }
        $errors = $model->getFirstErrors();
        if($errors) {
            $result['status'] = 0;
            $result['message'] = current($errors);
        }
        CommController::email($email);
        echo json_encode($result);
    }

    /**
     * 提交发布
     */
    public function actionLiftPublish() {
        $args = json_decode(\Yii::$app->request->rawBody);
        $mqcVersion = Jira::getVersionForMQC($args->version);
        $_version = Jira::getVersionInfo($mqcVersion->jVersion);
        $plan = LiftPlan::findOne(['version' => $mqcVersion->mVersion]);//查看是否已经存在
        $model = LiftDetail::findOne(['build_no' => $args->build_no,'plan_id' => $plan->id]);
        if('merge' == $args->environment){
            $model->merge_time = time();
            $model->merge_lifter = User::findOne(['username' => $args->lifter])->id;
            if ($_version){
                $release = [
                    'id' => $_version->id,
                    'released' => true,
                    'archived' => true,
                    'releaseDate' => date('Y-m-d',time()),
                    'overdue' => (!isset($_version->releaseDate) || (strtotime($_version->releaseDate.' 00:00:01') < strtotime(date('Y-m-d 00:00:01'))))?true:false,
                ];
                Jira::release($release);
                $issues = Jira::search(['fixVersion' => $mqcVersion->jVersion]);
                Jira::closeIssue(json_decode($issues));//关闭issue
            }
        }
        if('mtest' == $args->environment) {
            $model->lift_mtest_time = time();
            $model->mtest_lifter = User::findOne(['username' => $args->lifter])->id;
        }
        if('pro' == $args->environment){
            $features = '';
            $tasks = '';
            $model->lift_pro_time = time();
            $model->pro_lifter = User::findOne(['username' => $args->lifter])->id;
            //如果是job或tmall 则加上特殊逻辑
            $tmp = explode('.',$mqcVersion->mVersion);
            if(in_array('job1',$tmp) || in_array('job2',$tmp) || in_array('tmall1',$tmp) || in_array('tmall2',$tmp)){
                $model->lcbint_result =1;
                $model->lift_mtest_time = time();
                $model->pro_time = time();
                $model->pro_result = 1; //暂时逻辑
                $model->merge_lifter = 25;
                $model->merge_time = time();
                $plan->publish_time = time();
                $plan->publish_status = 1;
                $plan->updated_time = time();
                $plan->save();
            }
            //H5发布流程中增加产品验收通知 - 通知方式：企业微信
            if (in_array('h5',$tmp) || in_array('hybrid',$tmp)){
                //由于hybrid是独立发布因此版本号还是要区分一下
                if (in_array('hybrid',$tmp)){
                    $features = str_replace('h5','hybrid',$features);
                    $tasks = str_replace('h5','hybrid',$features);
                }
                $ret = Jira::search(['fixVersion' => $_version->name]);
                $issues = json_decode($ret);
                if ($issues) {
                    foreach ($issues as $issue) {
                        if ('New Feature' == $issue->type || 'Sub-Feature' == $issue->type) {
                            $features = $features . "需    求    方:" . $issue->reporter . ";\nISSUE编号:<a href='" . $issue->uri . "'>" . $issue->key . "</a>\nISSUE摘要:" . $issue->summary . ";\n\n";
                        } else {
                            $tasks = $tasks . "需    求    方:" . $issue->reporter . ";\nISSUE编号:<a href='" . $issue->uri . "'>" . $issue->key . "</a>\nISSUE摘要:" . $issue->summary . ";\n\n";
                        }
                    }
                }
                $data = [
                    'appCode' => 101,
                    'msg' => "[版本编号]： <".$_version->name."> \n\n".($features?$features:($tasks?$tasks:'无关联issue'))."请相关人员及时到生产环境验收，以免影响上线时间!\n\n",
                    'agentId' => '1000004'
                ];
                $data['msg'] = $data['msg']."确认地址:<a href='http://jenkins.lcbint.cn/view/%E5%8F%91%E5%B8%83/job/publish_h5/'>发布系统传送门</a>";
                Wechat::notify($data);
            }
        }
        $model->updated_time = time();
        if ($model->save()) {
            $result['status'] = 1;
            $result['message'] = 'success!';
        }
        $errors = $model->getFirstErrors();
        if($errors) {
            $result['status'] = 0;
            $result['message'] = current($errors);
        }
        echo json_encode($result);
    }

    /**
     * 项目提测时版本号检测
     */
    public function actionLiftCheck(){
        $ret = [
            'status' => 0
        ];
        $args = json_decode(\Yii::$app->request->rawBody);//获取并解析提交的参数
        $tmpVersion = str_replace('_','.',str_replace('-','.',$args->version));//格式化提测时的版本号
        $_version = Jira::convertVersion($tmpVersion);//对版本号兼容(与jira中版本对应)
        $version = Jira::getVersionInfo($_version);//获取版本号详情
        if ($version ){
            $issues = Jira::search(['fixVersion' => $_version]);//获取该版本下关联的issue
            if ($issues){
                if (!isset($version->releaseDate) || !$version->releaseDate){
                    $ret['message'] = '提测失败 => 预计发布日期（Release Date）为空,请计划好预计发布时间再提测!';
                } elseif (!isset($version->startDate) || !$version->startDate){
                    $ret['message'] = '提测失败 => 预计提测日期（Start Date）为空,请计划好预计发布时间再提测!';
                } elseif (!isset($version->description) || !$version->description){
                    $ret['message'] = '提测失败 => 版本描述（Descripftion）不能为空，请添加版本描述后再提测!';
                }else {
                    $ret['status'] = 1;
                    $ret['message'] = 'lift version check success!';
                }
            } else {
                $ret['message'] = '提测失败 => 该版本号没有关联issue，请关联issue后再提测!';
            }
        } else{
            $ret['message'] = '提测失败 => 该版本对应版本号(JIRA中)不存在或该版本已经Released,请确认提测版本号正确!';
        }
        LiftCheckLog::insertLiftCheckLog($tmpVersion,$ret['status'],0,1,$ret['message']);
        echo json_encode($ret);//接口返回正确的中文
    }

    /**
     * Demon
     */
    public function actionSearchIssues(){
        $ret = Jira::search(['fixVersion' => Jira::convertVersion('h5.webapp.5.0.4')]);
        $issues = json_decode($ret);
        $features = '';
        $tasks = '';
        foreach ($issues as $issue){
            if ('New Feature' == $issue->type || 'Sub-Feature' == $issue->type){
                $features = $features."需    求    方:".$issue->reporter.";\nISSUE编号:<a href='".$issue->uri."'>".$issue->key."</a>\nISSUE摘要:".$issue->summary.";\n\n";
            } else {
                $tasks = $tasks."需    求    方:".$issue->reporter.";\nISSUE编号:<a href='".$issue->uri."'>".$issue->key."</a>\nISSUE摘要:".$issue->summary.";\n\n";
            }
        }
        $data = [
            'appCode' => 101,
            'msg' => "[版本编号]： <h5.webapp.5.0.4> \n\n".($features?$features:($tasks?$tasks:'无关联issue'))."请相关人员及时到生产环境验收，以免影响上线时间!\n\n",
            'agentId' => '1000004',
            'user' => 'zoudaocheng'
        ];
        $data['msg'] = $data['msg']."确认地址:<a href='http://jenkins.lcbint.cn/view/%E5%8F%91%E5%B8%83/job/publish_h5/'>发布系统传送门</a>";
        Wechat::notify($data);
    }

    public function actionRepairVersion(){
        $query = LiftPlan::find();
        $provider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => [
                    'pageSize' => 200,
                ],
                'sort' => [
                    'defaultOrder' => [
                        'publish_status' => SORT_ASC,
                        'updated_time' => SORT_DESC,
                    ]
                ],
            ]
        );
        foreach ($provider->models as $plan){
            $version = Jira::getVersionInfo(Jira::convertVersion($plan->version));
            if ($version){

                if (!$plan->pre_lift_time && isset($version->startDate))
                    $plan->pre_lift_time = $version->startDate;
                if (!$plan->pre_publish_time && isset($version->releaseDate))
                    $plan->pre_publish_time = $version->releaseDate;

                $plan->version_id = $version->id;
                $plan->version_uri = $version->self;
                $plan->save();
            }
        }
    }
}