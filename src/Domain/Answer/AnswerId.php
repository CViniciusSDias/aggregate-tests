<?php

namespace CViniciusSDias\Aggregate\Domain\Answer;

use CViniciusSDias\Aggregate\Domain\Id;

class AnswerId extends Id
{
    protected function prefix(): string
    {
        return 'answer_';
    }
}
