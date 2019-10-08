<?php

namespace CViniciusSDias\Aggregate\Infrastructure\Question;

use CViniciusSDias\Aggregate\Domain\Question\Question;
use CViniciusSDias\Aggregate\Domain\Question\QuestionId;
use CViniciusSDias\Aggregate\Domain\Question\QuestionRepository;
use Doctrine\ORM\EntityRepository;

class DoctrineQuestionRepository extends EntityRepository implements QuestionRepository
{

    public function save(Question $question): Question
    {
        $this->getEntityManager()->persist($question);
        $this->getEntityManager()->flush();

        return $question;
    }

    public function ofId(QuestionId $id): Question
    {
        /** @var Question $question */
        $question = $this->find($id->id());
        return $question;
    }
}
