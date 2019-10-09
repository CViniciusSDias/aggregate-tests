<?php

namespace CViniciusSDias\Aggregate\Infrastructure\Question;

use CViniciusSDias\Aggregate\Domain\Question\Question;
use CViniciusSDias\Aggregate\Domain\Question\QuestionId;
use CViniciusSDias\Aggregate\Domain\Question\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class InMemoryQuestionRepository implements QuestionRepository
{
    private Collection $questionList;

    public function __construct()
    {
        $this->questionList = new ArrayCollection();
    }

    public function save(Question $question): Question
    {
        $this->questionList->contains($question)
            ? $this->questionList->set($this->questionList->indexOf($question), $question)
            : $this->questionList->add($question);

        return $question;
    }

    public function ofId(QuestionId $id): Question
    {
        return $this->questionList
            ->filter(fn (Question $question) => $question->id() == $id)
            ->first();
    }
}
