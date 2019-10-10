<?php

namespace CViniciusSDias\Aggregate\Application\DTO\Question;

use CViniciusSDias\Aggregate\Domain\Question\Question;

class QuestionDTO
{
    private string $questionId;

    public function __construct(Question $question)
    {
        $this->questionId = $question->id()->id();
    }

    public function questionId(): string
    {
        return $this->questionId;
    }
}
