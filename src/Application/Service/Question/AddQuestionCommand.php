<?php

namespace CViniciusSDias\Aggregate\Application\Service\Question;

use CViniciusSDias\Aggregate\Application\Service\Answer\AddAnswerCommand;

class AddQuestionCommand
{
    private string $prompt;
    private array $answerCommands;
    private string $questionType;
    private bool $required;

    /**
     * AddQuestionCommand constructor.
     * @param string $questionType
     * @param string $prompt
     * @param bool $required
     * @param AddAnswerCommand[] $answerCommands
     */
    public function __construct(string $questionType, string $prompt, bool $required, array $answerCommands)
    {
        $this->prompt = $prompt;
        $this->answerCommands = $answerCommands;
        $this->questionType = $questionType;
        $this->required = $required;
    }

    public function prompt(): string
    {
        return $this->prompt;
    }

    /**
     * @return AddAnswerCommand[]
     */
    public function answerCommands(): array
    {
        return $this->answerCommands;
    }

    public function questionType(): string
    {
        return $this->questionType;
    }

    public function required(): bool
    {
        return $this->required;
    }
}
