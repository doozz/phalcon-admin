<?php

use Phalcon\Di\FactoryDefault\Cli as CliDI,
    Phalcon\Cli\Console as ConsoleApp,
    Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter,
    Phalcon\Loader;

date_default_timezone_set( 'Asia/Shanghai');
$di = new CliDI;
// 根目录定义
define('DEBUG', TRUE);
define("ROOT_PATH", dirname(__DIR__));
require_once ROOT_PATH . '/../components/composer/vendor/autoload.php';
$di->setShared('config', function() {
    if (DEBUG) {
        $config = require __DIR__ . '/../config/config.php';
    } else {
        $config = require __DIR__ . '/../config/config.dev.php';
    }  
    return $config;
});

$config = $di['config'];

$loader = new Loader();
$loader->registerDirs([
    __DIR__ . '/../controllers/',
    __DIR__ . '/../models/',
    __DIR__ . '/../logics/',
    __DIR__ . '/../tasks/',
])->register();

$loader->registerNamespaces([
    'Components\Utils' => __DIR__ . '/../../components/utils/',
    'Components\Bets' => __DIR__ . '/../../components/bets/',
    'Plugins' => __DIR__ . '/../../plugins/'
]);

$di->setShared('db', function() use ($config) {
    $db = new DbAdapter(
        [
            'host' => $config['db']['host'],
            'port' => $config['db']['port'],
            'username' => $config['db']['user'],
            'password' => $config['db']['pass'],
            'dbname' => $config['db']['name'],
            'options' => [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, // 如果出现错误抛出错误警告
                \PDO::ATTR_ORACLE_NULLS => \PDO::NULL_TO_STRING, // 把所有的NULL改成""
                \PDO::ATTR_TIMEOUT => 30
            ]
        ]
    );
    $db->query('SET NAMES utf8mb4');
    return $db;
});

$di->setShared('helper', new Components\Utils\Helper);

$di->setShared('redis', function() use ($config) {
    $redis = new Redis();
    $redis->connect($config['redis']['host'], $config['redis']['port']);
    $redis->select($config['redis']['db']);
    
    return $redis;
});

$console = new ConsoleApp;

$console->setDI($di);

$arguments = [];

foreach($argv as $k => $arg) {
    if($k === 1)
        $arguments['task'] = $arg;
    elseif($k === 2)
        $arguments['action'] = $arg;
    elseif($k >= 3)
        $arguments['params'][] = $arg;
}

try
{
    $console->handle($arguments);
}
catch(Exception $e)
{
    echo $e->getMessage() . PHP_EOL . $e->getTraceAsString();
    exit(255);
}
