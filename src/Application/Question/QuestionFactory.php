<?php

namespace CViniciusSDias\Aggregate\Application\Question;

use CViniciusSDias\Aggregate\Domain\Question\Question;
use CViniciusSDias\Aggregate\Domain\Question\QuestionId;

class QuestionFactory
{
    public function createQuestion(string $questionType, QuestionId $questionId, string $prompt, bool $required): Question
    {
        $className = 'CViniciusSDias\\Aggregate\\Domain\\Question\\';
        $className .= str_replace(' ', '', ucwords(str_replace('_', ' ', $questionType)));

        return new $className($questionId, $prompt, $required);
    }
}
