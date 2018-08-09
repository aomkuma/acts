<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    $logger->pushHandler(new Monolog\Handler\RotatingFileHandler($settings['path'], $settings['maxFiles'], $settings['level']));
    return $logger;
};

$container['db'] = function ($c) {
    $settings = $c->get('settings')['db'];
    $capsule = new Illuminate\Database\Capsule\Manager;
    $capsule->addConnection($settings);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();
    return $capsule;
};

$container['LoginController'] = function ($c) {
    return new \App\Controller\LoginController($c->get('logger'), $c->get('db'));
};

$container['CommodityStandardController'] = function ($c) {
    return new \App\Controller\CommodityStandardController($c->get('logger'), $c->get('db'));
};

$container['AutocompleteController'] = function ($c) {
    return new \App\Controller\AutocompleteController($c->get('logger'), $c->get('db'));
};

$container['AcademicBoardController'] = function ($c) {
    return new \App\Controller\AcademicBoardController($c->get('logger'), $c->get('db'));
};

$container['MeetingController'] = function ($c) {
    return new \App\Controller\MeetingController($c->get('logger'), $c->get('db'));
};

$container['StakeholderController'] = function ($c) {
    return new \App\Controller\StakeholderController($c->get('logger'), $c->get('db'));
};

$container['UserAccountController'] = function ($c) {
    return new \App\Controller\UserAccountController($c->get('logger'), $c->get('db'));
};

$container['EmailController'] = function ($c) {
    return new \App\Controller\EmailController($c->get('logger'), $c->get('db'));
};