<?php declare(strict_types=1);

namespace App\Base\Provider;

use App\Movie\Console\FetchDataCommand;
use App\Movie\Service\MovieProcessing;
use App\Base\Command\{RouteListCommand};
use App\Base\Container\Container;
use App\Base\Support\{CommandMap, ServiceProviderInterface};
use Psr\Container\ContainerInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Log\LoggerInterface;
use Slim\Interfaces\RouteCollectorInterface;

class ConsoleCommandProvider implements ServiceProviderInterface
{
    public function register(Container $container): void
    {
        $container->set(RouteListCommand::class, static function (ContainerInterface $container) {
            return new RouteListCommand($container->get(RouteCollectorInterface::class));
        });

        $container->set(FetchDataCommand::class, static function (ContainerInterface $container) {
            return new FetchDataCommand(
                $container->get(ClientInterface::class),
                $container->get(LoggerInterface::class),
                $container->get(MovieProcessing::class),
            );
        });

        $container->get(CommandMap::class)->set(RouteListCommand::getDefaultName(), RouteListCommand::class);
        $container->get(CommandMap::class)->set(FetchDataCommand::getDefaultName(), FetchDataCommand::class);
    }
}
