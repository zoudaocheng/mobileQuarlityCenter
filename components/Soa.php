<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/5/5
 * Time: 11:42
 */

namespace app\components;


class Soa
{
    public static function jira(){
        $jira  = \Yii::$app->params['jira'];
        return $jira;
    }

    /**
     * 通用请求方法(给JIRA使用)
     * @param string $url
     * @param string $data
     * @param string $method
     * @return mixed
     */
    public static function fetch($url = '',$data = '',$method = 'GET'){
        $ch = curl_init(); //初始化CURL句柄
        curl_setopt($ch,CURLOPT_URL,$url); //设置请求的URL
        curl_setopt($ch,CURLOPT_USERPWD,self::jira()['username'].':'.self::jira()['password']);
        curl_setopt ($ch,CURLOPT_HTTPHEADER,array('Content-type:application/json'));
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_CUSTOMREQUEST,$method); //设置请求方式
        curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($data));//设置提交的字符串
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    public static function put($url = '',$data = array())
    {
        $ch = curl_init();
        if(isset($data['cookie']))
        {
            curl_setopt($ch,CURLOPT_COOKIE,$data['cookie']);//使用cookie
            unset($data['cookie']);
        }
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-type: application/x-www-form-urlencoded',
        ));
        curl_setopt($ch,CURLOPT_CUSTOMREQUEST,'PUT');
        curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query($data));
        $document = curl_exec($ch);
        $info = curl_getinfo($ch);
        $data = array(
            'HTTP_CODE' => $info['http_code'],
            'TOTAL_TIME' => $info['total_time'],
            'RETURN' => !curl_errno($ch)?json_decode($document)?json_decode($document):$document:$document,
        );
        curl_close($ch);
        return $data;
    }

    public static function delete($url = '',$data = array())
    {
        $ch = curl_init();
        if(isset($data['cookie']))
        {
            curl_setopt($ch,CURLOPT_COOKIE,$data['cookie']);//使用cookie
            unset($data['cookie']);
        }
        curl_setopt($ch,CURLOPT_URL,$url);
        if($data)
        {
            curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        }
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt ( $ch, CURLOPT_HEADER, true );
        curl_setopt ( $ch, CURLOPT_TIMEOUT, 5184000 );
        curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 120 );
        curl_setopt ( $ch, CURLOPT_NOSIGNAL, true );
        curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, 'DELETE' );
        $document = curl_exec($ch);
        $info = curl_getinfo($ch);
        $data = array(
            'HTTP_CODE' => $info['http_code'],
            'TOTAL_TIME' => $info['total_time'],
            'RETURN' => !curl_errno($ch)?json_decode($document)?json_decode($document):$document:$document,
        );
        curl_close($ch);
        return $data;
    }

    public static function get($url = '',$data = array())
    {
        $ch = curl_init();//初始化CURL句柄
        if(isset($data['cookie']))
        {
            curl_setopt($ch,CURLOPT_COOKIE,$data['cookie']);//使用cookie
            unset($data['cookie']);
        }
        while(list($key,$val) = each($data))
        {
            $url = $url.$key.'='.$val.'&';
        }
        $timeout = 5;
        curl_setopt ($ch, CURLOPT_URL,$url);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $document = curl_exec($ch);//执行预定义的CURL
        $info = curl_getinfo($ch);
        $data = array(
            'HTTP_CODE' => $info['http_code'],
            'TOTAL_TIME' => $info['total_time'],
            'RETURN' => !curl_errno($ch)?json_decode($document)?json_decode($document):$document:$document,
        );
        curl_close($ch);
        return $data;
    }

    public static function post($url = '',$data = array())
    {
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_POST,1);
        if('array' !== $data && json_encode($data))
        {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-type: application/json',
            ));
            curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        }else{
            if(isset($data['cookie']))
            {
                curl_setopt($ch,CURLOPT_COOKIE,$data['cookie']);//使用cookie
                unset($data['cookie']);
            }
            curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query($data));
        }
        $document = curl_exec($ch);//执行预定义的CURL
        $info = curl_getinfo($ch);
        $data = array(
            'HTTP_CODE' => $info['http_code'],
            'TOTAL_TIME' => $info['total_time'],
            'RETURN' => !curl_errno($ch)?json_decode($document)?json_decode($document):$document:$document,
        );
        curl_close($ch);
        return $data;
    }

    /**
     * 登录返回cookie
     * @param       $url
     * @param array $data
     * @return array
     */
    public static function login($url = '',$data = array())
    {
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_HEADER,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query($data));
        $document = curl_exec($ch);//执行预定义的CURL
        preg_match('/Set-Cookie:(.*);/iU',$document,$str); //正则匹配Cookie
        $info = curl_getinfo($ch);
        $data = array(
            'HTTP_CODE' => $info['http_code'],
            'TOTAL_TIME' => $info['total_time'],
            'COOKIE' => $str?$str[1]:'',//str_replace('sid=','',substr($document,strpos($document,'sid='),40)), 获得COOKIE（SESSIONID
            'RETURN' => json_decode(substr($document,strpos($document,'{'),-1).'}'),
        );
        curl_close($ch);
        return $data;
    }

    public static function soa($soa = '')
    {
        switch ($soa)
        {
            case 'soa1':
                return 'http://m.lechebang.cn/gateway/';
                break;
            case 'soa2':
                return 'http://m2.lechebang.cn/gateway/';
                break;
            case 'soa3':
                return 'http://m3.lechebang.cn/gateway/';
                break;
            case 'soa4':
                return 'http://m4.lechebang.cn/gateway/';
                break;
            case 'soa5':
                return 'http://m5.lechebang.cn/gateway/';
                break;
            case 'pre':
                return 'http://mtest.lechebang.com/gateway/';
                break;
            default:
                return 'http://soa.tec.lcbcorp.com:10006/';
        }
    }

    public static function field($data = array())
    {
        $json = array();
        if(is_object($data))
        {
            $data = (array)$data;
        }
        foreach($data as $key => $value)
        {
            if('object' == gettype($data[$key]))
            {
                $data[$key] = ('object' == gettype($data[$key]))?(array)$data[$key]:$data[$key];
                $json[$key] = Soa::field($data[$key]);
            }elseif('array' == gettype($data[$key]) && count($data[$key]) > 0)
            {
                $json[$key] = Soa::field(array($data[$key][0]));
            }elseif(!is_null($data[$key])){
                $json[$key] = gettype($data[$key]);
            }
        }
        return $json;
    }

    public static function name($data = array())
    {
        $json = array();
        if(is_object($data))
        {
            $data = (array)$data;
        }
        foreach($data as $key => $value)
        {
            if('object' == gettype($data[$key]))
            {
                $data[$key] = ('object' == gettype($data[$key]))?(array)$data[$key]:'补充名称';
                $json[$key] = Soa::field($data[$key]);
            }elseif('array' == gettype($data[$key]) && count($data[$key]) > 0)
            {
                $json[$key] = Soa::field(array($data[$key][0]));
            }elseif(!is_null($data[$key])){
                $json[$key] = '补充名称';
            }
        }
        return $json;
    }

    public static function Dates($start,$end) {
        $dates = array();
        $dt_start = strtotime($start);
        $dt_end = strtotime($end);
        do {
            array_push($dates,date('Y-m-d', $dt_start));
        } while (($dt_start += 86400) <= $dt_end);

        return $dates;
    }

    /**
     * Method: 企业微信通知
     * @param string $url
     * @param array  $data
     * @return mixed
     */
    public static function wechat_notice($url = '',$data = []){
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
}