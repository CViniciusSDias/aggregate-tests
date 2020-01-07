<?php

namespace CViniciusSDias\Aggregate\Infrastructure\Persistence;

use CViniciusSDias\Aggregate\Application\Persistence\TransactionalSession;

class PdoSession implements TransactionalSession
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function executeAtomically(callable $operation)
    {
        $this->pdo->beginTransaction();
        try {
            $return = $operation();
            $this->pdo->commit();
            return $return;
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}
