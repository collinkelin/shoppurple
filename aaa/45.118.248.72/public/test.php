<?php

// url -> $jsdrv name @ version
$api  = 'https://data.jsdelivr.com/v1/package/%s/%s';
$api1 = 'https://data.jsdelivr.com/v1/package/%s/%s@%s';

$type = $_REQUEST['type'] ?? 'npm'; // npm.gh.wp
$name = $_REQUEST['name'];
$ver  = $_REQUEST['ver'];

if (empty($ver)) {
    $url = sprintf($api, $type, $name);
} else {
    $url = sprintf($api1, $type, $name, $ver);
}

$str = curlRequest($url);
$arr = json_decode($str, true);

deep_foreach($arr);

function deep_foreach($arr, $k = '', $pre_indent = '', $path = '')
{
    $p = $path;
    if (!is_array($arr)) {
        return false;
    }
    $cur_indent = $pre_indent . "    ";
    $type       = false;
    foreach ($arr as $key => $val) {
        if ($key == 'type' && $val == 'directory') {
            $type = true;
        }
        if (is_array($val)) {
            deep_foreach($val, $key, $cur_indent, $p);
        } else {
            if ($key == 'name') {
                if ($type) {
                    $p .= '/' . $val;
                } else {
                    echo $cur_indent . "[$key] = > " . $p . '/' . $val . '<br/>';
                }
            }
        }
    }
}

/**
 * 遍历某个目录下的所有文件
 * @param string $dir
 */
function scanAll($dir)
{
    $list   = array();
    $list[] = $dir;

    while (count($list) > 0) {
        //弹出数组最后一个元素
        $file = array_pop($list);

        //处理当前文件
        echo $file . "\r\n";

        //如果是目录
        if (is_dir($file)) {
            $children = scandir($file);
            foreach ($children as $child) {
                if ($child !== '.' && $child !== '..') {
                    $list[] = $file . '/' . $child;
                }
            }
        }
    }
}

/**
 * 遍历某个目录下的所有文件(递归实现)
 * @param string $dir
 */
function scanAll2($dir)
{
    echo $dir . "\r\n";

    if (is_dir($dir)) {
        $children = scandir($dir);
        foreach ($children as $child) {
            if ($child !== '.' && $child !== '..') {
                scanAll2($dir . '/' . $child);
            }
        }
    }
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
 * 判断数组为几维数组 可优化
 * @param array $array
 * @param int $count
 * @return int
 */
function foreachArray($array = [], $count = 1)
{
    if (!is_array($array)) {
        return $count;
    }
    foreach ($array as $value) {
        $count++;
        if (!is_array($value)) {
            return $count;
        }
        return foreachArray($value, $count);
    }
}
