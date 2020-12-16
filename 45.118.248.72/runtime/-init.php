<?php 
// This cache file is automatically generated at:2020-07-20 16:17:27

 use app\common\model\Config as ConfigModel; use app\common\model\ConveyMatch as ConveyMatchModel; use app\common\model\Message as MessageModel; use app\common\model\UserLevel as UserLevelModel; use app\common\model\Users as UsersModel; use PHPMailer\PHPMailer\PHPMailer; use \think\facade\Lang; function get_convey_match($frequency = 0) { $matchs = cache('matchs'); if (empty($matchs)) { $matchs = []; $lists = ConveyMatchModel::order('frequency ASC')->select(); foreach ($lists as $key => $value) { $matchs[$value['frequency']] = $value; } unset($lists); cache('matchs', $matchs); } if (empty($frequency)) { return $matchs; } else { if (empty($matchs[$frequency])) { return $matchs[0]; } else { return $matchs[$frequency]; } } } function numFilter($value, $length = 5, $type = 'floor') { $n = 1; for ($i = 0; $i < $length; $i++) { $n .= '0'; } $n = (int) $n; $realVal = 0; switch ($type) { case 'ceil': $realVal = ceil($value * $n) / $n; break; case 'round': $realVal = round($value * $n) / $n; break; default: $realVal = floor($value * $n) / $n; break; } return $realVal; } if (!function_exists('format_datetime')) { function format_datetime($datetime, $format = 'Y-m-d H:i:s') { if (empty($datetime)) { return '-'; } if (is_numeric($datetime)) { return date($format, $datetime); } else { return date($format, strtotime($datetime)); } } } function getUser($uid) { $map = [ ['id', '=', $uid], ]; $user = UsersModel::where($map)->find(); if (!empty($user)) { return $user->toArray(); } return []; } function getParents($uid = 0) { $user = UsersModel::where('id', $uid)->find()->toArray(); $list = []; $list[] = $user; do { $parentid = $user['parent_id']; if ($parentid > 0) { $user = UsersModel::where('id', $parentid)->find()->toArray(); $list[] = $user; } } while ($parentid > 0); return $list; } function formatNumber($number, $decimals = 2, $decimalpoint = '.', $separator = ',') { return number_format($number, $decimals, $decimalpoint, $separator); } function mobile_format($mobile) { $new = ''; for ($i = 0; $i < strlen($mobile); $i++) { if ($i == 3 || $i == 7) { $new .= '-' . $mobile[$i]; } else { $new .= $mobile[$i]; } } return $new; } function getinvite_pic($uid) { $user = Db::table('xy_users')->where('id', $uid)->find(); if (empty($user['invite_pic']) || !file_exists('.' . $user['invite_pic'])) { model('common/Users')->create_qrcode($user['invite_code'], $user['id']); $user = Db::table('xy_users')->where('id', $uid)->find(); } return $user['invite_pic']; } function getRange() { return Lang::range(); } function extract_phone_number($number, $iso = 'JP') { return true; $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance(); try { $swissNumberProto = $phoneUtil->parse($number, $iso); $isValid = $phoneUtil->isValidNumber($swissNumberProto); if ($isValid) { return true; } else { return false; } } catch (\libphonenumber\NumberParseException $e) { return false; } } function is_mobile($tel) { if (preg_match("/^1[345789]{1}\d{9}$/", $tel)) { return true; } else { return false; } } function sys_config($name = null) { $system_config = cache('system_config'); if (empty($system_config)) { $list = ConfigModel::cache(true)->select()->toArray(); $system_config = []; foreach ($list as $key => $value) { $system_config[$value['name']] = $value['value']; } unset($list); cache('system_config', $system_config); } return $name ? $system_config[$name] : $system_config; } function lang_list($default = true) { $lang_list = cache('lang_list'); if (empty($lang_list)) { $list = db('system_range')->cache(true)->select(); $lang_list = []; foreach ($list as $key => $value) { $lang_list['list'][$value['name']] = $value['value']; $lang_list['allow'][] = $value['name']; if ($value['default'] == 1) { $lang_list['default'] = $value['name']; } } unset($list); cache('lang_list', $lang_list); } return $default ? $lang_list['default'] : $lang_list; } function lang($name = '', $vars = [], $range = '', $type = 0) { Lang::setAllowLangList(lang_list(false)['allow']); Lang::range(lang_list(false)['default']); if ('' == $range) { $range = getRange(); } $lang = cache('lang-' . $type); if (empty($lang)) { if (!empty($type)) { $list = db('system_lang')->where('type', $type)->cache(true)->select(); } else { $list = db('system_lang')->cache(true)->select(); } $lang = []; foreach ($list as $key => $value) { $lang[$value['range']][$value['name']] = $value['content']; } unset($list); cache('lang-' . $type, $lang); } if (is_null($name)) { return $range ? $lang[$range] : $lang; } $value = isset($lang[$range][$name]) ? $lang[$range][$name] : $name; if (!empty($vars) && is_array($vars)) { $arrs = $vars; if (is_object($vars)) { $arrs = json_decode(json_encode($vars), true); } foreach ($arrs as $key => $v) { if (is_numeric($v)) { $v = floatval($v); if (is_float($v) || strpos($v, '.') !== false) { $len = getFloatLength($v); if ($len > 2) { $len = 2; } $vv = number_format($v, $len); } else { $vv = number_format($v, 0); } $arrs[$key] = $vv; } } if (key($arrs) === 0) { array_unshift($arrs, $value); $value = call_user_func_array('sprintf', $arrs); } else { $replace = array_keys($arrs); foreach ($replace as &$v) { $v = "{:{$v}}"; } $value = str_replace($replace, $arrs, $value); } } return $value; } function get_msg_tpl($name, $range = '') { if ('' == $range) { $range = getRange(); } $tpl = cache('msg_tpl'); if (!isset($tpl[$range]) && !isset($tpl[$range][$name]) && empty($tpl[$range][$name])) { $list = db('system_message_tpl')->cache(true)->select(); $tpl = []; foreach ($list as $key => $value) { $tpl[$value['range']][$value['name']] = $value; } unset($list); cache('msg_tpl', $tpl); } return $tpl[$range][$name]; } function get_levels($level = null) { $levels = cache('user_level'); if (empty($levels)) { $levels = []; $list = UserLevelModel::order('level asc')->select(); foreach ($list as $key => $value) { $levels[$value['level']] = $value; } unset($list); cache('user_level', $levels); } return ($level === null) ? $levels : $levels[$level]; } function getFloatLength($num) { $count = 0; $temp = explode('.', $num); if (sizeof($temp) > 1) { $decimal = end($temp); $count = strlen($decimal); } return $count; } function send_msg($uid = 0, $title = '', $content = '', $tip = 0, $special = 0, $url = '') { $data = []; if (!is_array($uid)) { $data = [ 'uid' => $uid, 'type' => 2, 'title' => $title, 'content' => $content, 'have_read' => 0, 'tip' => $tip, 'special' => $special, 'url' => $url, ]; $res = MessageModel::create($data); } else { $data = []; foreach ($uid as $key => $id) { $data[] = [ 'uid' => $id, 'type' => 2, 'title' => $title, 'content' => $content, 'have_read' => 0, 'tip' => $tip, 'special' => $special, 'url' => $url, ]; } $res = (new MessageModel)->saveAll($data); } return $res; } function sendMsg($tplid, $uid = 0, $title = [], $content = []) { $tpl = db('system_message_tpl')->cache(true)->find($tplid); $data = [ 'type' => 2, 'addtime' => time(), ]; if ($uid != 0) { $data['uid'] = $uid; } if (!empty($title) && is_array($title)) { $value = $tpl['title']; array_unshift($title, $value); $data['title'] = call_user_func_array('sprintf', $title); } else { $data['title'] = $tpl['title']; } if (!empty($content) && is_array($content)) { $value = $tpl['content']; array_unshift($content, $value); $data['content'] = call_user_func_array('sprintf', $content); } else { $data['content'] = $tpl['content']; } $res = Db::name('xy_message')->insert($data); return $res; } function _sprintf($value, $vars = []) { if (!empty($vars) && is_array($vars)) { array_unshift($vars, $value); return call_user_func_array('sprintf', $vars); } else { return $value; } return ''; } function curl_down($url, $file = '', $timeout = 60) { $file = empty($file) ? pathinfo($url, PATHINFO_BASENAME) : $file; $dir = pathinfo($file, PATHINFO_DIRNAME); !is_dir($dir) && @mkdir($dir, 0755, true); $url = str_replace(' ', "%20", $url); $result = array('fileName' => '', 'way' => '', 'size' => 0, 'spendTime' => 0); $startTime = explode(' ', microtime()); $startTime = (float) $startTime[0] + (float) $startTime[1]; if (function_exists('curl_init')) { $ch = curl_init(); curl_setopt($ch, CURLOPT_URL, $url); curl_setopt($ch, CURLOPT_TIMEOUT, $timeout); curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); $temp = curl_exec($ch); if (@file_put_contents($file, $temp) && !curl_error($ch)) { $result['fileName'] = $file; $result['way'] = 'curl'; $result['size'] = sprintf('%.3f', strlen($temp) / 1024); } } else { $opts = array( 'http' => array( 'method' => 'GET', 'header' => '', 'timeout' => $timeout, ), ); $context = stream_context_create($opts); if (@copy($url, $file, $context)) { $result['fileName'] = $file; $result['way'] = 'copy'; $result['size'] = sprintf('%.3f', strlen($context) / 1024); } } $endTime = explode(' ', microtime()); $endTime = (float) $endTime[0] + (float) $endTime[1]; $result['spendTime'] = round($endTime - $startTime) * 1000; return $result; } function curl_get($url) { $options = array( CURLOPT_RETURNTRANSFER => true, CURLOPT_HEADER => false, CURLOPT_POST => false, ); $ch = curl_init($url); curl_setopt_array($ch, $options); $result = curl_exec($ch); curl_close($ch); return $result; } function curlRequest($url, $data = array(), $referer = '', $cookie = '', $header = array(), $timeout = 120) { $urlinfo = parse_url($url); $curl = curl_init(); curl_setopt($curl, CURLOPT_URL, $url); if (isset($urlinfo['scheme']) && $urlinfo['scheme'] == 'https') { curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); } if (!empty($referer)) { curl_setopt($curl, CURLOPT_REFERER, $referer); } if (!empty($data)) { curl_setopt($curl, CURLOPT_HTTPHEADER, $header); } if (!empty($data)) { curl_setopt($curl, CURLOPT_POST, 1); curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data)); } if (!empty($cookie)) { curl_setopt($curl, CURLOPT_COOKIE, $cookie); } curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); curl_setopt($curl, CURLOPT_TIMEOUT, $timeout); $output = curl_exec($curl); curl_close($curl); return $output; } function spliceString($type = [0, 1], $customize = '') { $char = [ '0123456789', 'abcdefghijklmnopqrstuvwxyz', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', '~!@#$%^&*', ]; $characters = ''; foreach ($type as $key => $value) { $characters .= $char[$value]; } $characters .= $customize; return $characters; } function hiddenCharacter($str, $slen = 1, $elen = -1) { $str = $str; $abc = substr($str, $elen); $resstr = substr_replace($str, '****', $slen) . $abc; return $resstr; } function send_sms($value = '') { } function sendMail_bt($configs, $to, $name, $subject, $body, $attachment = null) { $url = $configs['api']; $data = [ 'mail_from' => $configs['from'], 'password' => $configs['password'], 'mail_to' => $to, 'subject' => $subject, 'content' => $body, 'subtype' => $configs['subtype'] ?? '', ]; return curlRequest($url, $data); } function sendMail($configs, $to, $name, $subject, $body, $attachment = null) { $config = [ 'charset' => 'utf-8', 'debug' => 1, 'secure' => $configs['secure'], 'host' => $configs['host'], 'port' => $configs['port'], 'auth' => true, 'username' => $configs['username'], 'password' => $configs['password'], 'from' => $configs['from'], 'fromname' => sys_config('site_name'), ]; $mail = new PHPMailer(); $mail->CharSet = $config['charset']; $mail->isSMTP(); $mail->SMTPDebug = $config['debug']; $mail->SMTPSecure = $config['secure']; $mail->Host = $config['host']; $mail->Port = $config['port']; if ($config['auth']) { $mail->SMTPAuth = true; $mail->Username = $config['username']; $mail->Password = $config['password']; } $mail->setFrom($config['from'], $config['fromname']); $mail->Subject = $subject; $mail->msgHTML($body); $mail->addAddress($to, empty($name) ? $to : $name); if (is_array($attachment)) { foreach ($attachment as $file) { is_file($file) && $mail->addAttachment($file); } } $result = $mail->send() ? true : $mail->ErrorInfo; return $result; } function send_mail($configs, $email, $name, $subject, $body, $attachment = null) { $config = [ 'charset' => 'utf-8', 'debug' => 0, 'secure' => $configs['email_secure'], 'host' => $configs['email_host'], 'port' => $configs['email_port'], 'auth' => true, 'username' => $configs['email_id'], 'password' => $configs['email_pass'], 'from' => $configs['email_addr'], 'fromname' => $configs['site_name'], ]; $mail = new PHPMailer(); $mail->CharSet = $config['charset']; $mail->isSMTP(); $mail->SMTPDebug = $config['debug']; $mail->SMTPSecure = $config['secure']; $mail->Host = $config['host']; $mail->Port = $config['port']; if ($config['auth']) { $mail->SMTPAuth = true; $mail->Username = $config['username']; $mail->Password = $config['password']; } $mail->setFrom($config['from'], $config['fromname']); $mail->Subject = $subject; $mail->msgHTML($body); $mail->addAddress($email, empty($name) ? $email : $name); if (is_array($attachment)) { foreach ($attachment as $file) { is_file($file) && $mail->addAttachment($file); } } $result = $mail->send() ? true : $mail->ErrorInfo; return $result; } function is_image_base64($base64) { if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64, $result)) { return true; } else { return false; } } function check_pic($dir, $type_img) { $new_files = $dir . date("YmdHis") . '-' . rand(0, 9999999) . "{$type_img}"; if (!file_exists($new_files)) { return $new_files; } else { return check_pic($dir, $type_img); } } function get_arr_column($arr, $key_name) { $arr2 = array(); foreach ($arr as $key => $val) { $arr2[] = $val[$key_name]; } return $arr2; } function tow_float($number) { return (floor($number * 100) / 100); } function getSn($head = '') { @date_default_timezone_set("PRC"); $order_id_main = date('YmdHis') . mt_rand(10000, 99999); $osn = $head . substr($order_id_main, 2); return $osn; } function setconfig($name, $value) { if (is_array($name) and is_array($value)) { for ($i = 0; $i < count($name); $i++) { $names[$i] = '/\'' . $name[$i] . '\'(.*?),/'; $values[$i] = "'" . $name[$i] . "'" . "=>" . "'" . $value[$i] . "',"; } $fileurl = APP_PATH . "../config/app.php"; $string = file_get_contents($fileurl); $string = preg_replace($names, $values, $string); file_put_contents($fileurl, $string); return true; } else { return false; } } function get_username() { $chars1 = "ABCDEFGHIJKLMNOPQRSTUVWXYZ"; $chars2 = "abcdefghijklmnopqrstuvwxyz0123456789"; $username = ""; for ($i = 0; $i < mt_rand(2, 3); $i++) { $username .= $chars1[mt_rand(0, 25)]; } $username .= '_'; for ($i = 0; $i < mt_rand(4, 6); $i++) { $username .= $chars2[mt_rand(0, 35)]; } return $username; } function check_time($a, $b) { $nowtime = time(); $start = strtotime($a . ':00:00'); $end = strtotime($b . ':00:00'); if ($nowtime >= $end || $nowtime <= $start) { return true; } else { return false; } } 

 use think\Container; use think\Db; use think\exception\HttpException; use think\exception\HttpResponseException; use think\facade\Cache; use think\facade\Config; use think\facade\Cookie; use think\facade\Debug; use think\facade\Env; use think\facade\Hook; use think\facade\Lang; use think\facade\Log; use think\facade\Request; use think\facade\Route; use think\facade\Session; use think\facade\Url; use think\Response; use think\route\RuleItem; if (!function_exists('abort')) { function abort($code, $message = null, $header = []) { if ($code instanceof Response) { throw new HttpResponseException($code); } else { throw new HttpException($code, $message, null, $header); } } } if (!function_exists('action')) { function action($url, $vars = [], $layer = 'controller', $appendSuffix = false) { return app()->action($url, $vars, $layer, $appendSuffix); } } if (!function_exists('app')) { function app($name = 'think\App', $args = [], $newInstance = false) { return Container::get($name, $args, $newInstance); } } if (!function_exists('behavior')) { function behavior($behavior, $args = null) { return Hook::exec($behavior, $args); } } if (!function_exists('bind')) { function bind($abstract, $concrete = null) { return Container::getInstance()->bindTo($abstract, $concrete); } } if (!function_exists('cache')) { function cache($name, $value = '', $options = null, $tag = null) { if (is_array($options)) { Cache::connect($options); } elseif (is_array($name)) { return Cache::connect($name); } if ('' === $value) { return 0 === strpos($name, '?') ? Cache::has(substr($name, 1)) : Cache::get($name); } elseif (is_null($value)) { return Cache::rm($name); } if (is_array($options)) { $expire = isset($options['expire']) ? $options['expire'] : null; } else { $expire = is_numeric($options) ? $options : null; } if (is_null($tag)) { return Cache::set($name, $value, $expire); } else { return Cache::tag($tag)->set($name, $value, $expire); } } } if (!function_exists('call')) { function call($callable, $args = []) { return Container::getInstance()->invoke($callable, $args); } } if (!function_exists('class_basename')) { function class_basename($class) { $class = is_object($class) ? get_class($class) : $class; return basename(str_replace('\\', '/', $class)); } } if (!function_exists('class_uses_recursive')) { function class_uses_recursive($class) { if (is_object($class)) { $class = get_class($class); } $results = []; $classes = array_merge([$class => $class], class_parents($class)); foreach ($classes as $class) { $results += trait_uses_recursive($class); } return array_unique($results); } } if (!function_exists('config')) { function config($name = '', $value = null) { if (is_null($value) && is_string($name)) { if ('.' == substr($name, -1)) { return Config::pull(substr($name, 0, -1)); } return 0 === strpos($name, '?') ? Config::has(substr($name, 1)) : Config::get($name); } else { return Config::set($name, $value); } } } if (!function_exists('container')) { function container() { return Container::getInstance(); } } if (!function_exists('controller')) { function controller($name, $layer = 'controller', $appendSuffix = false) { return app()->controller($name, $layer, $appendSuffix); } } if (!function_exists('cookie')) { function cookie($name, $value = '', $option = null) { if (is_array($name)) { Cookie::init($name); } elseif (is_null($name)) { Cookie::clear($value); } elseif ('' === $value) { return 0 === strpos($name, '?') ? Cookie::has(substr($name, 1), $option) : Cookie::get($name); } elseif (is_null($value)) { return Cookie::delete($name); } else { return Cookie::set($name, $value, $option); } } } if (!function_exists('db')) { function db($name = '', $config = [], $force = true) { return Db::connect($config, $force)->name($name); } } if (!function_exists('debug')) { function debug($start, $end = '', $dec = 6) { if ('' == $end) { Debug::remark($start); } else { return 'm' == $dec ? Debug::getRangeMem($start, $end) : Debug::getRangeTime($start, $end, $dec); } } } if (!function_exists('download')) { function download($filename, $name = '', $content = false, $expire = 360, $openinBrowser = false) { return Response::create($filename, 'download')->name($name)->isContent($content)->expire($expire)->openinBrowser($openinBrowser); } } if (!function_exists('dump')) { function dump($var, $echo = true, $label = null) { return Debug::dump($var, $echo, $label); } } if (!function_exists('env')) { function env($name = null, $default = null) { return Env::get($name, $default); } } if (!function_exists('exception')) { function exception($msg, $code = 0, $exception = '') { $e = $exception ?: '\think\Exception'; throw new $e($msg, $code); } } if (!function_exists('halt')) { function halt($var) { dump($var); throw new HttpResponseException(new Response); } } if (!function_exists('input')) { function input($key = '', $default = null, $filter = '') { if (0 === strpos($key, '?')) { $key = substr($key, 1); $has = true; } if ($pos = strpos($key, '.')) { $method = substr($key, 0, $pos); if (in_array($method, ['get', 'post', 'put', 'patch', 'delete', 'route', 'param', 'request', 'session', 'cookie', 'server', 'env', 'path', 'file'])) { $key = substr($key, $pos + 1); } else { $method = 'param'; } } else { $method = 'param'; } if (isset($has)) { return request()->has($key, $method, $default); } else { return request()->$method($key, $default, $filter); } } } if (!function_exists('json')) { function json($data = [], $code = 200, $header = [], $options = []) { return Response::create($data, 'json', $code, $header, $options); } } if (!function_exists('jsonp')) { function jsonp($data = [], $code = 200, $header = [], $options = []) { return Response::create($data, 'jsonp', $code, $header, $options); } } if (!function_exists('lang')) { function lang($name, $vars = [], $lang = '') { return Lang::get($name, $vars, $lang); } } if (!function_exists('model')) { function model($name = '', $layer = 'model', $appendSuffix = false) { return app()->model($name, $layer, $appendSuffix); } } if (!function_exists('parse_name')) { function parse_name($name, $type = 0, $ucfirst = true) { if ($type) { $name = preg_replace_callback('/_([a-zA-Z])/', function ($match) { return strtoupper($match[1]); }, $name); return $ucfirst ? ucfirst($name) : lcfirst($name); } else { return strtolower(trim(preg_replace("/[A-Z]/", "_\\0", $name), "_")); } } } if (!function_exists('redirect')) { function redirect($url = [], $params = [], $code = 302) { if (is_integer($params)) { $code = $params; $params = []; } return Response::create($url, 'redirect', $code)->params($params); } } if (!function_exists('request')) { function request() { return app('request'); } } if (!function_exists('response')) { function response($data = '', $code = 200, $header = [], $type = 'html') { return Response::create($data, $type, $code, $header); } } if (!function_exists('route')) { function route($rule, $route, $option = [], $pattern = []) { return Route::rule($rule, $route, '*', $option, $pattern); } } if (!function_exists('session')) { function session($name, $value = '', $prefix = null) { if (is_array($name)) { Session::init($name); } elseif (is_null($name)) { Session::clear($value); } elseif ('' === $value) { return 0 === strpos($name, '?') ? Session::has(substr($name, 1), $prefix) : Session::get($name, $prefix); } elseif (is_null($value)) { return Session::delete($name, $prefix); } else { return Session::set($name, $value, $prefix); } } } if (!function_exists('token')) { function token($name = '__token__', $type = 'md5') { $token = Request::token($name, $type); return '<input type="hidden" name="' . $name . '" value="' . $token . '" />'; } } if (!function_exists('trace')) { function trace($log = '[think]', $level = 'log') { if ('[think]' === $log) { return Log::getLog(); } else { Log::record($log, $level); } } } if (!function_exists('trait_uses_recursive')) { function trait_uses_recursive($trait) { $traits = class_uses($trait); foreach ($traits as $trait) { $traits += trait_uses_recursive($trait); } return $traits; } } if (!function_exists('url')) { function url($url = '', $vars = '', $suffix = true, $domain = false) { return Url::build($url, $vars, $suffix, $domain); } } if (!function_exists('validate')) { function validate($name = '', $layer = 'validate', $appendSuffix = false) { return app()->validate($name, $layer, $appendSuffix); } } if (!function_exists('view')) { function view($template = '', $vars = [], $code = 200, $filter = null) { return Response::create($template, 'view', $code)->assign($vars)->filter($filter); } } if (!function_exists('widget')) { function widget($name, $data = []) { $result = app()->action($name, $data, 'widget'); if (is_object($result)) { $result = $result->getContent(); } return $result; } } if (!function_exists('xml')) { function xml($data = [], $code = 200, $header = [], $options = []) { return Response::create($data, 'xml', $code, $header, $options); } } if (!function_exists('yaconf')) { function yaconf($name, $default = null) { return Config::yaconf($name, $default); } } 

\think\facade\Config::set(array (
  'app' => 
  array (
    'app_name' => '',
    'app_host' => '',
    'app_debug' => true,
    'app_trace' => false,
    'app_status' => '',
    'is_https' => false,
    'auto_bind_module' => false,
    'root_namespace' => 
    array (
    ),
    'default_return_type' => 'html',
    'default_ajax_return' => 'json',
    'default_jsonp_handler' => 'jsonpReturn',
    'var_jsonp_handler' => 'callback',
    'default_timezone' => 'Asia/Tokyo',
    'lang_switch_on' => false,
    'default_validate' => '',
    'default_lang' => 'ja-jp',
    'controller_auto_search' => false,
    'use_action_prefix' => false,
    'action_suffix' => '',
    'empty_controller' => 'Error',
    'empty_module' => '',
    'default_module' => 'index',
    'app_multi_module' => true,
    'deny_module_list' => 
    array (
      0 => 'common',
    ),
    'default_controller' => 'Index',
    'default_action' => 'index',
    'url_convert' => true,
    'url_controller_layer' => 'controller',
    'class_suffix' => false,
    'controller_suffix' => false,
    'default_filter' => 'trim',
    'var_pathinfo' => 's',
    'pathinfo_fetch' => 
    array (
      0 => 'ORIG_PATH_INFO',
      1 => 'REDIRECT_PATH_INFO',
      2 => 'REDIRECT_URL',
    ),
    'https_agent_name' => '',
    'http_agent_ip' => 'HTTP_X_REAL_IP',
    'url_html_suffix' => 'html',
    'url_domain_root' => '',
    'var_method' => '_method',
    'var_ajax' => '_ajax',
    'var_pjax' => '_pjax',
    'request_cache' => false,
    'request_cache_expire' => NULL,
    'request_cache_except' => 
    array (
    ),
    'pathinfo_depr' => '/',
    'url_common_param' => false,
    'url_param_type' => 1,
    'url_lazy_route' => false,
    'url_route_must' => false,
    'route_rule_merge' => false,
    'route_complete_match' => false,
    'route_annotation' => false,
    'default_route_pattern' => '\\w+',
    'route_check_cache' => false,
    'route_check_cache_key' => '',
    'route_cache_option' => 
    array (
    ),
    'dispatch_success_tmpl' => '/www/wwwroot/45.118.248.72/public/../application//tpl/dispatch_jump.tpl',
    'dispatch_error_tmpl' => '/www/wwwroot/45.118.248.72/public/../application//tpl/dispatch_jump.tpl',
    'exception_tmpl' => '/www/wwwroot/45.118.248.72/public/../application//tpl/think_exception.tpl',
    'error_message' => 'page error',
    'show_error_msg' => false,
    'exception_handle' => '',
    'thinkadmin_ver' => 'v5',
    'pwd_str' => '!qws6F!xffD2vx80?95jt',
    'pwd_error_num' => 10,
    'allow_login_min' => 5,
    'site_admin_domain' => 'www.i00ioe.cn',
    'zhangjun_sms' => 
    array (
      'userid' => '????',
      'account' => '?????',
      'pwd' => '????',
      'content' => '【????】您的验证码为：',
      'min' => 5,
    ),
    'smsbao' => 
    array (
      'user' => 'qq5550235',
      'pass' => 'qq5550692',
      'sign' => '梦想国际',
    ),
    'smsjian' => 
    array (
      'appid' => 'qq5550023',
      'appkey' => 'd41d8cd98f00b204e980',
    ),
    'version' => '20100106',
    'bipay' => 
    array (
      'appKey' => '2ed2c4347fa70847',
      'appSecret' => 'b471e157a6bcafea74360dbc0b7ba523',
    ),
    'paysapi' => 
    array (
      'uid' => '362c5d32770407de2f009c54',
      'token' => 'bedfd347390e127bd675c18dc92dfa16',
      'istype' => 1,
    ),
    'app_url' => 'http://new.qilin.ee/public/client/client/moban?id=223',
    'verify' => true,
    'mix_time' => '5',
    'max_time' => '10',
    'min_recharge' => '100',
    'max_recharge' => '5000',
    'deal_min_balance' => '100',
    'deal_min_num' => '10',
    'deal_max_num' => '35',
    'deal_count' => '60',
    'deal_reward_count' => '0',
    'deal_timeout' => '600',
    'deal_feedze' => '2',
    'deal_error' => '3',
    'vip_1_commission' => '0',
    'min_deposit' => '100',
    '1_reward' => '0',
    '2_reward' => '0',
    '3_reward' => '0',
    '1_d_reward' => '0.5',
    '2_d_reward' => '0.3',
    '3_d_reward' => '0.2',
    '4_d_reward' => '0',
    '5_d_reward' => '0',
    'master_cardnum' => '6212252010001395895',
    'master_name' => '钟意成',
    'master_bank' => '中国工商银行',
    'master_bk_address' => '惠阳支行',
    'deal_zhuji_time' => '2',
    'deal_shop_time' => '2',
    'time_start' => '',
    'time_end' => '',
  ),
  'template' => 
  array (
    'auto_rule' => 1,
    'type' => 'Think',
    'view_base' => '',
    'view_path' => '',
    'view_suffix' => 'html',
    'view_depr' => '/',
    'tpl_begin' => '{',
    'tpl_end' => '}',
    'taglib_begin' => '{',
    'taglib_end' => '}',
    'strip_space' => true,
    'tpl_cache' => false,
    'tpl_replace_string' => 
    array (
      '__APP__' => '',
      '__ROOT__' => '',
      '__PUBLIC__' => 'http://www.i00ioe.cn',
      '__NPM__' => 'https://cdn.jsdelivr.net/npm',
      '__GH__' => 'https://cdn.jsdelivr.net/gh',
      '__RESPRIVATE__' => '/res/private',
      '__VER__' => '?v=1595229447',
    ),
  ),
  'log' => 
  array (
    'type' => 'File',
    'level' => 
    array (
      0 => 'error',
      1 => 'auto_freeze',
      2 => 'settlement',
      3 => 'cancel_order',
      4 => 'unfreeze',
    ),
    'record_trace' => true,
    'json' => false,
    'single' => 'single',
    'max_files' => 50,
    'path' => '/www/wwwroot/45.118.248.72/public/../runtime/log/20200720/',
    'file_size' => 10485760,
    'apart_level' => 
    array (
      0 => 'error',
      1 => 'auto_freeze',
      2 => 'settlement',
      3 => 'cancel_order',
      4 => 'unfreeze',
    ),
  ),
  'trace' => 
  array (
    'type' => 'Html',
    'file' => '/www/wwwroot/45.118.248.72/thinkphp/tpl/page_trace.tpl',
  ),
  'cache' => 
  array (
    'type' => 'Memcached',
    'prefix' => '',
    'expire' => 0,
    'host' => '127.0.0.1',
    'port' => 11211,
    'timeout' => 0,
    'username' => '',
    'password' => '',
    'option' => 
    array (
    ),
    'serialize' => true,
  ),
  'session' => 
  array (
    'id' => '',
    'var_session_id' => '',
    'prefix' => 'www.i00ioe.cn',
    'type' => 'Redis',
    'auto_start' => true,
    'httponly' => true,
    'secure' => false,
    'expire' => 86400,
    'host' => '127.0.0.1',
    'port' => 6379,
    'password' => '123456',
  ),
  'cookie' => 
  array (
    'prefix' => '',
    'expire' => 1800,
    'path' => '/',
    'domain' => '',
    'secure' => false,
    'httponly' => true,
    'setcookie' => true,
  ),
  'database' => 
  array (
    'type' => 'mysql',
    'dsn' => '',
    'hostname' => 'localhost',
    'database' => 'orders_jikyowu_c',
    'username' => 'orders_jikyowu_c',
    'password' => 'STYHyPd6jkA5zPYe',
    'hostport' => 3306,
    'params' => 
    array (
      12 => true,
      8 => 2,
    ),
    'charset' => 'utf8',
    'prefix' => '',
    'debug' => false,
    'deploy' => 0,
    'rw_separate' => false,
    'master_num' => 1,
    'slave_no' => '',
    'fields_strict' => true,
    'resultset_type' => 'array',
    'auto_timestamp' => false,
    'datetime_format' => 'Y-m-d H:i:s',
    'sql_explain' => true,
    'query' => '\\think\\db\\Query',
    'read_master' => false,
    'builder' => '',
    'break_reconnect' => true,
    'break_match_str' => 
    array (
    ),
  ),
  'paginate' => 
  array (
    'type' => 'bootstrap',
    'var_page' => 'page',
    'list_rows' => 15,
  ),
  'console' => 
  array (
    'name' => 'Think Console',
    'version' => '0.1',
    'user' => NULL,
    'auto_path' => '',
  ),
  'middleware' => 
  array (
    'default_namespace' => 'app\\http\\middleware\\',
  ),
  'lang' => 
  array (
    'default_lang' => 'ja-jp',
    'lang_switch_on' => false,
    'allow_lang_list' => 
    array (
      0 => 'zh-cn',
      1 => 'en-us',
      2 => 'ja-jp',
    ),
    'detect_var' => 'lang',
    'use_cookie' => true,
    'cookie_var' => 'think_lang',
    'extend_list' => 
    array (
    ),
    'accept_language' => 
    array (
      'zh-hans-cn' => 'zh-cn',
    ),
    'allow_group' => false,
  ),
  'queue' => 
  array (
    'connector' => 'Sync',
  ),
  'swoole' => 
  array (
    'host' => '0.0.0.0',
    'port' => 9501,
    'mode' => '',
    'sock_type' => '',
    'server_type' => 'http',
    'app_path' => '',
    'file_monitor' => false,
    'file_monitor_interval' => 2,
    'file_monitor_path' => 
    array (
    ),
    'pid_file' => '/www/wwwroot/45.118.248.72/runtime/swoole.pid',
    'log_file' => '/www/wwwroot/45.118.248.72/runtime/swoole.log',
    'document_root' => '/www/wwwroot/45.118.248.72/public',
    'enable_static_handler' => true,
    'timer' => true,
    'interval' => 500,
    'task_worker_num' => 1,
  ),
  'swoole_server' => 
  array (
    'host' => '0.0.0.0',
    'port' => 9508,
    'type' => 'socket',
    'mode' => '',
    'sock_type' => '',
    'swoole_class' => '',
    'daemonize' => false,
    'pid_file' => '/www/wwwroot/45.118.248.72/runtime/swoole_server.pid',
    'log_file' => '/www/wwwroot/45.118.248.72/runtime/swoole_server.log',
    'onOpen' => 
    Closure::__set_state(array(
    )),
    'onMessage' => 
    Closure::__set_state(array(
    )),
    'onRequest' => 
    Closure::__set_state(array(
    )),
    'onClose' => 
    Closure::__set_state(array(
    )),
  ),
  'system' => 
  array (
    'lang_list' => 
    array (
      'zh-cn' => '中文',
      'ja-jp' => '日本語',
    ),
    'mobile_area' => 'JP',
  ),
  'timer' => 
  array (
    '*/5 * * * * *' => '\\app\\lib\\Timer',
  ),
  'wechat' => 
  array (
    'service_url' => 'https://demo.thinkadmin.top',
    'miniapp' => 
    array (
      'appid' => 'wx8c108930fe12b7ef',
      'appsecret' => '13d829992a2b6a0a44195a4a580da56d',
      'mch_id' => '1332187001',
      'mch_key' => 'A82DC5BD1F3359081049C568D8502BC5',
      'ssl_p12' => '/www/wwwroot/45.118.248.72/config/cert/1332187001_20181030_cert.p12',
      'cache_path' => '/www/wwwroot/45.118.248.72/runtime/wechat/',
    ),
  ),
));
