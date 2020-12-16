<?php

namespace app\index\controller;

use app\common\model\TaskRecord as TaskRecordModel;
use app\common\model\Tasks as TasksModel;
use think\Controller;
use util\Time;

class Tasks extends Base
{
    /**
     * 任务列表
     */
    public function index()
    {
        if (request()->isPost()) {
            $tasks = TasksModel::where('status', 1)->select();
            return json(['code' => 0, 'info' => lang('Successful operation'), 'data' => $tasks]);
        }
        return $this->fetch();
    }

    /**
     * [join 任务接取]
     * @return [type] [description]
     */
    public function join()
    {
        $id  = input('id');
        $uid = session('user_id');
        if (empty($id)) {
            $this->error('请选择要接取的任务');
        }
        $map = [
            ['status', '=', 1],
            ['id', '=', $id],
            // ['day_start', 'between', [0, $time]],
            // ['day_end', 'notbetween', [1, $time]],
        ];
        $task = TasksModel::where($map)->find();
        if (empty($task)) {
            $this->error('任务不存在');
        }
        $time = time();
        if (!empty($task['time_start']) || !empty($task['time_end'])) {
            if (($task['day_start'] < $time && $task['day_start'] > 0) || $task['day_end'] > $time) {
                $this->error('任务未开放');
            }
        }
        if (!empty($task['time_start']) && !empty($task['time_end'])) {
            $now = Time::startEnd($task['time_start'], $task['time_end']);
            if ($now[0] > $time || $now[1] < $time) {
                $this->error(lang('每天任务开放时间{:time_start} - {:time_end}', $task));
            }
        }
        $today = Time::today();
        $map   = [
            ['tid', '=', $id],
            ['create_uid', '=', $uid],
            ['create_day', '=', $today[0]],
        ];
        $record = TaskRecordModel::where($map)->find();
        if (!empty($record)) {
            $this->error('任务已经接取，请于结束时间内完成');
        }
        $res = TaskRecordModel::create([
            'tid'        => $task['id'],
            'create_uid' => $uid,
            'create_day' => $today[0],
        ]);
        if ($res) {
            $this->success('任务接取成功');
        } else {
            $this->error('任务接取失败');
        }
    }

    /**
     * [complete 完成任务]
     * @return [type] [description]
     */
    public function complete()
    {
        return [];
    }

    /**
     * 首页
     */
    public function detail()
    {
        $id         = input('get.id/d', 1);
        $this->info = db('xy_index_msg')->where('id', $id)->find();

        return $this->fetch();
    }

    /**
     * 换一个客服
     */
    public function other_cs()
    {
        $data = db('xy_cs')->where('status', 1)->where('id', '<>', $id)->find();
        if ($data) {
            return json(['code' => 0, 'info' => lang('Request succeeded'), 'data' => $data]);
        }

        return json(['code' => 1, 'info' => lang('No data')]);
    }

    //---------------------------------------------------
    //支付
    //---------------------------------------------------

}
