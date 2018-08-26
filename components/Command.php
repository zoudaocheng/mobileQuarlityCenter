<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/5/13
 * Time: 15:50
 */

namespace app\components;


use yii\console\Exception;

class Command
{
    protected static $LOGDIR = '';

    protected static $logFile = null;

    protected $config;

    protected $status = 1; // 命令运行返回值：0失败，1成功
    protected $command = '';

    protected $log = null;

    /**
     * 配置信息加载
     * Command constructor.
     * @param $config
     */
    public function __construct($config)
    {
        if($config) {
            $this->config = $config;
        } else {
            throw new Exception(\Yii::t('mqc','配置信息未配置'));
        }
    }

    public function setConfig($config) {
        if($config){
            $this->config = $config;
        } else{
            throw  new Exception(\Yii::t('mqc','配置信息未配置'));
        }
    }

    public function getConfig(){
        return $this->config;
    }

    /**
     * 日志函数
     * @param string $message
     */
    public static function log($message = ''){
        if(empty(\Yii::$app->params['log.dir'])) return;

        $logDir = \Yii::$app->params['log.dir'];
        if(!file_exists($logDir)) return;

        $logFile = realpath($logDir).'/walle-'.date('Ymd').'.log'; //生成日志文件名
        if(self::$logFile === null){
            self::$logFile = fopen($logFile,'a');
        }
        $message = date('Y-m-d H:i:s -').$message; //日志格式
        fwrite(self::$logFile,$message.PHP_EOL);
    }

    /**
     * 执行本地宿主机命令
     * @param string $command
     * @return bool|int true 成功，false 失败
     */
    final public function runLocalCommand($command = ''){
        $command = trim($command);
        $this->log('-----------------------begin run command--------------------');
        $this->log('------ Executing: $ '.$command);
        $status = 1;
        $log = '';

        exec($command . ' 2>&1', $log, $status);
        $this->command = $command;
        $this->status = !$status;
        $log = implode(PHP_EOL,$log);
        $this->log = trim($log);
        $this->log($log);
        $this->log('----------------------end run command-----------------------');

        return $this->status;
    }

    /**
     * 执行远程目标机器命令
     * @param $command
     * @return bool
     */
    final public function runRemoteCommand($command){
        $this->log = '';
        $needTTY = '-T';
        
        foreach (GlobalHelper::str2arr($this->getConfig()->hosts) as $remoteHost){
            $localCommand = sprintf('ssh %s -p %d -q -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no -o CheckHostIp=false %s@%s %s',
                $needTTY,
                $this->getHostPort($remoteHost),
                escapeshellarg($this->getConfig()->release_user),
                escapeshellarg($this->getHostName($remoteHost)),
                escapeshellarg($command)
                );
            static::log('Run remote command '.$command);
            $log = $this->log;
            $this->status = $this->runLocalCommand($localCommand);

            $this->log = $log.($log?PHP_EOL:'').$remoteHost.' : '.$this->log;
            if(!$this->status) return false;
        }
        return true;
    }

    /**
     * 返回host名称
     * @param $host
     * @return mixed
     */
    public function getHostName($host){
        list($hostName,) = explode(':',$host);
        return $hostName;
    }

    /**
     * 返回服务器端口号
     * @param     $host
     * @param int $default
     * @return int
     */
    public function getHostPort($host, $default = 22){
        $hostInfo = explode(':',$host);
        return !empty($hostInfo[1])?$hostInfo[1]:$default;
    }

    /**
     * 获取执行command
     * @return string
     */
    public function getExeCommand(){
        return $this->command;
    }
}