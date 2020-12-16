<?php

namespace app\common\validation;

use think\Db;
use \WebGeeker\Validation\Validation;

/**
 * 支付控制器
 */
class Validations extends Validation
{

    // “错误提示信息模版”翻译对照表
    protected static $langCode2ErrorTemplates;

    // 文本翻译对照表
    protected static $langCodeToTranslations;

    public function __construct()
    {
        $list = db('system_lang')->where(['type' => 2])->cache(true)->select();
        $lang = [];
        foreach ($list as $key => $value) {
            $lang[$value['range']][$value['name']] = $value['content'];
        }
        self::$langCode2ErrorTemplates = $lang;

        $list = db('system_lang')->where(['type' => 3])->cache(true)->select();
        $lang = [];
        foreach ($list as $key => $value) {
            $lang[$value['range']][$value['name']] = $value['content'];
        }
        self::$langCodeToTranslations = $lang;
        Validations::setLangCode(getRange()); // 设置语言代码
    }
}
