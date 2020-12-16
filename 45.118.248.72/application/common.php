<?php

use app\common\model\Config as ConfigModel;
use app\common\model\ConveyMatch as ConveyMatchModel;
use app\common\model\Message as MessageModel;
use app\common\model\UserLevel as UserLevelModel;
use app\common\model\Users as UsersModel;
use PHPMailer\PHPMailer\PHPMailer;
use \think\facade\Lang;

/**
 * [get_convey_match 获取特殊刷单匹配额度]
 * @param  integer $frequency [次数]
 * @return [type]             [description]
 */
function get_convey_match($frequency = 0)
{
    $matchs = cache('matchs');
    if (empty($matchs)) {
        $matchs = [];
        $lists  = ConveyMatchModel::order('frequency ASC')->select();
        foreach ($lists as $key => $value) {
            $matchs[$value['frequency']] = $value;
        }
        unset($lists);
        cache('matchs', $matchs);
    }
    if (empty($frequency)) {
        return $matchs;
    } else {
        if (empty($matchs[$frequency])) {
            return $matchs[0];
        } else {
            return $matchs[$frequency];
        }
    }
}

/**
 * [numFilter 保留X位小数点]
 *
 * @method numFilter
 * @param  float      $value  [源數字]
 * @param  integer    $length [保留位數]
 * @param  string     $type   [保留類型]
 * @return [type]             [description]
 *
 * @Author Seamless
 * @date   2019-10-07
 *
 */
function numFilter($value, $length = 5, $type = 'floor')
{
    $n = 1;
    for ($i = 0; $i < $length; $i++) {
        $n .= '0';
    }
    $n       = (int) $n;
    $realVal = 0;
    switch ($type) {
        case 'ceil': // 向上取整;有小数就整数部分加1
            $realVal = ceil($value * $n) / $n;
            break;
        case 'round': // 四舍五入
            $realVal = round($value * $n) / $n;
            break;
        default: // 默認向下取整
            $realVal = floor($value * $n) / $n;
            break;
    }
    return $realVal;
}

if (!function_exists('format_datetime')) {
    /**
     * 日期格式标准输出
     * @param string $datetime 输入日期
     * @param string $format 输出格式
     * @return false|string
     */
    function format_datetime($datetime, $format = 'Y-m-d H:i:s')
    {
        if (empty($datetime)) {
            return '-';
        }

        if (is_numeric($datetime)) {
            return date($format, $datetime);
        } else {
            return date($format, strtotime($datetime));
        }
    }
}

/**
 * [getUser 获取指定用户信息]
 * @param  [type] $uid [description]
 * @return [type]      [description]
 */
function getUser($uid)
{
    $map = [
        ['id', '=', $uid],
    ];
    $user = UsersModel::where($map)->find();
    if (!empty($user)) {
        return $user->toArray();
    }
    return [];
}

/**
 * [getParents 获取用户上层]
 * @param  integer $uid [指定用户ID]
 * @return [type]       [description]
 */
function getParents($uid = 0)
{
    $user   = UsersModel::where('id', $uid)->find()->toArray();
    $list   = [];
    $list[] = $user;
    do {
        $parentid = $user['parent_id'];
        if ($parentid > 0) {
            $user   = UsersModel::where('id', $parentid)->find()->toArray();
            $list[] = $user;
        }
    } while ($parentid > 0);
    return $list;
}

/**
 * [formatNumber 通过千位分组来格式化数字]
 * 注释：该函数支持一个、两个或四个参数（不是三个）。
 * @param  [type]  $number       [必需。要格式化的数字。如果未设置其他参数，则数字会被格式化为不带小数点且以逗号（,）作为千位分隔符。]
 * @param  integer $decimals     [可选。规定多少个小数。如果设置了该参数，则使用点号（.）作为小数点来格式化数字。]
 * @param  string  $decimalpoint [可选。规定用作小数点的字符串。]
 * @param  string  $separator    [可选。规定用作千位分隔符的字符串。仅使用该参数的第一个字符。比如 "xxx" 仅输出 "x"。注释：如果设置了该参数，那么所有其他参数都是必需的。]
 * @return [type]                [description]
 */
function formatNumber($number, $decimals = 2, $decimalpoint = '.', $separator = ',')
{
    return number_format($number, $decimals, $decimalpoint, $separator);
}

/**
 * 手机号格式化
 * 15109876543=>151-0987-6543
 * @author d
 */
function mobile_format($mobile)
{
    $new = '';
    for ($i = 0; $i < strlen($mobile); $i++) {
        if ($i == 3 || $i == 7) {
            $new .= '-' . $mobile[$i];
        } else {
            $new .= $mobile[$i];
        }
    }
    return $new;
}

function getinvite_pic($uid)
{
    $user = Db::table('xy_users')->where('id', $uid)->find();
    if (empty($user['invite_pic']) || !file_exists('.' . $user['invite_pic'])) {
        model('common/Users')->create_qrcode($user['invite_code'], $user['id']);
        $user = Db::table('xy_users')->where('id', $uid)->find();
    }
    return $user['invite_pic'];
}

/**
 * [getRange 获取当前配置语言]
 * @return [type] [description]
 */
function getRange()
{
    return Lang::range();
}

/**
 * 校验海外手机号码格式
 * @param  {String} $number  [手机号]
 * @param  {String} $iso     [手机区号]
 * @return {[bool]}
 */
function extract_phone_number($number, $iso = 'JP')
{
    return true;
    $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
    try {
        $swissNumberProto = $phoneUtil->parse($number, $iso);
        $isValid          = $phoneUtil->isValidNumber($swissNumberProto);
        if ($isValid) {
            return true;
        } else {
            return false;
        }
    } catch (\libphonenumber\NumberParseException $e) {
        return false;
    }
}

function is_mobile($tel)
{
    if (preg_match("/^1[345789]{1}\d{9}$/", $tel)) {
        return true;
    } else {
        return false;
    }
}

/**
 * [sys_config 获取指定系统配置参数]
 * @param  [type] $name [description]
 * @return [type]       [description]
 */
function sys_config($name = null)
{
    $system_config = cache('system_config');
    if (empty($system_config)) {
        // $list          = db('system_config')->cache(true)->select();
        $list          = ConfigModel::cache(true)->select()->toArray();
        $system_config = [];
        foreach ($list as $key => $value) {
            $system_config[$value['name']] = $value['value'];
        }
        unset($list);
        cache('system_config', $system_config);
    }
    return $name ? $system_config[$name] : $system_config;
}

/**
 * [lang_list 获取语言列表]
 * @param  boolean $default [description]
 * @return [type]           [description]
 */
function lang_list($default = true)
{
    $lang_list = cache('lang_list');
    if (empty($lang_list)) {
        $list      = db('system_range')->cache(true)->select();
        $lang_list = [];
        foreach ($list as $key => $value) {
            $lang_list['list'][$value['name']] = $value['value'];
            $lang_list['allow'][]              = $value['name'];
            if ($value['default'] == 1) {
                $lang_list['default'] = $value['name'];
            }
        }
        unset($list);
        cache('lang_list', $lang_list);
    }
    return $default ? $lang_list['default'] : $lang_list;
}

/**
 * 获取语言变量值
 * @param string    $name  语言变量名
 * @param array     $vars  动态变量值
 * @param string    $range 语言
 * @return mixed
 */
function lang($name = '', $vars = [], $range = '', $type = 0)
{
    // 设置允许的语言，从数据库读取
    Lang::setAllowLangList(lang_list(false)['allow']);
    // 设置当前语言，数据库的默认语言
    Lang::range(lang_list(false)['default']);
    // 没有定义语言，开启识别
    if ('' == $range) {
        $range = getRange();
    }
    $lang = cache('lang-' . $type);
    if (empty($lang)) {
        if (!empty($type)) {
            $list = db('system_lang')->where('type', $type)->cache(true)->select();
        } else {
            $list = db('system_lang')->cache(true)->select();
        }
        $lang = [];
        foreach ($list as $key => $value) {
            $lang[$value['range']][$value['name']] = $value['content'];
        }
        unset($list);
        cache('lang-' . $type, $lang);
    }

    // 空参数返回所有定义
    if (is_null($name)) {
        return $range ? $lang[$range] : $lang;
    }

    $value = isset($lang[$range][$name]) ? $lang[$range][$name] : $name;

    // 变量解析
    if (!empty($vars) && is_array($vars)) {
        $arrs = $vars;
        if (is_object($vars)) {
            $arrs = json_decode(json_encode($vars), true);
        }
        foreach ($arrs as $key => $v) {
            if (is_numeric($v)) {
                $v = floatval($v);
                if (is_float($v) || strpos($v, '.') !== false) {
                    $len = getFloatLength($v);
                    if ($len > 2) {
                        $len = 2;
                    }
                    $vv = number_format($v, $len);
                } else {
                    $vv = number_format($v, 0);
                }
                $arrs[$key] = $vv;
            }
        }
        /**
         * Notes:
         * 为了检测的方便，数字索引的判断仅仅是参数数组的第一个元素的key为数字0
         * 数字索引采用的是系统的 sprintf 函数替换，用法请参考 sprintf 函数
         */
        if (key($arrs) === 0) {
            // 数字索引解析
            array_unshift($arrs, $value);
            $value = call_user_func_array('sprintf', $arrs);
        } else {
            // 关联索引解析
            $replace = array_keys($arrs);
            foreach ($replace as &$v) {
                $v = "{:{$v}}";
            }
            $value = str_replace($replace, $arrs, $value);
        }
        // /**
        //  * Notes:
        //  * 为了检测的方便，数字索引的判断仅仅是参数数组的第一个元素的key为数字0
        //  * 数字索引采用的是系统的 sprintf 函数替换，用法请参考 sprintf 函数
        //  */
        // if (key($vars) === 0) {
        //     // 数字索引解析
        //     array_unshift($vars, $value);
        //     $value = call_user_func_array('sprintf', $vars);
        // } else {
        //     // 关联索引解析
        //     $replace = array_keys($vars);
        //     foreach ($replace as &$v) {
        //         $v = "{:{$v}}";
        //     }
        //     $value = str_replace($replace, $vars, $value);
        // }
    }

    return $value;
}

/**
 * [get_msg_tpl 获取指定消息模版]
 * @param  string $name  [模版索引]
 * @param  string $range [模版语言]
 * @return [type]        [description]
 */
function get_msg_tpl($name, $range = '')
{
    // 没有定义语言，开启识别
    if ('' == $range) {
        $range = getRange();
    }
    $tpl = cache('msg_tpl');
    if (!isset($tpl[$range]) && !isset($tpl[$range][$name]) && empty($tpl[$range][$name])) {
        $list = db('system_message_tpl')->cache(true)->select();
        $tpl  = [];
        foreach ($list as $key => $value) {
            $tpl[$value['range']][$value['name']] = $value;
        }
        unset($list);
        cache('msg_tpl', $tpl);
    }
    return $tpl[$range][$name];
}

/**
 * [get_levels 查询等级规则]
 * @param  integer $level [指定等级]
 * @return [type]         [description]
 */
function get_levels($level = null)
{
    $levels = cache('user_level');
    if (empty($levels)) {
        $levels = [];
        $list   = UserLevelModel::order('level asc')->select();
        foreach ($list as $key => $value) {
            $levels[$value['level']] = $value;
        }
        unset($list);
        cache('user_level', $levels);
    }
    return ($level === null) ? $levels : $levels[$level];
}

function getFloatLength($num)
{
    $count = 0;
    $temp  = explode('.', $num);
    if (sizeof($temp) > 1) {
        $decimal = end($temp);
        $count   = strlen($decimal);
    }
    return $count;
}

/**
 * [send_msg 发送消息给用户]
 * @param  integer $uid     [用户ID]
 * @param  string  $title   [消息标题]
 * @param  string  $content [消息内容]
 * @param  integer $special [是否弹窗]
 * @param  string  $url     [跳转链接]
 * @return [type]           [description]
 */
function send_msg($uid = 0, $title = '', $content = '', $tip = 0, $special = 0, $url = '')
{
    $data = [];
    if (!is_array($uid)) {
        $data = [
            'uid'       => $uid,
            'type'      => 2,
            'title'     => $title,
            'content'   => $content,
            'have_read' => 0,
            'tip'       => $tip,
            'special'   => $special,
            'url'       => $url,
        ];
        $res = MessageModel::create($data);
        // if (!empty($url)) {
        //     $data['url'] = $url . '?mid=' . $res['id'];
        //     MessageModel::where('id', $res['id'])->update(['url' => $data['url']]);
        // }
    } else {
        $data = [];
        foreach ($uid as $key => $id) {
            $data[] = [
                'uid'       => $id,
                'type'      => 2,
                'title'     => $title,
                'content'   => $content,
                'have_read' => 0,
                'tip'       => $tip,
                'special'   => $special,
                'url'       => $url,
            ];
            // $res = MessageModel::create($data);
            // if (!empty($url)) {
            //     $data['url'] = $url . '?mid=' . $res['id'];
            //     MessageModel::where('id', $res['id'])->update(['url' => $data['url']]);
            // }
        }
        $res = (new MessageModel)->saveAll($data);
    }
    return $res;
}

/**
 * [sendMsg 发送通知]
 * @param  integer $tplid    [模版ID]
 * @param  integer $uid      [用户ID;0:所有用户]
 * @param  array   $title    [标题替换参数]
 * @param  array   $content  [内容替换参数]
 * @return [type]            [description]
 */
function sendMsg($tplid, $uid = 0, $title = [], $content = [])
{
    $tpl  = db('system_message_tpl')->cache(true)->find($tplid);
    $data = [
        'type'    => 2,
        'addtime' => time(),
    ];
    if ($uid != 0) {
        $data['uid'] = $uid;
    }
    if (!empty($title) && is_array($title)) {
        $value = $tpl['title'];
        array_unshift($title, $value);
        $data['title'] = call_user_func_array('sprintf', $title);
    } else {
        $data['title'] = $tpl['title'];
    }
    if (!empty($content) && is_array($content)) {
        $value = $tpl['content'];
        array_unshift($content, $value);
        $data['content'] = call_user_func_array('sprintf', $content);
    } else {
        $data['content'] = $tpl['content'];
    }
    $res = Db::name('xy_message')->insert($data);
    return $res;
}

/**
 * [_sprintf 内容替换]
 * @param [type] $value [原字符串]
 * @param array  $vars  [替换内容]
 */
function _sprintf($value, $vars = [])
{
    if (!empty($vars) && is_array($vars)) {
        array_unshift($vars, $value);
        return call_user_func_array('sprintf', $vars);
    } else {
        return $value;
    }
    return '';
}

/**
 * [curl_down 下載文件]
 * @param  [type]  $url     [description]
 * @param  string  $file    [description]
 * @param  integer $timeout [description]
 * @return [type]           [description]
 */
function curl_down($url, $file = '', $timeout = 60)
{
    $file = empty($file) ? pathinfo($url, PATHINFO_BASENAME) : $file;
    $dir  = pathinfo($file, PATHINFO_DIRNAME);
    !is_dir($dir) && @mkdir($dir, 0755, true);
    $url       = str_replace(' ', "%20", $url);
    $result    = array('fileName' => '', 'way' => '', 'size' => 0, 'spendTime' => 0);
    $startTime = explode(' ', microtime());
    $startTime = (float) $startTime[0] + (float) $startTime[1];
    if (function_exists('curl_init')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $temp = curl_exec($ch);
        if (@file_put_contents($file, $temp) && !curl_error($ch)) {
            $result['fileName'] = $file;
            $result['way']      = 'curl';
            $result['size']     = sprintf('%.3f', strlen($temp) / 1024);
        }
    } else {
        $opts = array(
            'http' => array(
                'method'  => 'GET',
                'header'  => '',
                'timeout' => $timeout,
            ),
        );
        $context = stream_context_create($opts);
        if (@copy($url, $file, $context)) {
            $result['fileName'] = $file;
            $result['way']      = 'copy';
            $result['size']     = sprintf('%.3f', strlen($context) / 1024);
        }
    }
    $endTime             = explode(' ', microtime());
    $endTime             = (float) $endTime[0] + (float) $endTime[1];
    $result['spendTime'] = round($endTime - $startTime) * 1000; //单位：毫秒
    return $result;
}

/**
 * [curl_get curl發送get請求]
 * @param  [type] $url [description]
 * @return [type]      [description]
 */
function curl_get($url)
{
    $options = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER         => false,
        CURLOPT_POST           => false,
    );
    $ch = curl_init($url);
    curl_setopt_array($ch, $options);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

/**
 * [curlRequest CURL請求]
 * @param  String  $url     [請求地址]
 * @param  array   $data    [POST數據]
 * @param  array   $cookie  [提交的cookies]
 * @param  array   $header  [頭信息]
 * @param  integer $timeout [超時時間]
 * @return [type]           [description]
 */
function curlRequest($url, $data = array(), $referer = '', $cookie = '', $header = array(), $timeout = 120)
{
    $urlinfo = parse_url($url);
    $curl    = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    if (isset($urlinfo['scheme']) && $urlinfo['scheme'] == 'https') {
        // 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
        // 如果在部署过程中代码在此处验证失败，请到 http://curl.haxx.se/ca/cacert.pem 下载新的证书判别文件。
        // https请求 不验证证书和hosts
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        // 从证书中检查SSL加密算法是否存在(默认不需要验证）
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    }
    if (!empty($referer)) {
        curl_setopt($curl, CURLOPT_REFERER, $referer);
    }
    if (!empty($data)) {
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    }
    if (!empty($data)) {
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    }
    if (!empty($cookie)) {
        curl_setopt($curl, CURLOPT_COOKIE, $cookie);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
    $output = curl_exec($curl);
    curl_close($curl);
    return $output;
}

/**
 * [spliceString 拼接指定字符串]
 *
 * @method spliceString
 * @param  array          $type      [类型,0:数字;1:小写字母;2:大写字母;3:特殊符号;]
 * @param  string         $customize [自定义字符串]
 * @return [type]                    [description]
 *
 * echo spliceString([0,1], 'asdw');
 *
 * @Author Seamless
 * @date   2019-04-08
 *
 */
function spliceString($type = [0, 1], $customize = '')
{
    $char = [
        '0123456789',
        'abcdefghijklmnopqrstuvwxyz',
        'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
        '~!@#$%^&*',
    ];
    $characters = '';
    foreach ($type as $key => $value) {
        $characters .= $char[$value];
    }
    $characters .= $customize;
    return $characters;
}

/**
 * [hiddenCharacter 自定义函数隐藏后面X位]
 * @param  [type] $str [description]
 * @return [type]      [description]
 */
function hiddenCharacter($str, $slen = 1, $elen = -1)
{
    $str    = $str;
    $abc    = substr($str, $elen);
    $resstr = substr_replace($str, '****', $slen) . $abc;
    return $resstr;
}

function send_sms($value = '')
{
    # code...
}

/**
 * [sendMail_bt 宝塔发送邮件]
 *
 * @method sendMail
 * @param  array      $configs    [邮箱配置信息]
 * @param  string     $to         [收件人]
 * @param  string     $name       [收件人名称]
 * @param  string     $subject    [邮件主题]
 * @param  string     $body       [邮件内容]
 * @param  [type]     $attachment [附件列表]
 * @return [type]                 [description]
 *
 * @Author Seamless
 * @date   2019-09-28
 *
 */
function sendMail_bt($configs, $to, $name, $subject, $body, $attachment = null)
{
    # 要调用的发件接口地址，例如http://192.168.1.11:8888/mail_sys/send_mail_http.json
    $url  = $configs['api'];
    $data = [
        'mail_from' => $configs['from'],
        'password'  => $configs['password'],
        'mail_to'   => $to,
        'subject'   => $subject,
        'content'   => $body,
        'subtype'   => $configs['subtype'] ?? '',
    ];
    return curlRequest($url, $data);
}

/**
 * [sendMail 发送邮件]
 *
 * @method sendMail
 * @param  array      $configs    [邮箱配置信息]
 * @param  string     $to         [收件人]
 * @param  string     $name       [收件人名称]
 * @param  string     $subject    [邮件主题]
 * @param  string     $body       [邮件内容]
 * @param  [type]     $attachment [附件列表]
 * @return [type]                 [description]
 *
 * @Author Seamless
 * @date   2019-09-28
 *
 */
function sendMail($configs, $to, $name, $subject, $body, $attachment = null)
{
    $config = [
        // 设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
        'charset'  => 'utf-8',
        // SMTP调试功能 0 = 关闭 1 = 错误和消息 2 = 消息
        'debug'    => 1,
        // 协议 Options: '', 'ssl' or 'tls'
        'secure'   => $configs['secure'],
        // SMTP 服务器
        'host'     => $configs['host'],
        // SMTP 服务器 端口
        'port'     => $configs['port'],
        // 是否需要认证
        'auth'     => true,
        // 用户名
        'username' => $configs['username'],
        // 密码
        'password' => $configs['password'],
        // 来源地址 QQ邮箱必须设置和用户名相同
        'from'     => $configs['from'],
        // 来源名称
        'fromname' => sys_config('site_name'),
    ];
    $mail          = new PHPMailer();
    $mail->CharSet = $config['charset'];
    $mail->isSMTP();
    $mail->SMTPDebug = $config['debug'];

    $mail->SMTPSecure = $config['secure']; // 使用安全
    $mail->Host       = $config['host']; //
    $mail->Port       = $config['port']; // SMTP服务器的端口号

    if ($config['auth']) {
        $mail->SMTPAuth = true; // 启用 SMTP 验证功能
        $mail->Username = $config['username']; // SMTP服务器用户名
        $mail->Password = $config['password']; // SMTP服务器密码
    }

    $mail->setFrom($config['from'], $config['fromname']);
    $mail->Subject = $subject;
    $mail->msgHTML($body);
    $mail->addAddress($to, empty($name) ? $to : $name);
    if (is_array($attachment)) {
        // 添加附件
        foreach ($attachment as $file) {
            is_file($file) && $mail->addAttachment($file);
        }
    }
    $result = $mail->send() ? true : $mail->ErrorInfo;
    return $result;
}

/**
 * [send_mail 发送邮件]
 *
 * @method send_mail
 * @param  array      $configs    [邮箱配置信息]
 * @param  string     $email      [收件人]
 * @param  string     $name       [收件人名称]
 * @param  string     $subject    [邮件主题]
 * @param  string     $body       [邮件内容]
 * @param  [type]     $attachment [附件列表]
 * @return [type]                 [description]
 *
 * @Author Seamless
 * @date   2019-09-28
 *
 */
function send_mail($configs, $email, $name, $subject, $body, $attachment = null)
{
    $config = [
        // 设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
        'charset'  => 'utf-8',
        // SMTP调试功能 0 = 关闭 1 = 错误和消息 2 = 消息
        'debug'    => 0,
        // 协议 Options: '', 'ssl' or 'tls'
        'secure'   => $configs['email_secure'],
        // SMTP 服务器
        'host'     => $configs['email_host'],
        // SMTP 服务器 端口
        'port'     => $configs['email_port'],
        // 是否需要认证
        'auth'     => true,
        // 用户名
        'username' => $configs['email_id'],
        // 密码
        'password' => $configs['email_pass'],
        // 来源地址 QQ邮箱必须设置和用户名相同
        'from'     => $configs['email_addr'],
        // 来源名称
        'fromname' => $configs['site_name'],
    ];
    $mail          = new PHPMailer();
    $mail->CharSet = $config['charset'];
    $mail->isSMTP();
    $mail->SMTPDebug = $config['debug'];

    $mail->SMTPSecure = $config['secure']; // 使用安全
    $mail->Host       = $config['host']; //
    $mail->Port       = $config['port']; // SMTP服务器的端口号

    if ($config['auth']) {
        $mail->SMTPAuth = true; // 启用 SMTP 验证功能
        $mail->Username = $config['username']; // SMTP服务器用户名
        $mail->Password = $config['password']; // SMTP服务器密码
    }

    $mail->setFrom($config['from'], $config['fromname']);
    $mail->Subject = $subject;
    $mail->msgHTML($body);
    $mail->addAddress($email, empty($name) ? $email : $name);
    if (is_array($attachment)) {
        // 添加附件
        foreach ($attachment as $file) {
            is_file($file) && $mail->addAttachment($file);
        }
    }
    $result = $mail->send() ? true : $mail->ErrorInfo;
    return $result;
}

/*
 * 检查图片是不是bases64编码的
 */
function is_image_base64($base64)
{
    if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64, $result)) {
        return true;
    } else {
        return false;
    }
}

function check_pic($dir, $type_img)
{
    $new_files = $dir . date("YmdHis") . '-' . rand(0, 9999999) . "{$type_img}";
    if (!file_exists($new_files)) {
        return $new_files;
    } else {
        return check_pic($dir, $type_img);
    }

}

/**
 * 获取数组中的某一列
 * @param array $arr 数组
 * @param string $key_name  列名
 * @return array  返回那一列的数组
 */
function get_arr_column($arr, $key_name)
{
    $arr2 = array();
    foreach ($arr as $key => $val) {
        $arr2[] = $val[$key_name];
    }
    return $arr2;
}

//保留两位小数
function tow_float($number)
{
    return (floor($number * 100) / 100);
}

//生成订单号
function getSn($head = '')
{
    @date_default_timezone_set("PRC");
    $order_id_main = date('YmdHis') . mt_rand(10000, 99999);
    //唯一订单号码（YYMMDDHHIISSNNN）
    $osn = $head . substr($order_id_main, 2); //生成订单号
    return $osn;
}

/**
 * 修改本地配置文件
 *
 * @param array $name   ['配置名']
 * @param array $value  ['参数']
 * @return void
 */
function setconfig($name, $value)
{
    if (is_array($name) and is_array($value)) {
        for ($i = 0; $i < count($name); $i++) {
            $names[$i]  = '/\'' . $name[$i] . '\'(.*?),/';
            $values[$i] = "'" . $name[$i] . "'" . "=>" . "'" . $value[$i] . "',";
        }
        $fileurl = APP_PATH . "../config/app.php";
        $string  = file_get_contents($fileurl); //加载配置文件
        $string  = preg_replace($names, $values, $string); // 正则查找然后替换
        file_put_contents($fileurl, $string); // 写入配置文件
        return true;
    } else {
        return false;
    }
}

//生成随机用户名
function get_username()
{
    $chars1   = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $chars2   = "abcdefghijklmnopqrstuvwxyz0123456789";
    $username = "";
    for ($i = 0; $i < mt_rand(2, 3); $i++) {
        $username .= $chars1[mt_rand(0, 25)];
    }
    $username .= '_';

    for ($i = 0; $i < mt_rand(4, 6); $i++) {
        $username .= $chars2[mt_rand(0, 35)];
    }
    return $username;
}

/**
 * 判断当前时间是否在指定时间段之内
 * @param integer $a 起始时间
 * @param integer $b 结束时间
 * @return boolean
 */
function check_time($a, $b)
{
    $nowtime = time();
    $start   = strtotime($a . ':00:00');
    $end     = strtotime($b . ':00:00');
    if ($nowtime >= $end || $nowtime <= $start) {
        return true;
    } else {
        return false;
    }
}
