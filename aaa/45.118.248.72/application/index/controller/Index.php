<?php

namespace app\index\controller;

use app\common\model\Message as MessageModel;
use Hidehalo\Nanoid\Client as NanoidClient;
use library\Controller;
use think\Db;
use util\Time;

/**
 * 应用入口
 * Class Index
 * @package app\index\controller
 */
class Index extends Base
{
    /**
     * 入口跳转链接
     */
    public function index()
    {
        $this->redirect('home');
    }

    public function home()
    {
        $this->info    = Db::name('xy_index_msg')->field('content')->select();
        $this->balance = Db::name('xy_users')->where('id', session('user_id'))->sum('balance');
        $this->banner  = Db::name('xy_banner')->select();
        $this->notice  = db('xy_index_msg')->where('id', 6)->find();
        $this->assign('pic', getinvite_pic(session('user_id')));
        $this->cate = db('xy_goods_cate')->order('id asc')->select();
        $map        = [
            ['xc.status', '=', 1],
            ['xc.special', '>', 0],
        ];
        $lists = db('xy_convey')
            ->alias('xc')
            ->leftJoin('xy_users u', 'u.id=xc.uid')
            ->where($map)
            ->limit(20)
            ->field('xc.uid,count(xc.uid) sum,u.username')
            ->order('sum DESC')
            ->group('xc.uid')
            ->select();
        $this->conveys = [];
        foreach ($lists as $key => $value) {
            $value['con']        = '用户' . hiddenCharacter($value['username']) . '完成次数' . $value['sum'];
            $this->conveys[$key] = $value;
        }
        return $this->fetch();
    }

    //获取首页图文
    public function get_msg()
    {
        $type = input('post.type/d', 1);
        $data = Db::name('xy_index_msg')->find($type);
        if ($data) {
            return json(['code' => 0, 'info' => lang('Request succeeded'), 'data' => $data]);
        } else {
            return json(['code' => 1, 'info' => lang('No data')]);
        }

    }

    //获取首页图文
    public function getTongji()
    {
        $type          = input('post.type/d', 1);
        $data          = array();
        $yesterday     = Time::yesterday();
        $data['user']  = formatNumber(db('xy_users')->where('status', 1)->where('addtime', 'between', $yesterday)->count('id') + sysconf('index_users'), 0);
        $data['goods'] = formatNumber(sysconf('index_goods'), 0);
        $data['price'] = formatNumber(db('xy_convey')->where('status', 1)->where('endtime', 'between', $yesterday)->sum('num') + sysconf('index_price'));
        $user_order    = db('xy_convey')->where('status', 1)->where('addtime', 'between', Time::today())->field('uid')->Distinct(true)->select();
        $data['num']   = formatNumber(count($user_order) + sysconf('index_num'), 0);

        if ($data) {
            return json(['code' => 0, 'info' => lang('Request succeeded'), 'data' => $data]);
        } else {
            return json(['code' => 1, 'info' => lang('No data')]);
        }
    }

    /**
     * [scrollnews description]
     * @return [type] [description]
     */
    public function scrollnews()
    {
        $client   = new NanoidClient();
        $type     = input('type') ?? 0;
        $num      = rand(10, 30);
        $data     = [];
        $alphabet = spliceString([1]);
        for ($i = 0; $i < $num; $i++) {
            $t        = rand(1, 10);
            $username = hiddenCharacter($client->formattedId($alphabet, 5));
            if ($t < 5) {
                if ($type != 1) {
                    $data[] = lang('User successfully opened membership', [$username]);
                }
            } else {
                $money  = rand(50, 10000) / 100;
                $data[] = lang('Congratulations to users for getting commissions', [$username, $money]);
            }
        }
        if ($data) {
            return json(['code' => 0, 'info' => lang('Request succeeded'), 'data' => $data]);
        } else {
            return json(['code' => 1, 'info' => lang('No data')]);
        }
    }

    public function getDanmu()
    {
        $barrages = //弹幕内容
        array(
            array(
                'info' => lang('User successfully opened membership', ['173***4985']),
                'href' => '/index/ctrl/deposit_admin',

            ),
            array(
                'info'  => lang('User successfully opened membership', ['136***1524']),
                'href'  => '/index/ctrl/deposit_admin',
                'color' => '#ff6600',

            ),
            array(
                'info'   => lang('User successfully opened membership', ['139***7878']),
                'href'   => '/index/ctrl/deposit_admin',
                'bottom' => 450,
            ),
            array(
                'info'  => lang('User successfully opened membership', ['159***7888']),
                'href'  => '/index/ctrl/deposit_admin',
                'close' => false,

            ), array(
                'info' => lang('User successfully opened membership', ['151***7799']),
                'href' => '/index/ctrl/deposit_admin',
            ),
        );

        echo json_encode($barrages);
    }

    public function tip()
    {
        $map = [
            ['uid', '=', $this->user['id']],
            ['type', '=', 2],
            ['have_read', '=', 0],
            ['tip', '=', 1],
        ];
        $info = MessageModel::where($map)->find();
        if (empty($info)) {
            return json(['code' => 1, 'info' => lang('No data')]);
        } else {
            if ($info['special'] == 0) {
                $info->tip = 0;
                $info->save();
            }
            // 请求成功
            return json(['code' => 0, 'info' => lang('Request succeeded'), 'data' => $info]);
        }

    }

    public function tel()
    {
        $number = input('tel');
        $iso    = input('iso', 'JP');
        if (extract_phone_number($number, $iso)) {
            echo 1;
        } else {
            echo 2;
        }

        exit;
        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
        try {
            $swissNumberProto = $phoneUtil->parse($number, $iso);
            echo '111', '<br />';
            var_dump($swissNumberProto);
            $isValid = $phoneUtil->isValidNumber($swissNumberProto);
            var_dump($isValid); // true
        } catch (\libphonenumber\NumberParseException $e) {
            var_dump($e);
        }
    }
}
