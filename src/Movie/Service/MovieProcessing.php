<?php

declare(strict_types=1);

namespace App\Movie\Service;

use App\Movie\Entity\Movie;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Exception;
use Psr\Log\LoggerInterface;
use SimpleXMLElement;
use Symfony\Component\Console\Exception\RuntimeException;

/**
 * Обработка фильма.
 * Обновляем текущую запись или добавляем новую.
 */
class MovieProcessing
{
    private EntityManagerInterface $entityManager;
    private LoggerInterface $logger;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    /**
     * @throws ORMException
     * @throws Exception
     */
    public function run(string $data, int $count): void
    {
        $i = 0;
        foreach ($this->processXML($data)->channel->item as $item) {
            if (++$i > $count) {
                break;
            }

            $title = (string) $item->title;
            $movie = $this->entityManager->getRepository(Movie::class)->findOneBy(['title' => $title]);

            if ($movie === null) {
                $this->logger->info('Create new Movie', ['title' => $title]);

                $movie = new Movie(
                    (string) $item->title,
                    (string) $item->link,
                    (string) $item->description,
                    new DateTime((string) $item->pubDate),
                    $this->getImageUrl((string) $item->children("content", true)->encoded)
                );

                $this->entityManager->persist($movie);
            } else {
                $this->logger->info('Movie found', ['title' => $title]);
            }
        }

        $this->entityManager->flush();
    }

    /**
     * @throws Exception
     */
    private function processXML(string $data): SimpleXMLElement
    {
        $xml = (new SimpleXMLElement($data))->children();

        if (!property_exists($xml, 'channel')) {
            throw new RuntimeException('Could not find \'channel\' element in feed');
        }

        return $xml;
    }

    private function getImageUrl($content): string
    {
        $matches = [];
        preg_match('/src="([^"]*)"/i', $content, $matches);

        return $matches[1];
    }
}
