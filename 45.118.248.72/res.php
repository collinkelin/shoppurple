<?php
/**
 *
 * @authors Seamless (42447558@qq.com)
 * @date    2017-07-23 23:29:22
 * @version $Id$
 */

// url -> $jsdrv name @ version
$api  = 'https://data.jsdelivr.com/v1/package/%s/%s';
$api1 = 'https://data.jsdelivr.com/v1/package/%s/%s@%s';
$down = 'https://cdn.jsdelivr.net/%s/%s@%s%s';

$local = '/public/res/%s/%s@%s%s';
$type  = '';
$name  = '';
$ver   = '';

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

function deep_foreach($arr, $k = '', $pre_indent = '', $path = '')
{
    global $type, $name, $ver, $down, $local;
    $p = $path;
    if (!is_array($arr)) {
        return false;
    }
    $cur_indent = $pre_indent . "    ";
    $dir        = false;
    foreach ($arr as $key => $val) {
        if ($key == 'type' && $val == 'directory') {
            $dir = true;
        }
        if (is_array($val)) {
            deep_foreach($val, $key, $cur_indent, $p);
        } else {
            if ($key == 'name') {
                if ($dir && !empty($val)) {
                    $p .= '/' . $val;
                } else {
                    $file   = $p . '/' . $val;
                    $result = curl_down(sprintf($down, $type, $name, $ver, $file), '.' . sprintf($local, $type, $name, $ver, $file));
                    echo json_encode($result), PHP_EOL;
                }
            }
        }
    }
}

if (isset($argv[2])) {
    $args = $argv;
    $type = $args[1];
    $name = $args[2];
    $ver  = $args[3];

    if (empty($ver)) {
        $url = sprintf($api, $type, $name);
    } else {
        $url = sprintf($api1, $type, $name, $ver);
    }

    $str = curl_get($url);
    $arr = json_decode($str, true);

    deep_foreach($arr);
}
