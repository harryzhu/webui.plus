<?php
use Phalcon\Autoload\Loader;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Application;
use Phalcon\Mvc\Url as UrlProvider;
use Phalcon\Mvc\View;

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

require_once(BASE_PATH . "/../config/settings.global.php");

// Register an autoloader
$loader = new Loader();
$loader->setDirectories(
    [
        APP_PATH . '/controllers/',
        APP_PATH . '/models/',
    ]
)
       ->register()
;

// Create a DI
$container = new FactoryDefault();

		
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
        "host"     => U2_DB_HOST,
        "username" => U2_DB_USERNAME,
        "password" => U2_DB_PASSWORD,
        "dbname"   => U2_DB_DBNAME,
        "prefix"   => U2_DB_PREFIX,
    ]);
};

// Handle the request
try {
    $application = new Phalcon\Mvc\Application($container);
    $response    = $application->handle($_SERVER["REQUEST_URI"]);
    $response->send();
} catch (Exception $e) {
    echo "Exception: ", $e->getMessage();
}
