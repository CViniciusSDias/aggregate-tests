<?php

namespace CViniciusSDias\Aggregate\Infrastructure\Persistence;

use CViniciusSDias\Aggregate\Application\Persistence\TransactionalSession;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineSession implements TransactionalSession
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function executeAtomically(callable $operation)
    {
        return $this->entityManager->transactional($operation);
    }
}
