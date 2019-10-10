<?php

namespace CViniciusSDias\Aggregate\Application\Service\Question;

use CViniciusSDias\Aggregate\Domain\Question\QuestionRepository;

class RemoveQuestion
{
    private QuestionRepository $questionRepository;

    public function __construct(QuestionRepository $questionRepository)
    {
        $this->questionRepository = $questionRepository;
    }

    public function execute(RemoveQuestionCommand $command)
    {
        $this->questionRepository->removeOfId($command->questionId());
    }
}
