<?php

namespace app\admin\controller;

use app\common\model\ConveyMatch as ConveyMatchModel;
use library\Controller;
use think\facade\Request;
use think\facade\View;

/**
 * 特殊刷单匹配信息
 * Class Users
 * @package app\admin\controller
 */
class ConveyMatch extends Base
{

    public function initialize()
    {
        parent::initialize();
    }

    public function index()
    {
        $param = input('');
        if (Request::isPost()) {
            $page  = Request::post('page') ? Request::post('page') : 1;
            $limit = Request::post('limit') ? Request::post('limit') : $this->pageSize;
            $map   = [];
            $map2  = [];
            if (isset($param['type']) && $param['type'] >= 0) {
                $map[]  = ['u.type', '=', $param['type']];
                $map2[] = ['type', '=', $param['type']];
            }
            if (!empty($param['value'])) {
                switch ($param['valueKey']) {
                    case 'username':
                    case 'email':
                        $map[]  = ['u.' . $param['valueKey'], 'like', '%' . $param['value'] . '%'];
                        $map2[] = [$param['valueKey'], 'like', '%' . $param['value'] . '%'];
                        break;
                    case 'code':
                        $map[] = ['ui.' . $param['valueKey'], 'like', '%' . $param['value'] . '%'];
                        break;
                    default:
                        $map[]  = ['u.' . $param['valueKey'], '=', $param['value']];
                        $map2[] = [$param['valueKey'], '=', $param['value']];
                        break;
                }
            }
            if (!empty($param['startTime'])) {
                $start  = strtotime($param['startTime'] . ' 00:00:00');
                $map[]  = ['u.' . $param['valueKey'], '>', $start];
                $map2[] = [$param['valueKey'], '>', $start];
            }
            if (!empty($param['endTime'])) {
                $time = strtotime($param['endTime'] . ' 23:59:59');
                if (!empty($param['startTime']) && $start > $time) {
                    $this->error('请重新选择结束时间');
                }
                $map[]  = ['u.' . $param['valueKey'], '<', $time];
                $map2[] = [$param['valueKey'], '<', $time];
            }
            $sort = 'id DESC';
            if (!empty($param['sort'])) {
                $sort = $param['sortKey'] . ' ' . $param['sort'];
            }
            $list = ConveyMatchModel::where($map)
                ->order($sort)
                ->paginate(['list_rows' => $limit, 'page' => $page])
                ->toArray();
            return $result = [
                'code'  => 0,
                'msg'   => '获取成功!',
                'data'  => $list['data'],
                'count' => $list['total'],
                'where' => $param,
                'token' => [
                    'save'   => systoken('admin/convey_match/save'),
                    'delete' => systoken('admin/convey_match/delete'),
                ],
            ];
        }
        // $cates = GoodsCateModel::where('addtime', '>', 0)->select();
        $view = [
            'where' => $param,
        ];
        // $this->assign('cates', $cates);
        View::assign($view);
        return View::fetch();
    }

    /**
     * [save 保存数据]
     * @param  string $value [description]
     * @return [type]        [description]
     */
    public function save()
    {
        if (request()->isPost()) {
            $this->applyCsrfToken();
            $param = input('');
            if (empty($param['id'])) {
                $res = (new ConveyMatchModel)->allowField(true)->save($param);
            } else {
                $res = (new ConveyMatchModel)->allowField(true)->save($param, ['id' => $param['id']]);
            }
            if ($res) {
                $this->success('操作成功');
            } else {
                $this->error('操作失败');
            }
        }
    }

    /**
     * 删除
     * @auth true
     * @menu true
     */
    public function delete()
    {
        $this->applyCsrfToken();
        $id = input('post.id/d', 0);
        if (empty($id)) {
            return $this->error('参数错误');
        }
        $info = ConveyMatchModel::get($id);
        $res  = $info->delete();
        if ($res) {
            $this->success('删除成功!');
        } else {
            $this->error('删除失败!');
        }

    }
}
