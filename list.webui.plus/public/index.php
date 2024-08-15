<?php
use Phalcon\Autoload\Loader;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;

use Phalcon\Crypt;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Application;
use Phalcon\Http\Response;
use Phalcon\Http\Response\Cookies;
use Phalcon\Session\Adapter\Stream as SessionAdapter;
use Phalcon\Session\Manager as SessionManager;
use Phalcon\Flash\Session as FlashSession;
use Phalcon\Mvc\Url as UrlProvider;
use Phalcon\Mvc\View;

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

require_once(BASE_PATH . "/../config/settings.global.php");

global $_G;
$_G = array();

require_once(APP_PATH . '/config/config.php');
require_once(APP_PATH . '/config/loader.php');
require_once(APP_PATH . '/config/functions.php');

// Register an autoloader
$loader = new Loader();
$loader->setDirectories(
    [
        APP_PATH . '/config/',
        APP_PATH . '/controllers/',
        APP_PATH . '/models/',
    ]
)
->register()
;

// Create a DI
$container = new FactoryDefault();

$container->setShared(
    'session', 
    function () {
        $session = new SessionManager();
        $files   = new SessionAdapter(
            [
                'savePath' =>'/tmp',
            ]
        );
        $session->setAdapter($files);
        $session->start();

        return $session;
    }
);

// Setting up the view component
$container['view'] = function () {
    $view = new View();
    $view->setViewsDir(APP_PATH . '/views/');
    return $view;
};

// Setup a base URI so that all generated URIs include the "tutorial" folder
$container['url'] = function () {
    $url = new UrlProvider();
    $url->setBaseUri('/');
    return $url;
};



// Set the database service
$container['db'] = function () {
    return new DbAdapter([
        "host"     => LIST_DB_HOST,
        "username" => LIST_DB_USERNAME,
        "password" => LIST_DB_PASSWORD,
        "dbname"   => LIST_DB_DBNAME,
        "prefix"   => LIST_DB_PREFIX,
    ]);
};

$container['cookies'] = function() {
    $signKey  = md5("#1dj8$=dp?.ak//j1V$~%*0XaK\xb1\x8d\xa9\x98\x054t7w!z%C*F-Jk\x98\x05\\\x5c");
    $cookies = new Cookies();
    $cookies->setSignKey($signKey); //Use your own key!
    return $cookies;
};

$container['flash'] = function () {
    return new FlashSession(array(
        'error'   => 'alert alert-danger',
        'success' => 'alert alert-success',
        'notice'  => 'alert alert-info',
        'warning' => 'alert alert-warning'
    ));
};

// Handle the request
try {
    $application = new Phalcon\Mvc\Application($container);
    $response    = $application->handle($_SERVER["REQUEST_URI"]);
    $response->send();
} catch (Exception $e) {
    echo "Exception: ", $e->getMessage();
}
