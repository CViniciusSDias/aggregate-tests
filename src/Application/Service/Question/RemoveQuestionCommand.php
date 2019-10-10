<?php

namespace CViniciusSDias\Aggregate\Application\Service\Question;

use CViniciusSDias\Aggregate\Domain\Question\QuestionId;

class RemoveQuestionCommand
{
    private QuestionId $questionId;

    public function __construct(QuestionId $questionId)
    {
        $this->questionId = $questionId;
    }

    public function questionId(): QuestionId
    {
        return $this->questionId;
    }
}
