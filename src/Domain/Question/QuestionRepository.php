<?php


namespace CViniciusSDias\Aggregate\Domain\Question;

interface QuestionRepository
{
    public function save(Question $question): Question;
    public function ofId(QuestionId $id): Question;
}
