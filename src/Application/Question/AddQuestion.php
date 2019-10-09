<?php

namespace CViniciusSDias\Aggregate\Application\Question;

use CViniciusSDias\Aggregate\Domain\Answer\AnswerId;
use CViniciusSDias\Aggregate\Domain\Question\Question;
use CViniciusSDias\Aggregate\Domain\Question\QuestionId;
use CViniciusSDias\Aggregate\Domain\Question\QuestionRepository;

class AddQuestion
{
    private QuestionRepository $questionRepository;

    public function __construct(QuestionRepository $questionRepository)
    {
        $this->questionRepository = $questionRepository;
    }

    public function execute(AddQuestionCommand $command)
    {
        $className = 'CViniciusSDias\\Aggregate\\Domain\\Question\\';
        $className .= str_replace(' ', '', ucwords(str_replace('_', ' ', $command->questionType())));
        /** @var Question $question */
        $question = new $className(new QuestionId(), $command->prompt(), $command->required());

        foreach ($command->answerCommands() as $answer) {
            $question->addAnswer(new AnswerId(), $answer->prompt());
        }

        $this->questionRepository->save($question);
    }
}
