<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

if (file_exists(dirname(__DIR__).'/config/bootstrap.php')) {
    require dirname(__DIR__).'/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}

if (file_exists(dirname(__DIR__).'/var/cache/test/')) {
    passthru(sprintf('php "%s/bin/console" doctrine:database:drop --force --env=test', dirname(__DIR__)));
    passthru(sprintf('php "%s/bin/console" doctrine:database:create --env=test', dirname(__DIR__)));
    passthru(sprintf('php "%s/bin/console" doctrine:schema:create --env=test', dirname(__DIR__)));
}
