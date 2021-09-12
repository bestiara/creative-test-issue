<?php

declare(strict_types=1);

namespace App\Movie\Controller;

use App\Movie\Entity\Movie;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Interfaces\RouteCollectorInterface;
use Twig\Environment;

/**
 * Контроллер страницы просмотра фильма
 *
 * @author Dmitry Nikolsky <nikolskiy.d@book24.ru>
 */
class ShowController
{
    public function __construct(
        private RouteCollectorInterface $routeCollector,
        private Environment $twig,
        private EntityManagerInterface $em
    ) {}

    public function show(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            $data = $this->twig->render('home/show.html.twig', [
                'trailer' => $this->fetchData(
                    (int)$request->getAttribute('id')
                ),
            ]);
        } catch (\Exception $e) {
            throw new HttpBadRequestException($request, $e->getMessage(), $e);
        }

        $response->getBody()->write($data);

        return $response;
    }

    protected function fetchData(int $id): Movie
    {
        return $this->em->getRepository(Movie::class)
            ->findOneBy(['id' => $id]);
    }
}
