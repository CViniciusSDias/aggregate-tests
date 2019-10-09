<?php

namespace CViniciusSDias\Aggregate\Domain\Question;

use CViniciusSDias\Aggregate\Domain\Answer\Answer;
use CViniciusSDias\Aggregate\Domain\Answer\AnswerId;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Question
{
    private QuestionId $id;
    private string $prompt;
    private Collection $answers;
    private bool $required;

    public function __construct(QuestionId $id, string $prompt, bool $required = false)
    {
        $this->id = $id;
        $this->prompt = $prompt;
        $this->required = $required;
        $this->answers = new ArrayCollection();
    }

    public function id(): QuestionId
    {
        return $this->id;
    }

    public function addAnswer(AnswerId $id, string $answerPrompt)
    {
        if (count($this->answers()) >= 5) {
            throw new \DomainException('Maximum of 5 answers exceded');
        }

        $this->answers->add(new Answer($id, $answerPrompt, $this));
        return $this;
    }

    public function answers(): Collection
    {
        return $this->answers;
    }

    public function prompt(): string
    {
        return $this->prompt;
    }

    public function removeAnswerOfId(AnswerId $answerIdToRemove)
    {
        foreach ($this->answers() as $i => $answer) {
            if ($answer->id() == $answerIdToRemove) {
                $this->answers->remove($i);
            }
        }
    }

    public function isRequired(): bool
    {
        return $this->required;
    }
}
