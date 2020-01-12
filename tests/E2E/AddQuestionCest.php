<?php 

class AddQuestionCest
{
    public function addNewQuestionSuccesfully(AcceptanceTester $I)
    {
        $I->amOnPage('/questions/add');

        $I->selectOption('questionType', 'select_one');
        $I->fillField('prompt', 'Dummy prompt');
        $I->fillField('answerPrompt[]', 'Dummy answer');
        $I->click('btn_create_question');

        $I->seeNumRecords(1, 'question');
        $I->seeInDatabase('question', ['prompt' => 'Dummy prompt']);
        $I->seeNumRecords(2, 'answer');
        $I->seeInDatabase('answer', ['prompt' => 'Dummy answer']);
    }
}
