<?php

namespace CViniciusSDias\Aggregate\Infrastructure\DI;

use CViniciusSDias\Aggregate\Domain\Question\Question;
use CViniciusSDias\Aggregate\Domain\Question\QuestionRepository;
use CViniciusSDias\Aggregate\Infrastructure\Domain\Question\DoctrineQuestionRepository;
use CViniciusSDias\Aggregate\Infrastructure\Persistence\Doctrine\EntityManagerFactory;
use DI\ContainerBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

class ContainerCreator
{
    private static ContainerInterface $container;

    public static function instance(): ContainerInterface
    {
        if (!isset(self::$container)) {
            $builder = new ContainerBuilder();
            $builder->addDefinitions([
                EntityManagerInterface::class => fn () => (new EntityManagerFactory())->createEntityManager([
                    'driver' => 'pdo_sqlite',
                    'path' => ':memory:',
                ]),
                QuestionRepository::class => fn (ContainerInterface $c) => $c->get(EntityManagerInterface::class)->getRepository(Question::class),
            ]);

            self::$container = $builder->build();
        }

        return self::$container;
    }
}
