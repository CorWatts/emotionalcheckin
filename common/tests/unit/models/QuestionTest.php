<?php

namespace common\unit\models;

use Yii;
use common\models\Question;

/**
 * Time test
 */

class QuestionTest extends \Codeception\Test\Unit
{
    private $question;
    private $questionData = [
    [
      'id' => 487,
      'user_id' => 1,
      'behavior_id' => 54,
      'user_behavior_id' => 2664,
      'question' => 1,
      'answer' => 'test1',
      'date' => '2019-10-27 21:07:35',
      'category_id' => 4,
    ], [
      'id' => 488,
      'user_id' => 1,
      'behavior_id' => 54,
      'user_behavior_id' => 2664,
      'question' => 2,
      'answer' => 'test2',
      'date' => '2019-10-27 21:07:35',
      'category_id' => 4,
    ], [
      'id' => 489,
      'user_id' => 1,
      'behavior_id' => 54,
      'user_behavior_id' => 2664,
      'question' => 3,
      'answer' => 'test3',
      'date' => '2019-10-27 21:07:35',
      'category_id' => 4,
    ], [
      'id' => 490,
      'user_id' => 1,
      'behavior_id' => 132,
      'user_behavior_id' => 2665,
      'question' => 1,
      'answer' => 'test1',
      'date' => '2019-10-27 21:07:35',
      'category_id' => 7,
    ], [
      'id' => 491,
      'user_id' => 1,
      'behavior_id' => 132,
      'user_behavior_id' => 2665,
      'question' => 2,
      'answer' => 'test2',
      'date' => '2019-10-27 21:07:35',
      'category_id' => 7,
    ], [
      'id' => 492,
      'user_id' => 1,
      'behavior_id' => 132,
      'user_behavior_id' => 2665,
      'question' => 3,
      'answer' => 'test3',
      'date' => '2019-10-27 21:07:35',
      'category_id' => 7,
    ], [
      'id' => 493,
      'user_id' => 1,
      'behavior_id' => null,
      'user_behavior_id' => 2666,
      'question' => 1,
      'answer' => 'test1',
      'date' => '2019-10-27 21:07:35',
      'category_id' => 2,
    ], [
      'id' => 494,
      'user_id' => 1,
      'behavior_id' => null,
      'user_behavior_id' => 2666,
      'question' => 2,
      'answer' => 'test2',
      'date' => '2019-10-27 21:07:35',
      'category_id' => 2,
    ], [
      'id' => 495,
      'user_id' => 1,
      'behavior_id' => null,
      'user_behavior_id' => 2666,
      'question' => 3,
      'answer' => 'test3',
      'date' => '2019-10-27 21:07:35',
      'category_id' => 2,
    ],
  ];
    private $userQuestions = [
    0 => [
      'category_id' => 2,
      'category_name' => 'Forgetting Priorities',
      'question' => [
        'user_behavior_id' => 2666,
        'behavior_name' => 'some_custom_behavior',
      ],
      'answers' => [
        [
          'title' => 'How does it affect me? How do I act and feel?',
          'answer' => 'test1',
        ], [
          'title' => 'How does it affect the important people in my life?',
          'answer' => 'test2',
        ], [
          'title' => 'Why do I do this? What is the benefit for me?',
          'answer' => 'test3',
        ],
      ],
    ],
    1 => [
    'category_id' => 4,
    'category_name' => 'Speeding Up',
      'question' => [
        'user_behavior_id' => 2664,
        'behavior_name' => 'can\'t turn off thoughts',
      ],
      'answers' => [
        [
          'title' => 'How does it affect me? How do I act and feel?',
          'answer' => 'test1',
        ], [
          'title' => 'How does it affect the important people in my life?',
          'answer' => 'test2',
        ], [
          'title' => 'Why do I do this? What is the benefit for me?',
          'answer' => 'test3',
        ],
      ],
    ],
    2=> [
    'category_id' => 7,
    'category_name' => 'Relapse/Moral Failure',
      'question' => [
        'user_behavior_id' => 2665,
        'behavior_name' => 'aloneness',
      ],
      'answers' => [
        [
          'title' => 'How does it affect me? How do I act and feel?',
          'answer' => 'test1',
        ], [
          'title' => 'How does it affect the important people in my life?',
          'answer' => 'test2',
        ], [
          'title' => 'Why do I do this? What is the benefit for me?',
          'answer' => 'test3',
        ],
      ],
    ],
  ];

    public function setUp(): void
    {
        $this->question = $this->getMockBuilder('\common\models\Question')
         ->setMethods(['save', 'attributes'])
         ->getMock();
    }

    public function testParseQuestionData()
    {
        $questions = array_map(function ($d) {
            $q = $this->getMockBuilder('\common\models\Question')
                      ->setMethods(['save', 'attributes'])
                      ->getMock();
            $q->method('save')->willReturn(true);
            $q->method('attributes')
              ->willReturn([
                  'id',
                  'user_id',
                  'behavior_id',
                  'category_id',
                  'user_behavior_id',
                  'question',
                  'answer',
                  'date',
                  'userBehavior',
              ]);
            foreach ($d as $k => $v) {
                if ($k === 'behavior_id' && $v === null) {
                    $ub = new \StdClass();
                    $ub->custom_behavior = 'some_custom_behavior';
                    $q->userBehavior = $ub;
                } else {
                    $q->$k = $v;
                }
            }
            return $q;
        }, $this->questionData);

        expect('parseQuestionData should return the correct structure with expected data', $this->assertEquals($this->userQuestions, $this->question->parseQuestionData($questions)));
        expect('parseQuestionData should return empty with the empty set', $this->assertEmpty($this->question->parseQuestionData([])));
    }
}
