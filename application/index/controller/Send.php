<?php

namespace app\index\controller;

use library\Controller;

class Send extends Controller
{

    public function smsbao($tel, $code)
    {
        //----------------短信宝---------------------
        $statusStr = array(
            '0'  => lang('SMS sent successfully'),
            '-1' => lang('Incomplete parameters'),
            '-2' => lang('Server space is not supported'),
            '30' => lang('wrong password'),
            '40' => lang('Account does not exist'),
            '41' => lang('Insufficient balance'),
            '42' => lang('Account has expired'),
            '43' => lang('IP address restrictions'),
            '50' => lang('Content contains sensitive words'),
        );
        $smsapi  = "http://api.smsbao.com/";
        $user    = config('app.smsbao.user'); //短信平台帐号15196952584
        $pass    = config('app.smsbao.pass');
        $pass    = md5("$pass"); //短信平台密码
        $sign    = config('app.smsbao.sign');
        $content = lang('SMS verification msg sign', [$sign, $code]);
        $phone   = $tel; //要发送短信的手机号码
        $sendurl = $smsapi . "sms?u=" . $user . "&p=" . $pass . "&m=" . $phone . "&c=" . urlencode($content);
        $result  = file_get_contents($sendurl);

        if ($result == '0') {
            return ['status' => 1, 'msg' => lang('Sent successfully')];
        } else {
            return ['status' => 0, 'msg' => $statusStr[$result]];
        }

    }

    public function smsjian($mobile, $code)
    {

        $appid  = config('app.smsjian.appid'); //短信平台帐号15196952584
        $appkey = config('app.smsjian.appkey');

        $content = lang('SMS verification msg', [$code]);
        $url     = "http://utf8.api.smschinese.cn/?Uid=" . $appid . "&Key=" . $appkey . "&smsMob=" . $mobile . "&smsText=" . $content;
        $res     = $this->new_get($url);

        if ($res == 1) {
            return ['status' => 1, 'msg' => lang('Sent successfully')];
        } else {
            return ['status' => 0, 'msg' => lang('Failed to send')];
        }

    }

    public function new_get($url)
    {
        $ch = curl_init();
        // curl_init()需要php_curl.dll扩展
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $file_contents = curl_exec($ch);
        curl_close($ch);
        return $file_contents;
    }

}
