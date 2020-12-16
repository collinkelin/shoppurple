<?php

namespace app\command;

use app\command\Common;
use app\common\model\MessageAisle as MessageAisleModel;
use app\common\model\MessageQueue as MessageQueueModel;
use Swoole\Timer;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;
use util\Time;

// 启动
// php think monitors
class Monitors extends Common
{
    protected function configure()
    {
        $this->setName('monitors')
            ->addArgument('action', Argument::OPTIONAL, "action  start|stop|restart")
            ->addArgument('type', Argument::OPTIONAL, "d -d")
            ->setDescription('the monitors command');
    }

    protected function execute(Input $input, Output $output)
    {
        // 指令输出
        $output->writeln('monitors');
        global $argv;
        $action = trim($input->getArgument('action'));
        $type   = trim($input->getArgument('type')) ? '-d' : '';

        $argv[0] = 'monitors';
        $argv[1] = $action;
        $argv[2] = $type ? '-d' : '';

        $this->start($action);
    }

    private function start($action)
    {
        Timer::tick(5000, function () {
            $this->SendMail();
        });
    }

    private function SendMail()
    {
        $mapc = [
            ['type', '=', 0],
            ['status', '=', 1],
            ['default', '=', 1],
        ];
        $time   = time();
        $config = MessageAisleModel::where($mapc)->find();
        if (!empty($config)) {
            $limit = 50;
            $start = 0;
            do {
                // 获取待发送邮件
                $map = [
                    ['sms', '=', 0],
                    ['status', '=', 0],
                ];
                $mails = MessageQueueModel::where($map)
                    ->limit($start, $limit)
                    ->select();
                // 发送邮件
                foreach ($mails as $key => $mail) {
                    if ($mail['status'] != 1) {
                        $action = $config['action'];
                        // if (!function_exists($action)) {
                        echo json_encode($config), PHP_EOL;
                        $result = $action($config, $mail['to'], $mail['to'], $mail['title'], $mail['content']);
                        if ($result == true) {
                            echo date('Y-m-d H:i:s') . "\t向:{$mail['to']} 发送邮件: {$mail['title']}", PHP_EOL;
                            $data = [
                                'id'     => $mail['id'],
                                'status' => 1,
                            ];
                            MessageQueueModel::update($data, ['id' => $data['id']]);
                        } else {
                            var_dump($result);
                            echo PHP_EOL;
                        }
                        // }
                    }
                }
                $start += $limit;
            } while (!empty($mails[$limit]));
        }
        $mapq = [
            ['sms', '=', 0],
            ['expire_time', '<', $time],
        ];
        MessageQueueModel::where($mapq)->delete();

        Timer::after(5000, function () {
            $this->SendMail();
        });
    }
}
