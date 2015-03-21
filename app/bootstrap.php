<?php

use Igorw\Silex\ConfigServiceProvider;

//Configure the service providers
$app->register(
    new ConfigServiceProvider(__DIR__."/../app/config/config.php", array('app.path' => getcwd()))
);
if(true === $app['debug']) {
    $app->register(
        new ConfigServiceProvider(__DIR__."/../app/config/config_dev.php", array('app.path' => getcwd()))
    );
}