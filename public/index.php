<?php

namespace think;

$http = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] && $_SERVER['HTTPS'] != 'off') ? 'https' : 'http';
define('SITE_URL', $http . '://' . $_SERVER['HTTP_HOST']); // 网站域名
define('APP_PATH', __DIR__ . '/../application/');
define('ROOT_PATH', __DIR__ . '/../');

require __DIR__ . '/../thinkphp/base.php';

Container::get('app')->run()->send();
