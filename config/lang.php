<?php

// +----------------------------------------------------------------------
// | 多语言设置
// +----------------------------------------------------------------------

use think\facade\Env;

return [
    // 默认语言
    'default_lang'    => Env::get('lang.default', 'ja-jp'),
    // 开启语言切换
    'lang_switch_on'  => false,
    // 允许的语言列表
    'allow_lang_list' => ['zh-cn', 'en-us', 'ja-jp'],
    // 多语言自动侦测变量名
    'detect_var'      => 'lang',
    // 是否使用Cookie记录
    'use_cookie'      => true,
    // 多语言cookie变量
    'cookie_var'      => 'think_lang',
    // 扩展语言包
    'extend_list'     => [],
    // Accept-Language转义为对应语言包名称
    'accept_language' => [
        'zh-hans-cn' => 'zh-cn',
    ],
    // 是否支持语言分组
    'allow_group'     => false,
];

// // Lojban
// 'art-lojban'  => '',
// // Azerbaijani in Arabic script
// 'az-Arab'     => '',
// // Azerbaijani in Cyrillic                  script
// 'az-Cyrl'     => '',
// // Azerbaijani in Latin script
// 'az-Latn'     => '',
// // Belarusian in Latin script
// 'be-Latn'     => '',
// // Bosnian in Cyrillic script
// 'bs-Cyrl'     => '',
// // Bosnian in Latin script
// 'bs-Latn'     => '',
// // Gaulish
// 'cel-gaulish' => '',
// // German, traditional                      orthography
// 'de-1901'     => '',
// // German, orthography of 1996
// 'de-1996'     => '',
// // German, Austrian variant, traditional orthography
// 'de-AT-1901'  => '',
// // German, Austrian variant, orthography of 1996
// 'de-AT-1996'  => '',
// // German, Swiss variant, traditional orthography
// 'de-CH-1901'  => '',
// // German, Swiss variant, orthography of 1996
// 'de-CH-1996'  => '',
// // German, German variant, traditional orthography
// 'de-DE-1901'  => '',
// // German, German variant, orthography of 1996
// 'de-DE-1996'  => '',
// // Greek in Latin script
// 'el-Latn'     => '',
// // Boontling
// 'en-boont'    => '',
// // English, Oxford English Dictionary spelling
// 'en-GB-oed'   => '',
// // English Liverpudlian dialect known as 'Scouse'
// 'en-scouse'   => '',
// // Latin American Spanish
// 'es-419'      => '',
// // Amis
// 'i-ami'       => '',
// // Bunun
// 'i-bnn'       => '',
// // Default Language Context
// 'i-default'   => '',
// // Enochian
// 'i-enochian'  => '',
// // Hakka
// 'i-hak'       => '',
// // Klingon
// 'i-klingon'   => '',
// // Luxembourgish
// 'i-lux'       => '',
// // Mingo
// 'i-mingo'     => '',
// // Navajo
// 'i-navajo'    => '',
// // Paiwan
// 'i-pwn'       => '',
// // Tao
// 'i-tao'       => '',
// // Tayal
// 'i-tay'       => '',
// // Tsou
// 'i-tsu'       => '',
// // Inuktitut in Canadian Aboriginal
// 'iu-Cans'     => '',
// // Inuktitut in Latin script
// 'iu-Latn'     => '',
// // Mongolian in Cyrillic script
// 'mn-Cyrl'     => '',
// // Mongolian in Mongolian script
// 'mn-Mong'     => '',
// // Norwegian "Book language"
// 'no-bok'      => '',
// // Norwegian "New Norwegian"
// 'no-nyn'      => '',
// // Belgian-French Sign Language
// 'sgn-BE-fr'   => '',
// // Belgian-Flemish Sign Language
// 'sgn-BE-nl'   => '',
// // Brazilian Sign Language
// 'sgn-BR'      => '',
// // Swiss German Sign Language
// 'sgn-CH-de'   => '',
// // Colombian Sign Language
// 'sgn-CO'      => '',
// // German Sign Language
// 'sgn-DE'      => '',
// // Danish Sign Language
// 'sgn-DK'      => '',
// // Spanish Sign Language
// 'sgn-ES'      => '',
// // French Sign Language
// 'sgn-FR'      => '',
// // British Sign Language
// 'sgn-GB'      => '',
// // Greek Sign Language
// 'sgn-GR'      => '',
// // Irish Sign Language
// 'sgn-IE'      => '',
// // Italian Sign Language
// 'sgn-IT'      => '',
// // Japanese Sign Language
// 'sgn-JP'      => '',
// // Mexican Sign Language
// 'sgn-MX'      => '',
// // Nicaraguan Sign Language
// 'sgn-NI'      => '',
// // Dutch Sign Language
// 'sgn-NL'      => '',
// // Norwegian Sign Language
// 'sgn-NO'      => '',
// // Portuguese Sign Language
// 'sgn-PT'      => '',
// // Swedish Sign Language
// 'sgn-SE'      => '',
// // American Sign Language
// 'sgn-US'      => '',
// // South African Sign Language
// 'sgn-ZA'      => '',
// // Natisone dialect, Nadiza dialect
// 'sl-nedis'    => '',
// // Resian, Resianic, Rezijan
// 'sl-rozaj'    => '',
// // Serbian in Cyrillic script
// 'sr-Cyrl'     => '',
// // Serbian in Latin script
// 'sr-Latn'     => '',
// // Tajik in Arabic script
// 'tg-Arab'     => '',
// // Tajik in Cyrillic script
// 'tg-Cyrl'     => '',
// // Uzbek in Cyrillic script
// 'uz-Cyrl'     => '',
// // Uzbek in Latin script
// 'uz-Latn'     => '',
// // Yiddish, in Latin script
// 'yi-latn'     => '',
// // Mandarin Chinese
// 'zh-cmn'      => '',
// // Mandarin Chinese (Simplified)
// 'zh-cmn-Hans' => '',
// // Mandarin Chinese (Traditional)
// 'zh-cmn-Hant' => '',
// // Chinese, in simplified script
// 'zh-Hans'     => '',
// // PRC Mainland Chinese in simplified script
// 'zh-Hans-CN'  => '',
// // Hong Kong Chinese in simplified script
// 'zh-Hans-HK'  => '',
// // Macao Chinese in simplified script
// 'zh-Hans-MO'  => '',
// // Singapore Chinese in simplified script
// 'zh-Hans-SG'  => '',
// // Taiwan Chinese in simplified script
// 'zh-Hans-TW'  => '',
// // Chinese, in traditional script
// 'zh-Hant'     => '',
// // PRC Mainland Chinese in traditional script
// 'zh-Hant-CN'  => '',
// // Hong Kong Chinese in traditional script
// 'zh-Hant-HK'  => '',
// // Macao Chinese in traditional script
// 'zh-Hant-MO'  => '',
// // Singapore Chinese in traditional script
// 'zh-Hant-SG'  => '',
// // Taiwan Chinese in traditional script
// 'zh-Hant-TW'  => '',
// // Kan or Gan
// 'zh-gan'      => '',
// // Mandarin or Standard Chinese
// 'zh-guoyu'    => '',
// // Hakka
// 'zh-hakka'    => '',
// // Min, Fuzhou, Hokkien, Amoy or Taiwanese
// 'zh-min'      => '',
// // Minnan, Hokkien, Amoy, Taiwanese, Southern Min, Southern Fujian, Hoklo, Southern Fukien, Ho-lo
// 'zh-min-nan'  => '',
// // Shanghaiese or Wu
// 'zh-wuu'      => '',
// // Xiang or Hunanese
// 'zh-xiang'    => '',
// // Cantonese
// 'zh-yue'      => '',
