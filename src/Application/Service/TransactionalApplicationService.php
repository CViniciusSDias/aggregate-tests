<?php

namespace CViniciusSDias\Aggregate\Application\Service;

use CViniciusSDias\Aggregate\Application\Persistence\TransactionalSession;

class TransactionalApplicationService
{
    private object $service;
    private TransactionalSession $session;

    public function __construct(object $service, TransactionalSession $session)
    {
        $this->service = $service;
        $this->session = $session;
    }

    public function execute($command)
    {
        return $this->session->executeAtomically(fn () => $this->service->execute($command));
    }
}
