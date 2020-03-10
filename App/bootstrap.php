<?php

use App\Config;
use App\Di\Container;
use App\Helper\ClassHelper;
use App\Http\Request;
use App\Http\Response;
use App\Kernel;
use App\MySQL\Interfaces\IArrayDataManager;
use App\MySQL\Interfaces\IConnection;
use App\MySQL\Interfaces\IObjectDataManager;
use App\MySQL\ArrayDataManager;
use App\MySQL\Connection;
use App\MySQL\ObjectDataManager;
use App\Service\CartService;
use App\Service\UserService;

define('APP_DIR', realpath(__DIR__ . '/../'));

require_once APP_DIR . '/vendor/autoload.php';

session_start();

$container = new Container([
    IConnection::class => Connection::class,
    IArrayDataManager::class => ArrayDataManager::class,
    IObjectDataManager::class => ObjectDataManager::class,
]);

$container->setSingleton(Config::class);
$config = $container->get(Config::class);

$container->setSingleton(ClassHelper::class);
$container->setSingleton(Request::class);
$container->setSingleton(Response::class);
$container->setSingleton(CartService::class);
$container->setSingleton(UserService::class);

$container->setSingleton(Connection::class, [
    $config->get('db.host'),
    $config->get('db.user'),
    $config->get('db.password'),
    $config->get('db.db_name'),
]);

$container->setSingleton(ArrayDataManager::class);
$container->setSingleton(ObjectDataManager::class);

$container->setSingleton(Smarty::class, [], function($smarty) use ($container) {
    $config = $container->get(Config::class);
    $smarty->template_dir = $config->get('template.template_dir');
    $smarty->compile_dir = $config->get('template.compile_dir');
    $smarty->cache_dir = $config->get('template.cache_dir');
});

$kernel = $container->get(Kernel::class);