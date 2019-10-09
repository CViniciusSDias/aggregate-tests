<?php

namespace CViniciusSDias\Aggregate\Domain;

abstract class Id
{
    private string $id;

    public function __construct(string $id = null)
    {
        $this->id = $id ?? uniqid($this->prefix());
    }

    abstract protected function prefix(): string;

    public function id(): string
    {
        return $this->id;
    }

    public function __toString()
    {
        return $this->id();
    }
}
