<?php

use CViniciusSDias\Aggregate\Infrastructure\Persistence\Doctrine\EntityManagerFactory;
use Doctrine\ORM\Tools\Console\ConsoleRunner;

require_once 'vendor/autoload.php';

return ConsoleRunner::createHelperSet((new EntityManagerFactory())->createEntityManager([
    'driver' => 'pdo_sqlite',
    'path' => ':memory:',
]));
