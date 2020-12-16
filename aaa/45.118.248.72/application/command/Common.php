<?php
namespace app\command;

// use app\common\model\Config as ConfigModel;
use think\console\Command;

// use think\facade\Cache;

class Common extends Command
{
    /**
     * [getConfigs 獲取系統參數]
     *
     * @method getConfigs
     * @param  string  $name  [參數名]
     * @return [type]         [description]
     *
     * @Author Seamless
     * @date   2019-04-24
     *
     */
    protected function getConfigs($name = '')
    {
        $config = [];
        if (Cache::get('sysconfig')) {
            $config = Cache::get('sysconfig');
            if ($name && isset($config[$name])) {
                return Cache::get('sysconfig')[$name];
            }
        }
        $variable = (new ConfigModel)->select();
        foreach ($variable as $key => $value) {
            $config[$value['code']] = $value['value'];
        }
        Cache::set('sysconfig', $config, 0);
        return $name ? $config[$name] : $config;
    }
}
