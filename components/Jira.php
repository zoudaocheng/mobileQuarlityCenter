<?php
/**
 * Created by PhpStorm.
 * User: daocheng
 * Date: 17-7-31
 * Time: 上午11:39
 */

namespace app\components;


class Jira
{
    public static function jira(){
        $jira  = \Yii::$app->params['jira'];
        return $jira;
    }

    public static function search($data = array()){
        $issues = [];
        $ch = curl_init();
        $url = self::jira()['url'].'rest/api/2/search?jql=';
        while (list($key,$value) = each($data)){
            $url = $url.$key.'='.$value.'&';
        }
        if(isset($data['cookie']))
        {
            curl_setopt($ch,CURLOPT_COOKIE,$data['cookie']);//使用cookie
            unset($data['cookie']);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-type: application/json',
        ));
        curl_setopt($ch, CURLOPT_USERPWD, self::jira()['username'].':'.self::jira()['password']);
        //curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        $timeout = 5;
        curl_setopt ($ch, CURLOPT_URL,$url);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $document = curl_exec($ch);//执行预定义的CURL
        $pre = json_decode($document);
        if ($pre && isset($pre->issues)){
            foreach ($pre->issues as $issue){
                array_push($issues,[
                    'key' => $issue->key,
                    'uri' => self::jira()['url'].'browse/'.$issue->key,
                    'summary' => $issue->fields->summary,
                    'description' => $issue->fields->description,
                    'type' => $issue->fields->issuetype->name,
                    'resolution' => isset($issue->fields->resolution->name)?$issue->fields->resolution->name:'未解决',
                    'source' => isset($issue->fields->customfield_10301)?$issue->fields->customfield_10301:null,
                    'priority' => $issue->fields->priority->name,
                    'status' => $issue->fields->status->name,
                    'reporter' => $issue->fields->reporter->displayName,
                    'assignee' => isset($issue->fields->assignee->displayName)?$issue->fields->assignee->displayName:'未分配',
                    'suggestion' => isset($issue->fields->customfield_10300)?$issue->fields->customfield_10300:null,
                ]);
            }
        }
        return json_encode($issues);
    }

    /**
     * update issues
     * @param array $data
     */
    public static function edit($data = array()){
        $ret = [];
        $url = self::jira()['url'].'rest/api/2/issue/';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERPWD, self::jira()['username'].':'.self::jira()['password']);
        while (list($key,$value) = each($data)){
            $url = $url.$key;
            curl_setopt($ch,CURLOPT_URL, $url);
            curl_setopt ($ch,CURLOPT_HTTPHEADER, array('Content-type:application/json'));
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); //设为TRUE把curl_exec()结果转化为字串，而不是直接输出
            curl_setopt($ch,CURLOPT_CUSTOMREQUEST,'PUT');
            curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($value));
            $document = curl_exec($ch);
            $info = curl_getinfo($ch);
            $data = array(
                'HTTP_CODE' => $info['http_code'],
                'TOTAL_TIME' => $info['total_time'],
                'RETURN' => !curl_errno($ch)?json_decode($document)?json_decode($document):$document:$document,
            );
            array_push($ret,[$key => $info['http_code']]);
            curl_close($ch);
        }
        return json_encode($ret);
    }

    public static function put($data){
        $url = self::jira()['url'].'rest/api/2/issue/'.$data['key'].'/transitions';
        $data = json_encode($data);
        $ch = curl_init(); //初始化CURL句柄
        curl_setopt($ch, CURLOPT_URL, $url); //设置请求的URL
        curl_setopt($ch, CURLOPT_USERPWD, self::jira()['username'].':'.self::jira()['password']);
        curl_setopt ($ch, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); //设为TRUE把curl_exec()结果转化为字串，而不是直接输出
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"PUT"); //设置请求方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);//设置提交的字符串
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    /**
     * release and archive a version
     * $releaseDate formate : YYYY-mm-dd
     * @param array $data = ['id' => $id,'archived' => true,'released' => true,'releaseDate' => $releaseDate,'overdue' => true];
     * @return mixed
     */
    public static function release($data = array()){
        $url = self::jira()['url'].'rest/api/2/version/'.$data['id'];
        $ret = Soa::fetch($url,$data,'PUT');
        return $ret;
    }

    /**
     * 关闭issue
     * @param array $issues
     * @return null
     */
    public static function closeIssue($issues = []) {
        $ret = [];
        if ($issues && is_array($issues)) {
            foreach ($issues as $issue){
                if ('Closed' != $issue->status){
                    $url = self::jira()['url'].'rest/api/2/issue/'.$issue->key.'/transitions';
                    switch ($issue->resolution){
                        case 'Fixed':
                            $data = [
                                'key' => $issue->key,
                                'transition' => ['id' => 701]
                            ];
                            break;
                        case 'Done':
                            $data = [
                                'key' => $issue->key,
                                'transition' => ['id' => 701]
                            ];
                            break;
                        case 'In progress':
                            $data = [
                                'key' => $issue->key,
                                'transition' => ['id' => 2],
                                'fields' => ['resolution' => ['name' => 'Fixed']]
                            ];
                            break;
                        default:
                            $data = [
                                'key' => $issue->key,
                                'transition' => ['id' => 2]
                            ];
                    }
                    Soa::fetch($url,$data,'POST');
                    array_push($ret,[$issue->key => 'DONE']);
                }
            }
        }
        return $ret;
    }

    /**
     * 根据传入的版本号获取版本信息
     * @param string $version
     * @return null
     */
    public static function getVersionInfo($version = ''){
        $versions = explode('.',trim($version));
        $ret = [];
        if (in_array('h5',$versions) || in_array('soa',$versions) || in_array('rn',$versions)) {
            $url = self::jira()['url'].'rest/api/2/project/LCB/versions';
            $_url = self::jira()['url'].'browse/LCB/fixforversion/';
        } elseif (in_array('lcb',$versions) || in_array('operation',$versions) || in_array('zonghui',$versions)){
            $url = self::jira()['url'].'rest/api/2/project/OP/versions';
            $_url = self::jira()['url'].'browse/OP/fixforversion/';
        } elseif (in_array('store',$versions)){
            $url = self::jira()['url'].'rest/api/2/project/STORE/versions';
            $_url = self::jira()['url'].'browse/STORE/fixforversion/';
        } else{
            $url = self::jira()['url'].'rest/api/2/project/MARKET/versions';
            $_url = self::jira()['url'].'browse/MARKET/fixforversion/';
        }
        $fixVersions = json_decode(Soa::fetch($url,'','GET'));

        if ($fixVersions && count($fixVersions)){
            foreach ($fixVersions as $fixVersion){
                if ($version == $fixVersion->name)
                    $ret = $fixVersion;
            }
        }
        if ($ret)
            $ret->uri = $_url.$ret->id;
        return $ret;
    }

    /**
     * 根据版本接口链接返回查看issue的链接
     * @param string $url
     * @return string
     */
    public static function versionToUri($url = ''){
        $_version = json_decode(Soa::fetch($url));
        $versions = explode('.',$_version->name);
        if (in_array('h5',$versions) || in_array('soa',$versions) || in_array('rn',$versions)) {
            $_url = self::jira()['url'].'browse/LCB/fixforversion/';
        } elseif (in_array('lcb',$versions) || in_array('operation',$versions) || in_array('zonghui',$versions)){
            $_url = self::jira()['url'].'browse/OP/fixforversion/';
        } elseif (in_array('store',$versions)){
            $_url = self::jira()['url'].'browse/STORE/fixforversion/';
        } elseif (in_array('php',$versions) && in_array('wechat',$versions)){
            $_url = self::jira()['url'].'browse/WECHAT/fixforversion/';
        } else{
            $_url = self::jira()['url'].'browse/MARKET/fixforversion/';
        }
        return $_url.$_version->id;
    }

    /**
     * 版本号转换
     * @param string $version
     * @return $version
     */
    public static function convertVersion($version = ''){
        if($version){
            $tmp = explode('.',trim($version)); //将版本号转为数组
            if ('soa' == $tmp[2] || in_array('tmall1',$tmp) || in_array('tmall2',$tmp) || in_array('job1',$tmp) || in_array('job2',$tmp)){
                unset($tmp[2]); //如果版本号中第三个地方再次出现，优化掉
            } elseif (in_array('project',$tmp)){
                unset($tmp[2]);
                unset($tmp[3]); //将原来的版本号php.lcb.php.project.x.x.x中的php.project去掉
            } elseif ((in_array('operation',$tmp) && 'php' == $tmp[2]) || (in_array('store',$tmp) && 'php' == $tmp[2])){
                unset($tmp[2]);
            } elseif (in_array('service',$tmp) && in_array('soa',$tmp)) {
                $key = array_search('service',$tmp);
                if ('soa' == $tmp[$key+1]){
                    unset($tmp[$key+1]); //把service后面的 soa 去掉
                }
            }
            $ret = str_replace('php.market.backend','php.market_backend',implode('.',$tmp)); //再将数组转换为字符串同时将market的版本号特殊处理一下
            $ret = str_replace('php.operation.backend','php.operation',$ret);//将operation.backend 转为operation
            $ret = str_replace('php.store.backend','php.store',$ret); //将store.backend转为 store
            $ret = str_replace('hybrid','h5',$ret);//将hybrid对应的版本改为H5的版本号
            $ret = str_replace('ios','rn',$ret);//将ios对应的版本改为react native的版本号
            $ret = str_replace('android','rn',$ret);//将android对应的版本改为react native的版本号
        } else {
            $ret = null;
        }
        return $ret;
    }

    /**
     * 获取版本计划
     */
    public static function getVersionPlan(){
        $ret = [];
        $projects = self::getProjects();
        foreach ($projects as $project){
            $versions = json_decode(Soa::fetch($project->url,'','GET'));
            foreach ($versions as $version){
                array_push($ret,$version);
            }
        }
        return $ret;
    }

    public static function getProjects(){
        $projects = [];
        $url = self::jira()['url'].'rest/api/2/project';
        $rets = json_decode(Soa::fetch($url,'','GET'));
        foreach ($rets as $ret){
            $project = [
                'id' => $ret->id,
                'key' => $ret->key,
                'name' => $ret->name,
                'url' => self::jira()['url'].'rest/api/2/project/'.$ret->key.'/versions',
                'issue_url' => self::jira()['url'].'browse/'.$ret->key.'/fixforversion/',
            ];
            array_push($projects,$project);
        }
        return $project;
    }

    /**
     * 封装给MQC提供版本转换
     * @param string $versionString
     * @return object
     */
    public static function getVersionForMQC($versionString = ''){
        $_version = str_replace('_','.',str_replace('-','.',$versionString));
        $_mVersion = $_jVersion = self::convertVersion($_version);//jira中的版本号及MQC中版本号的初始值
        $_tmp = explode('.',$_version);//传入的版本号转数组用于判断是否含有特殊项目提测的版本信息
        if (in_array('hybrid',$_tmp)){
            $_mVersion = str_replace('h5','hybrid',$_mVersion);
        }
        if (in_array('job1',$_tmp)){
            $_mVersion = str_replace('all','all.job1',$_mVersion);
        }
        if (in_array('job2',$_tmp)){
            $_mVersion = str_replace('all','all.job2',$_mVersion);
        }
        if (in_array('tmall1',$_tmp)){
            $_mVersion = str_replace('all','all.tmall1',$_mVersion);
        }
        if (in_array('tmall2',$_tmp)){
            $_mVersion = str_replace('all','all.tmall2',$_mVersion);
        }
        if (in_array('android',$_tmp)){
            $_mVersion = str_replace('rn','android',$_mVersion);
        }
        if (in_array('ios',$_tmp)){
            $_mVersion = str_replace('rn','ios',$_mVersion);
        }
        $_tmp = explode('.',$_mVersion);
        foreach ($_tmp as $key => $value){
            if (is_numeric($value))
                unset($_tmp[$key]);
        }
        $ret = [
            'jVersion' => $_jVersion, //JIRA的中的版本号,用于获取版本信息及获取issue
            'mVersion' => $_mVersion, //MQC中储存的版本号
            'project' => str_replace('php.market.backend','php.market_backend',implode('.',$_tmp)),//MQC中维护的项目前缀
        ];
        return (object)$ret;
    }
}