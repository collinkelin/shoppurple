<?php

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;

// 启动
// php think res
class Res extends Command
{

    protected function configure()
    {
        // 指令配置
        $this->setName('app\command\res');
        // 设置参数
    }

    protected function execute(Input $input, Output $output)
    {
        // 指令输出
        $output->writeln(APP_PATH);
        $this->start();
    }

    private function start()
    {
        $res = db('xy_res')->select();
        foreach ($res as $key => $value) {
            switch ($value['type']) {
                case 'npm':
                    $url = _sprintf($value['files'], $value);
                    break;
                case 'gh':
                    $url = _sprintf($value['files'], $value);
                    break;
            }
            echo $url, PHP_EOL;
            $str = curl_get($url);
            try {
                $arr = json_decode($str, true);
                $this->deep_foreach($value, $arr);
            } catch (Exception $e) {

            }
        }
    }

    private function deep_foreach($res, $arr, $k = '', $pre_indent = '', $path = '')
    {
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
                $this->deep_foreach($res, $val, $key, $cur_indent, $p);
            } else {
                if ($key == 'name') {
                    if ($dir && !empty($val)) {
                        $p .= '/' . $val;
                    } else {
                        $res['file'] = $p . '/' . $val;
                        $downurl     = _sprintf($res['downurl'], $res);
                        $local       = WEB_ROOT . _sprintf($res['local'], $res);
                        // echo $downurl, PHP_EOL;
                        // echo $local, PHP_EOL;
                        $result = curl_down($downurl, $local);
                        echo json_encode($result), PHP_EOL;
                    }
                }
            }
        }
    }
}
