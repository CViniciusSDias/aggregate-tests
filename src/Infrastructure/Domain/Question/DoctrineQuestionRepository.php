<?php

namespace CViniciusSDias\Aggregate\Infrastructure\Domain\Question;

use CViniciusSDias\Aggregate\Domain\Question\Question;
use CViniciusSDias\Aggregate\Domain\Question\QuestionId;
use CViniciusSDias\Aggregate\Domain\Question\QuestionRepository;
use Doctrine\ORM\EntityRepository;

class DoctrineQuestionRepository extends EntityRepository implements QuestionRepository
{
    public function save(Question $question): Question
    {
        $this->getEntityManager()->persist($question);

        return $question;
    }

    public function ofId(QuestionId $id): Question
    {
        return $this->find($id->id());
    }

    public function removeOfId(QuestionId $questionId): void
    {
        $question = $this->getEntityManager()->getReference(Question::class, $questionId->id());
        $this->getEntityManager()->remove($question);
    }
}
