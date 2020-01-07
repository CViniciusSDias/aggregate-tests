<?php

namespace CViniciusSDias\Aggregate\Tests\Domain\Question;

use CViniciusSDias\Aggregate\Domain\Answer\AnswerId;
use CViniciusSDias\Aggregate\Domain\Question\Question;
use CViniciusSDias\Aggregate\Domain\Question\QuestionId;
use PHPUnit\Framework\TestCase;

class QuestionTest extends TestCase
{
    public function testQuestionCannotHaveMoreThan5Answers()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Maximum of 5 answers exceded');

        $question = new class(new QuestionId(), 'Dummy prompt') extends Question {};
        $question->addAnswer(new AnswerId(), 'Dummy answer prompt');
        $question->addAnswer(new AnswerId(), 'Dummy answer prompt');
        $question->addAnswer(new AnswerId(), 'Dummy answer prompt');
        $question->addAnswer(new AnswerId(), 'Dummy answer prompt');
        $question->addAnswer(new AnswerId(), 'Dummy answer prompt');
        $question->addAnswer(new AnswerId(), 'Dummy answer prompt');
    }
}
