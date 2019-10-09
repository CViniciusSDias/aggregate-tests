<?php

namespace CViniciusSDias\Aggregate\Domain\Answer;

use CViniciusSDias\Aggregate\Domain\Question\Question;

class Answer
{
    private AnswerId $id;
    private string $prompt;
    private Question $question;

    public function __construct(AnswerId $id, string $prompt, Question $question)
    {
        $this->id = $id;
        $this->prompt = $prompt;
        $this->question = $question;
    }

    public function prompt(): string
    {
        return $this->prompt;
    }

    public function id(): AnswerId
    {
        return $this->id;
    }

    public function question(): Question
    {
        return $this->question;
    }
}
