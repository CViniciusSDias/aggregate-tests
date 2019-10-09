<?php

namespace CViniciusSDias\Aggregate\Application\Answer;

class AddAnswerCommand
{
    private string $prompt;

    public function __construct(string $prompt)
    {
        $this->prompt = $prompt;
    }

    public function prompt(): string
    {
        return $this->prompt;
    }
}
