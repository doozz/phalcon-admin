<?php
use Phalcon\Mvc\Application,
    Phalcon\DI\FactoryDefault,
    Phalcon\Loader,
    Phalcon\Events\Manager as EventsManager,
    Phalcon\Mvc\Dispatcher,
    Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter,
    Phalcon\Events\Event,
    Phalcon\Crypt,
    Phalcon\Mvc\Dispatcher\Exception as DispatcherException,
    Phalcon\Session\Adapter\Files as Session,
    Phalcon\Http\Response\Cookies,
    Phalcon\Mvc\View;

define('DS', DIRECTORY_SEPARATOR);
define('DEBUG', FALSE);
// 根目录定义
define("ROOT_PATH", dirname(__DIR__) . DS);
require_once ROOT_PATH . 'components/composer/vendor/autoload.php';

try {
    date_default_timezone_set( 'Asia/Shanghai');
    $di = new FactoryDefault();

    // set config service
    $di->setShared('config', function() {
        if (DEBUG)
            $config = require __DIR__ . '/../app/config/config.php';
        else 
            $config = require __DIR__ . '/../app/config/config.dev.php';
        return $config;
    });

    $config = $di['config'];

    $loader = new Loader();
    $loader->registerDirs([
        __DIR__ . '/../app/controllers/',
        __DIR__ . '/../app/models/',
        __DIR__ . '/../app/logics/',
    ])->register();

    $loader->registerNamespaces([
        'Components\Utils' => __DIR__ . '/../components/utils/',
        'Plugins' => __DIR__ . '/../plugins/',
    ]);

    // set event listener
    $di->setShared('dispatcher', function() use ($config) {
        $eventManager = new EventsManager();

        $eventManager->attach(
            'dispatch:beforeException',
            new Plugins\NotFoundPlugin
        );

        $eventManager->attach(
            'dispatch:beforeExecuteRoute',
            new Plugins\CallShieldPlugin
        );

        $dispatcher = new Dispatcher();
        $dispatcher->setEventsManager($eventManager);

        return $dispatcher;
    });

    $di->setShared('helper', new Components\Utils\Helper);

    $di->setShared('check', new Components\Utils\Validate);

    $di->setShared('session', function() {
        $session = new Session();
        $session->start();
        return $session;
    });

   
    $di->setShared('cookie', function() {
        $cookie = new Cookies();
        $cookie->useEncryption(true);
        return $cookie;
    });

    $di->setShared('crypt', function() use ($config) {
        $crypt = new Crypt();
        $crypt->setKey($config['baseInfo']['cryptKey']);
        return $crypt;
    });

    $di->setShared('redis', function() use ($config) {
        $redis = new Redis();
        $redis->connect($config['redis']['host'], $config['redis']['port']);
        $redis->select($config['redis']['db']);
        
        return $redis;
    });

    // set database
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
        $db->query('SET NAMES utf8');
        return $db;
    });

    $di->setShared('ErrorService', function() {
        $ec = new ErrorsController;
        return $ec;
    });
    
    $di->set('view', function() {
        $view = new View();
        $view->setViewsDir(__DIR__ . '/../app/views/');
        return $view;
    });

    $application = new Application();
    $application->setDI($di);

    echo $application->handle()->getContent();


} catch (Exception $e) {
    if(!DEBUG)
        echo json_encode(array('code' => '500', 'msg' => 'system error'));

	file_put_contents($config['application']['logFile'], '[' . date("Y-m-d H:i:s") . ']code: ' . $e->getCode() . ' msg: ' . $e->getMessage() . "\n", FILE_APPEND);

    if(DEBUG)
    {
        echo json_encode(array(
            'code' => $e->getCode(),
            'msg' => $e->getMessage()
        ));
    }
}
