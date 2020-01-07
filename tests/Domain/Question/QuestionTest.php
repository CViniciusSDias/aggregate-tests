<?php

namespace CViniciusSDias\Aggregate\Tests\Domain\Question;

use CViniciusSDias\Aggregate\Domain\Answer\AnswerId;
use CViniciusSDias\Aggregate\Domain\Question\Question;
use CViniciusSDias\Aggregate\Domain\Question\QuestionId;
use PHPUnit\Framework\TestCase;

class QuestionTest extends TestCase
{
    private Question $question;

    protected function setUp(): void
    {
        $this->question = new class(new QuestionId(), 'Dummy prompt') extends Question {};
    }

    public function testQuestionCannotHaveMoreThan5Answers()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Maximum of 5 answers exceded');

        $this->question->addAnswer(new AnswerId(), 'Dummy answer prompt');
        $this->question->addAnswer(new AnswerId(), 'Dummy answer prompt');
        $this->question->addAnswer(new AnswerId(), 'Dummy answer prompt');
        $this->question->addAnswer(new AnswerId(), 'Dummy answer prompt');
        $this->question->addAnswer(new AnswerId(), 'Dummy answer prompt');
        $this->question->addAnswer(new AnswerId(), 'Dummy answer prompt');
    }

    public function testAddingUpTo5AnswersToAQuestionMustWork()
    {
        $this->question->addAnswer(new AnswerId(), 'Dummy answer prompt');
        $this->question->addAnswer(new AnswerId(), 'Dummy answer prompt');
        $this->question->addAnswer(new AnswerId(), 'Dummy answer prompt');
        $this->question->addAnswer(new AnswerId(), 'Dummy answer prompt');
        $this->question->addAnswer(new AnswerId(), 'Dummy answer prompt');

        self::assertCount(5, $this->question->answers());
    }

    public function testShouldBeAbleToRemoveAnAnswer()
    {
        $answerId = new AnswerId();
        $this->question->addAnswer($answerId, 'Dummy answer prompt');
        $this->question->removeAnswerOfId($answerId);

        self::assertCount(0, $this->question->answers());
    }
}
