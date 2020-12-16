<?php

return [
    // 日志记录方式，内置 file socket 支持扩展
    'type'         => 'File',
    // 是否JSON格式记录
    'json'         => false,
    // 设置日志文件名
    'single'       => 'single',
    // 是否记录trace信息到日志
    'record_trace' => true,
    // 最多保留50个文件
    'max_files'    => 50,
    // 日志保存目录
    'path'         => ROOT_PATH . 'runtime/log/' . date('Ymd') . '/',
    // 日志每10兆分割文件
    'file_size'    => 10485760,
    // 设置记录目录的类型
    'level'        => [
        'error',
        'auto_freeze',
        'settlement',
        'cancel_order',
        'unfreeze',
    ],
    // 日志类型分别写入文件
    'apart_level'  => [
        'error',
        'auto_freeze',
        'settlement',
        'cancel_order',
        'unfreeze',
    ],
];
