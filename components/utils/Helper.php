<?php
namespace Components\Utils;

use Phalcon\Mvc\User\Component;

Class Helper extends Component
{

    public function resRet($data = [], $code = 200)
    {
        $ret = ['code' => $code];
        if($code != 200)
            $ret['msg'] = $data;
        else if(!empty($data))
            $ret['data'] = $data;

        echo json_encode($ret);
        return false;
        
    }

    static function isEmail($email)
    {
        if (filter_var ($email, FILTER_VALIDATE_EMAIL ))
            return true;
         else
            return false;
    }

    static function getIp()
    {
        if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
            $ip = getenv('REMOTE_ADDR');
        } elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return preg_match ( '/[\d\.]{7,15}/', $ip, $matches ) ? $matches [0] : '';
    }

    /**
     * 验证手机号
     * @return boolean [description]
     */
    static function isMobi($mobi)
    {
        if (empty($mobi)) return false;
        return preg_match('/1[34578]{1}+\d{9}$/', $mobi);
    }

    /**
    * Luhn算法
    *
    */

   static function isCard($card)
   {
        settype($card, 'string');
        $sumTable = array(
        array(0,1,2,3,4,5,6,7,8,9),
        array(0,2,4,6,8,1,3,5,7,9));
        $sum = 0;
        $flip = 0;
        for ($i = strlen($card) - 1; $i >= 0; $i--) {
            $sum += $sumTable[$flip++ & 0x1][$card[$i]];
        }
        return $sum % 10 === 0;
    }

    function randPassword( $length = 8 )
    {
        // 密码字符集，可任意添加你需要的字符
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';

        $password = '';
        for ( $i = 0; $i < $length; $i++ ) {
            // 这里提供两种字符获取方式
            // 第一种是使用 substr 截取$chars中的任意一位字符；
            // 第二种是取字符数组 $chars 的任意元素
            // $password .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
            $password .= $chars[ mt_rand(0, strlen($chars) - 1) ];
        }
        return $password;
    }

    public function match($str, $start, $end){
        $plen = strlen($str);
        if(!preg_match("/^(([a-z]+[0-9]+)|([0-9]+[a-z]+))[a-z0-9]*$/i",$str)||$plen < $start||$plen > $end)
            return false;

        return true;
    }

    public  function setDir( $type, $id = 0 )
    {
        $data = substr(md5($type.$id), 8, 16);
        $schme1 = substr($data, 0, 2);
        $schme2 = substr($data, 2, 2);
        return   $schme1.'/'.$schme2.'/';
    }

    function userName( $first = 4, $end = 12 )
    {
        // 密码字符集，可任意添加你需要的字符
        $f = 'abcdefghijklmnopqrstuvwxyz';
        $e = '0123456789';
        $fnew = '';
        $enew = '';
        for ( $i = 0; $i < $first; $i++ ) {
            $fnew .= $f[ mt_rand(0, strlen($f) - 1) ];
        }
        for ( $i = 0; $i < $end; $i++ ) {
            $enew .= $e[ mt_rand(0, strlen($e) - 1) ];
        }
        return $fnew.$enew;
    }
}
