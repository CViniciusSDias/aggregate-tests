<?php

namespace CViniciusSDias\Aggregate\Domain\Question;

use CViniciusSDias\Aggregate\Domain\Id;

class QuestionId extends Id
{
    protected function prefix(): string
    {
        return 'question_';
    }
}
