<?php

namespace app\command;

use app\common\model\GoodsCate as GoodsCateModel;
use app\common\model\GoodsList as GoodsListModel;
use think\console\Command;
use think\console\Input;
use think\console\Output;

// 启动
// php think syncgoods
class SyncGoods extends Command
{

    protected function configure()
    {
        // 指令配置
        $this->setName('app\command\syncgoods');
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
        $page      = 1;
        $list_rows = 30;
        $domain    = 'https://www.rebyua-zu.com';
        $url       = '%s/index/api/syncgoods?page=%s&list_rows=%s';
        $downurl   = sprintf($url, $domain, $page, $list_rows);
        $str       = curl_get($downurl);
        $arr       = [];
        $img_root  = ROOT_PATH . 'public';
        try {
            $arr = json_decode($str, true);
            if (!empty($arr)) {
                foreach ($arr['cates'] as $key => $value) {
                    $res = GoodsCateModel::where('id', $value['id'])->find();
                    if (empty($res)) {
                        (new GoodsCateModel)->allowField(true)->save($value);
                    }
                    if (!file_exists($img_root . $value['cate_pic'])) {
                        try {
                            $result = curl_down($domain . $value['cate_pic'], $img_root . $value['cate_pic']);
                            echo json_encode($result), PHP_EOL;
                        } catch (Exception $e) {
                            echo 'error3', PHP_EOL;
                        }
                    }
                }
                do {
                    $downurl = sprintf($url, $domain, $page, $list_rows);
                    $str     = curl_get($downurl);
                    $arr     = [];
                    try {
                        $arr = json_decode($str, true);
                        echo $arr['last_page'], ' --- ', $arr['current_page'], PHP_EOL;
                        foreach ($arr['data'] as $key => $value) {
                            $res = GoodsListModel::where('id', $value['id'])->find();
                            if (empty($res)) {
                                (new GoodsListModel)->allowField(true)->save($value);
                            }
                            if (!file_exists($img_root . $value['goods_pic'])) {
                                try {
                                    $result = curl_down($domain . $value['goods_pic'], $img_root . $value['goods_pic']);
                                    echo json_encode($result), PHP_EOL;
                                } catch (Exception $e) {
                                    echo 'error3', PHP_EOL;
                                }
                            }
                        }
                    } catch (Exception $e) {
                        echo 'error2', PHP_EOL;
                    }
                    $page = $arr['current_page'] + 1;
                } while ($arr['last_page'] >= $page);
            }
        } catch (Exception $e) {
            echo 'error1', PHP_EOL;
        }
    }
}
