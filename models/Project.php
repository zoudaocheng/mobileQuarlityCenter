<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/5/16
 * Time: 11:37
 */

namespace app\models;


use app\components\Folder;
use app\components\GlobalHelper;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "conf"
 * @package app\models
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property integer $level
 * @property integer $status
 * @property string $version
 * @property integer $created_at
 * @property string $deploy_from
 * @property string $excludes
 * @property string $release_user
 * @property string $release_to
 * @property string $release_library
 * @property string $hosts
 * @property string $pre_deploy
 * @property string $post_deploy
 * @property string $post_release
 * @property string $repo_mode
 * @property string $repo_type
 * @property integer $audit
 * @property integer $ansible
 * @property integer $keep_version_num
 */
class Project extends ActiveRecord
{
    const STATUS_VALID = 1; //有效状态
    const LEVEL_TEST = 1; //测试环境
    const LEVEL_UAT = 2; //堡垒环境
    const LEVEL_PROD = 3; //生产环境
    const AUDIT_YES = 1;
    const AUDIT_NO = 2;
    const REPO_BRANCH = 'branch';
    const REPO_TAG = 'tag';
    const REPO_GIT = 'git';
    const REPO_SVN = 'svn';

    public static $CONF;
    public static $LEVEL = [
        self::LEVEL_TEST => 'test',
        self::LEVEL_UAT => 'uat',
        self::LEVEL_PROD => 'prod'
    ];

    public static function tableName(){
        return 'project';
    }

    public function behaviors()
    {
        return [
            'class' => TimestampBehavior::className(),
            'createAtAttribute' => 'created_at',
            'updateAtAttribute' => 'updated_at',
            'value' => new Expression('NOW()')
        ];
    }

    public function rules()
    {
        return [
            [['user_id', 'repo_url', 'name', 'level', 'deploy_from', 'release_user', 'release_to', 'release_library', 'hosts', 'keep_version_num'], 'required'],
            [['user_id', 'level', 'status', 'audit', 'ansible', 'keep_version_num'], 'integer'],
            [['excludes', 'hosts', 'pre_deploy', 'post_deploy', 'pre_release', 'post_release'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'repo_password'], 'string', 'max' => 100],
            [['version'], 'string', 'max' => 20],
            ['repo_type', 'default', 'value' => self::REPO_GIT],
            [['deploy_from', 'release_to', 'release_library', 'repo_url'], 'string', 'max' => 200],
            [['release_user', 'repo_mode', 'repo_username'], 'string', 'max' => 50],
            [['repo_type'], 'string', 'max' => 10],
        ];
    }

    public function attributes()
    {
        return [
            'id'               => '项目编号',
            'user_id'          => '创建用户',
            'name'             => '项目名称',
            'level'            => '环境',
            'status'           => '状态',
            'version'          => '版本',
            'created_at'       => '创建时间',
            'deploy_from'      => '检出仓库',
            'excludes'         => '排除文件列表',
            'release_user'     => '目标机器部署代码用户',
            'release_to'       => '代码的webroot',
            'release_library'  => '发布版本库',
            'hosts'            => '目标机器',
            'pre_deploy'       => '宿主机代码检出前置任务',
            'post_deploy'      => '宿主机同步前置任务',
            'pre_release'      => '目标机更新版本前置任务',
            'post_release'     => '目标机更新版本后置任务',
            'repo_url'         => 'git/svn地址',
            'repo_username'    => 'svn用户名',
            'repo_password'    => 'svn密码',
            'repo_mode'        => '分支/tag',
            'audit'            => '任务需要审核？',
            'ansible'          => '开启Ansible？',
            'keep_version_num' => '线上版本保留数',
        ];
    }

    /**
     * 获取当前进程的项目配置
     * @param null $id
     * @return mixed
     */
    public static function getConf($id = null){
        if(empty(static::$CONF)){
            static::$CONF = static::findOne($id);
        }
        return static::$CONF;
    }

    /**
     * 根据git地址获取项目名称
     * @param string $gitUrl
     * @return mixed
     */
    public static function getGitProjectName($gitUrl = ''){
        if(preg_match('#.*/(.*?)\.git#',$gitUrl,$match)){
            return $match[1];
        }
        return basename($gitUrl);
    }

    /**
     * 拼接宿主机的部署隔离工作空间
     * @param $version
     * @return mixed
     */
    public static function getDeployWorkspace($version) {
        $from = static::$CONF->deploy_from;
        $env = isset(static::$LEVEL[static::$CONF->level])?static::$LEVEL[static::$CONF->level]:'Unknown Environment';
        $project = static::getGitProjectName(static::$CONF->repo_url);

        return sprintf("%s/%s/%s-%s",rtrim($from,'/'),rtrim($env,'/'),$project,$version);
    }

    /**
     * 获取 ansible 宿主机tar文件路径
     * {deploy_from}/{env}/{project}-YYmmdd-HHiiss.tar.gz
     * @param $version
     * @return mixed
     */
    public static function getDeployPackagePath($version){
        return sprintf('%s.tar.gz', static::getDeployWorkspace($version));
    }

    /**
     * 获取目标机要发布的目录
     * @return mixed
     */
    public static function getTargetWorkspace(){
        return rtrim(static::$CONF->release_to,'/');
    }

    /**
     * 拼接目标机要发布的目录
     * {release_library}/{project}/{version}
     * @param string $version
     * @return mixed
     */
    public static function getReleaseVersionDir($version = ''){
        return sprintf('%s/%s/%s', rtrim(static::$CONF->release_library, '/'),
            static::getGitProjectName(static::$CONF->repo_url), $version);
    }

    /**
     * 获取当前进程配置的目标机器host列表
     * @return mixed
     */
    public static function getHosts(){
        return GlobalHelper::str2arr(static::$CONF->hosts);
    }

    /**
     * 获取当前进程配置的ansible状态
     * @return bool
     */
    public static function getAnsibleStatus(){
        return (bool)static::$CONF->ansible;
    }

    /**
     * 获取当前进程配置的ansible hosts文件路径
     *  $projectId 可以传入指定的id
     * {ansible_hosts.dir}/project_{projectId}
     * @param int $projectId
     */
    public static function getAnsibleHostsFile($projectId = 0){
        if(!$projectId){
            $projectId = static::$CONF->id;
        }
        return sprintf('%s/project_%d',rtrim(\Yii::$app->params['ansible_hosts.dir'],'/'),$projectId);
    }

    /**
     * 添加数据保存事件afterSave
     * @param bool  $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        //修改了项目repo_url，本地检出代码将被清空
        if(isset($changedAttributes['repo_url'])){
            $projectDir = static::getDeployFromDir();
            if(file_exists($projectDir)){
                $folder = new Folder($this);
                $folder->removeLocalProjectWorkspace($projectDir);
            }
        }
        //插入一条管理员关系
        if($insert){
            Group::addGroupUser($this->attributes['id'],[$this->attributes['user_id']],Group::TYPE_ADMIN);
        }
    }

    /**
     * 拼接宿主机的仓库目录
     * @return mixed
     */
    public static function getDeployFromDir(){
        $from    = static::$CONF->deploy_from;
        $env     = isset(static::$LEVEL[static::$CONF->level]) ? static::$LEVEL[static::$CONF->level] : 'Unknown Environment';
        $project = static::getGitProjectName(static::$CONF->repo_url);

        return sprintf("%s/%s/%s", rtrim($from, '/'), rtrim($env, '/'), $project);
    }

    /**
     * 添加数据删除事件afterDelete
     */
    public function afterDelete()
    {
        parent::afterDelete();
        Group::deleteAll(['project_id' => $this->attributes['id']]);
    }

    /**
     * 拼接目标机要发布的打包文件路径
     * {release_library}/{project}/{version}.tar.gz
     * @param string $version
     * @return mixed
     */
    public static function getReleaseVersionPackage($version = '') {

        return sprintf('%s.tar.gz', static::getReleaseVersionDir($version));
    }
}