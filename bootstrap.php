<?php

use App\Base\Provider\AppProvider;
use App\Base\Provider\ConsoleCommandProvider;
use App\Base\Provider\DoctrineOrmProvider;
use App\Base\Provider\RenderProvider;
use App\Base\Provider\WebProvider;
use App\Base\Support\Config;
use App\Base\Support\ServiceProviderInterface;
use Symfony\Component\Dotenv\Dotenv;
use App\Base\Container\Container;

require_once __DIR__ . '/vendor/autoload.php';

(new Dotenv('APP_ENV'))->loadEnv(__DIR__ . '/.env');

$env = $_ENV['APP_ENV'];
if (!$env) {
    $env = 'dev';
}

$config = new Config(__DIR__ . '/config', $env, __DIR__);

$providers = [
    AppProvider::class,
    DoctrineOrmProvider::class,
    ConsoleCommandProvider::class,
    WebProvider::class,
    RenderProvider::class,
];

$container = new Container([
    Config::class => static function () use ($config) { return $config; },
]);

foreach ($providers as $providerClassName) {
    if (!class_exists($providerClassName)) {
        throw new RuntimeException(sprintf('Provider %s not found', $providerClassName));
    }
    $provider = new $providerClassName;
    if (!($provider instanceof ServiceProviderInterface)) {
        throw new RuntimeException(sprintf('%s class is not a Service Provider', $providerClassName));
    }
    $provider->register($container);
}

return $container;
