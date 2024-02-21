<?php

namespace site\tests\unit\models;

use Yii;
use \site\models\QuestionForm;
use \yii\di\Container;

class QuestionFormTest extends \Codeception\Test\Unit
{
    private $container;

    public function setUp(): void
    {
        $this->container = new Container;
        $this->container->set('common\interfaces\UserInterface', '\site\tests\_support\MockUser');
        $this->container->set('common\interfaces\UserBehaviorInterface', '\site\tests\_support\MockUserBehavior');
        $this->container->set('common\interfaces\QuestionInterface', '\site\tests\_support\MockQuestion');
        $this->container->set('common\interfaces\TimeInterface', function () {
            return new \common\components\Time('America/Los_Angeles');
        });
    }

    public function testAttributeLabels()
    {
        $model = $this->container->get('\site\models\QuestionForm');
        expect('attributeLabels should be correct', $this->assertEquals($model->attributeLabels(), [
            'user_behavior_id1' => 'Restoration',
            'user_behavior_id2' => 'Forgetting Priorities',
            'user_behavior_id3' => 'Anxiety',
            'user_behavior_id4' => 'Speeding Up',
            'user_behavior_id5' => 'Ticked Off',
            'user_behavior_id6' => 'Exhausted',
            'user_behavior_id7' => 'Relapsed/Moral Failure'
        ]));
    }

    public function testGetBhvrValidator()
    {
        $model = $this->container->get('\site\models\QuestionForm');
        $validator = $model->getBhvrValidator();

        expect('getBhvrValidator should return false when nothing is set on the form', $this->assertFalse($validator($model, "user_behavior_id1")));
        expect('getBhvrValidator should return false when nothing is set on the form', $this->assertFalse($validator($model, "user_behavior_id7")));

        $model->answer_1a = "processing emotions";
        expect('getBhvrValidator should return true when there is one answer set for this behavior', $this->assertTrue($validator($model, "user_behavior_id1")));

        $model->answer_1b = "also processing emotions";
        expect('getBhvrValidator should return true when there are two answers set for this behavior', $this->assertTrue($validator($model, "user_behavior_id1")));

        $model->answer_1c = "yep, processing emotions";
        expect('getBhvrValidator should return true when there are three answers set for this behavior', $this->assertTrue($validator($model, "user_behavior_id1")));

        expect('getBhvrValidator should return false when the answers that are set are NOT for this behavior', $this->assertFalse($validator($model, "user_behavior_id3")));
    }

    public function testGetPrefixProps()
    {
        $model = $this->container->get('\site\models\QuestionForm');

        expect('getPrefixProps should strip out all the falsy propeties it finds', $this->assertEmpty($model->getPrefixProps('answer')));

        $model->answer_1a = 'processing emotions';
        expect('getPrefixProps should return non-falsy properties that have the given prefix', $this->assertEquals($model->getPrefixProps('answer'), ['answer_1a' => 'processing emotions']));
    }

    public function testGetUserBehaviorIds()
    {
        $model = $this->container->get('\site\models\QuestionForm');

        $model->user_behavior_id1 = false;
        $model->user_behavior_id3 = null;
        expect('getUserBehaviorIds should strip out all the falsy propeties it finds', $this->assertEmpty($model->getUserBehaviorIds('user_behavior_id')));

        $model->user_behavior_id1 = '323';
        $model->user_behavior_id2 = 122;
        $model->user_behavior_id3 = '454';
        $model->answer_1a = 'processing emotions';
        expect('getUserBehaviorIds should return non-falsy properties that have the given prefix', $this->assertEquals($model->getUserBehaviorIds('user_behavior_id'), [323, 122, 454]));
    }

    public function testBehaviorToAnswers()
    {
        $model = $this->container->get('\site\models\QuestionForm');
        $model->answer_1a = 'answering question a';
        $model->answer_1b = 'answering question b';
        $model->answer_1c = 'answering question c';

        $model->answer_2a = 'answering question a';
        $model->answer_2b = 'answering question b';
        $model->answer_2c = 'answering question c';

        expect('behaviorToAnswers should return the answer properties related to the behavior number supplied', $this->assertEquals($model->behaviorToAnswers(1), [
            'a' => 'answering question a',
            'b' => 'answering question b',
            'c' => 'answering question c'
        ]));

        expect('behaviorToAnswers should return the the empty set when there are no answers associated with the supplied behavior number', $this->assertEmpty($model->behaviorToAnswers(7)));
    }

    public function testGetAnswers()
    {
        $model = $this->container->get('\site\models\QuestionForm');

        $model->user_behavior_id1 = 'dummy';
        $model->user_behavior_id2 = 'dummy';
        $model->user_behavior_id3 = 'dummy';
        $model->answer_1a = "processing emotions";
        $model->answer_1b = "processing emotions";
        $model->answer_1c = "processing emotions";
        $model->answer_2a = "processing emotions";
        $model->answer_2b = "processing emotions";
        $model->answer_2c = "processing emotions";
        $model->answer_3a = "processing emotions";
        $model->answer_3b = "processing emotions";
        $model->answer_3c = "processing emotions";

        expect('getAnswers should extract and coerce the data correctly', $this->assertEquals($model->getAnswers([
            $this->fakeModel(7, 280, 6),
            $this->fakeModel(13, 281, 7),
            $this->fakeModel(28, 284, 8)
        ]), [ [
            'behavior_id' => 280,
            'category_id' => 6,
            'user_bhvr_id' => 7,
            'question_id' => 1,
            'answer' => 'processing emotions',
        ], [
            'behavior_id' => 280,
            'category_id' => 6,
            'user_bhvr_id' => 7,
            'question_id' => 2,
            'answer' => 'processing emotions',
        ], [
            'behavior_id' => 280,
            'category_id' => 6,
            'user_bhvr_id' => 7,
            'question_id' => 3,
            'answer' => 'processing emotions',
        ], [
            'behavior_id' => 281,
            'category_id' => 7,
            'user_bhvr_id' => 13,
            'question_id' => 1,
            'answer' => 'processing emotions',
        ], [
            'behavior_id' => 281,
            'category_id' => 7,
            'user_bhvr_id' => 13,
            'question_id' => 2,
            'answer' => 'processing emotions',
        ], [
            'behavior_id' => 281,
            'category_id' => 7,
            'user_bhvr_id' => 13,
            'question_id' => 3,
            'answer' => 'processing emotions',
        ], [
            'behavior_id' => 284,
            'category_id' => 8,
            'user_bhvr_id' => 28,
            'question_id' => 1,
            'answer' => 'processing emotions',
        ], [
            'behavior_id' => 284,
            'category_id' => 8,
            'user_bhvr_id' => 28,
            'question_id' => 2,
            'answer' => 'processing emotions',
        ], [
            'behavior_id' => 284,
            'category_id' => 8,
            'user_bhvr_id' => 28,
            'question_id' => 3,
            'answer' => 'processing emotions',
        ]]));
    }

    public function testSaveAnswers()
    {
        $question = $this->getMockBuilder(\common\models\Question::class)
                  ->setMethods(['save', 'attributes'])
                  ->getMock();
        $question
      ->method('attributes')
      ->willReturn(['id', 'user_id', 'behavior_id', 'category_id', 'user_behavior_id', 'question', 'answer', 'date']);
        $question
      ->expects($this->exactly(6))
      ->method('save')
      ->willReturn(true);

        Yii::$container->set(\common\interfaces\QuestionInterface::class, $question);

        $model = $this->getMockBuilder(\site\models\QuestionForm::class)
                  ->setConstructorArgs([$question])
                  ->setMethods(['getAnswers'])
                  ->getMock();
        $model
      ->expects($this->once())
      ->method('getAnswers')
      ->willReturn([[
        'behavior_id' => 280,
        'category_id' => 8,
        'user_bhvr_id' => 7,
        'question_id' => 1,
        'answer' => 'processing emotions',
      ], [
        'behavior_id' => 280,
        'category_id' => 8,
        'user_bhvr_id' => 7,
        'question_id' => 2,
        'answer' => 'processing emotions',
      ], [
        'behavior_id' => 280,
        'category_id' => 8,
        'user_bhvr_id' => 7,
        'question_id' => 3,
        'answer' => 'processing emotions',
      ], [
        'behavior_id' => 281,
        'category_id' => 8,
        'user_bhvr_id' => 13,
        'question_id' => 1,
        'answer' => 'processing emotions',
      ], [
        'behavior_id' => 281,
        'category_id' => 8,
        'user_bhvr_id' => 13,
        'question_id' => 2,
        'answer' => 'processing emotions',
      ], [
        'behavior_id' => 281,
        'category_id' => 8,
        'user_bhvr_id' => 13,
        'question_id' => 3,
        'answer' => 'processing emotions',
      ]]);

        expect('saveAnswers should invoke save() the expected number of times and return true', $this->assertTrue($model->saveAnswers(123, []))); // doesn't matter what we pass in, we're mocking getAnswers()
    }

    /**
     * This test doesn't work :(
     * Phpunit doesn't like to mock static methods
     * There may be a way to do this but I don't have the patience
     * to figure it out right now.
     */
    /*
    public function testDeleteToday()
    {
      Yii::$container->set(\common\interfaces\TimeInterface::class, function () {
        return new \common\components\Time('America/Los_Angeles');
      });
      $time = Yii::$container->get(\common\interfaces\TimeInterface::class);
      list ($start, $end) = $time->getUTCBookends($time->getLocalDate());
      $question = $this->getMockBuilder(\common\models\Question::class)
                    ->setMethods(['save', 'attributes', 'deleteAll'])
                    ->getMock();
      $user_id = 5;
      $question
        ->expects($this->once())
        ->method('deleteAll')
        ->with("user_id=:user_id
        AND date > :start_date
        AND date < :end_date",
        [
          ":user_id" => 5,
          ':start_date' => $start,
          ":end_date" => $end
        ]);
      $container = new Container;
      $form = $container->get(\site\models\QuestionForm::class, [$question]);

      $form->deleteToday(5);
    }
    */

    private function fakeModel($id, $behavior_id, $category_id)
    {
        $class = new \stdClass;
        $class->id = $id;
        $class->behavior_id = $behavior_id;
        $class->category_id = $category_id;
        return $class;
    }
}
