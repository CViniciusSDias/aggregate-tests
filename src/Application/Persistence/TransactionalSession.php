<?php

namespace CViniciusSDias\Aggregate\Application\Persistence;

interface TransactionalSession
{
    public function executeAtomically(callable $operation);
}
