<?php

namespace CViniciusSDias\Aggregate\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

class EntityManagerFactory
{
    public function createEntityManager(array $connection)
    {
        $config = Setup::createXMLMetadataConfiguration([
            __DIR__ . '/Mapping'
        ]);

        return EntityManager::create($connection, $config);
    }
}
