<?php namespace App\Base\Support;

use App\Base\Container\Container;

interface ServiceProviderInterface
{
    public function register(Container $container): void;
}
