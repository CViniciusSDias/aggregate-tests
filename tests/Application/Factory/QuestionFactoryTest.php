<?php

namespace CViniciusSDias\Aggregate\Tests\Application\Factory;

use CViniciusSDias\Aggregate\Application\Factory\QuestionFactory;
use CViniciusSDias\Aggregate\Domain\Question\QuestionId;
use CViniciusSDias\Aggregate\Domain\Question\SelectMultiple;
use CViniciusSDias\Aggregate\Domain\Question\SelectOne;
use PHPUnit\Framework\TestCase;

class QuestionFactoryTest extends TestCase
{
    /**
     * @dataProvider questionClasses
     */
    public function testShouldCreateCorrectClasses(string $questionType, string $className)
    {
        $question = (new QuestionFactory())->createQuestion($questionType, new QuestionId(), 'Dummy prompt', false);
        self::assertInstanceOf($className, $question);
    }

    public function questionClasses()
    {
        return [
            ['select_one', SelectOne::class],
            ['select_multiple', SelectMultiple::class],
        ];
    }
}
