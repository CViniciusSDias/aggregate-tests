<?php

namespace CViniciusSDias\Aggregate\Application\Service\Question;

use CViniciusSDias\Aggregate\Application\DTO\Question\QuestionDTO;
use CViniciusSDias\Aggregate\Application\Factory\QuestionFactory;
use CViniciusSDias\Aggregate\Domain\Answer\AnswerId;
use CViniciusSDias\Aggregate\Domain\Question\QuestionId;
use CViniciusSDias\Aggregate\Domain\Question\QuestionRepository;

class AddQuestion
{
    private QuestionRepository $questionRepository;
    private QuestionFactory $questionFactory;

    public function __construct(QuestionRepository $questionRepository, QuestionFactory $questionFactory)
    {
        $this->questionRepository = $questionRepository;
        $this->questionFactory = $questionFactory;
    }

    public function execute(AddQuestionCommand $command): QuestionDTO
    {
        $question = $this->questionFactory
            ->createQuestion($command->questionType(), new QuestionId(), $command->prompt(), $command->required());
        foreach ($command->answerCommands() as $answer) {
            $question->addAnswer(new AnswerId(), $answer->prompt());
        }

        $this->questionRepository->save($question);

        return new QuestionDTO($question);
    }
}
