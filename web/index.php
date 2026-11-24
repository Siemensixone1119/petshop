<?php

$debug = getenv('YII_DEBUG');
$environment = getenv('YII_ENV');

defined('YII_DEBUG') or define(
    'YII_DEBUG',
    $debug === false ? true : filter_var($debug, FILTER_VALIDATE_BOOLEAN)
);
defined('YII_ENV') or define('YII_ENV', $environment ?: 'dev');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();
