<?php

namespace common\unit\models;

use Yii;

date_default_timezone_set('UTC');

/**
 * Time test
 */

class UserBehaviorTest extends \Codeception\Test\Unit
{
    public $singleSimpleBehaviorNoBehavior = [
    [
      'id' => 396,
      'user_id' => 2,
      'behavior_id' => 107,
      'category_id' => 6,
      'date' => '2016-06-17 04:12:43',
    ],
  ];

    public $badSingleSimpleBehaviorNoBehavior = [
    [
      'id' => 396,
      'user_id' => 2,
      'behavior_id' => 99999,
      'category_id' => 6,
      'date' => '2016-06-17 04:12:43',
    ],
  ];

    public $behaviorData = [
    [
      'id' => 820,
      'user_id' => 2,
      'behavior_id' => 7,
      'category_id' => 1,
      'date' => '2016-09-10 19:26:04',
      'behavior' => [
        'id' => 7,
        'name' => 'making eye contact',
        'category_id' => 1,
      ],
      'category' => [
        'id' => 1,
        'name' => 'Restoration',
      ],
    ], [
      'id' => 821,
      'user_id' => 2,
      'behavior_id' => 13,
      'category_id' => 2,
      'date' => '2016-09-10 19:26:04',
      'behavior' => [
        'id' => 13,
        'name' => 'less time/energy for God, meetings, and church',
        'category_id' => 2,
      ],
      'category' => [
        'id' => 2,
        'name' => 'Forgetting Priorities',
      ],
    ], [
      'id' => 822,
      'user_id' => 2,
      'behavior_id' => 18,
      'category_id' => 2,
      'date' => '2016-09-10 19:26:04',
      'behavior' => [
        'id' => 18,
        'name' => 'changes in goals',
        'category_id' => 2,
      ],
      'category' => [
        'id' => 2,
        'name' => 'Forgetting Priorities',
      ],
    ], [
      'id' => 823,
      'user_id' => 2,
      'behavior_id' => 29,
      'category_id' => 3,
      'date' => '2016-09-10 19:26:04',
      'behavior' => [
        'id' => 29,
        'name' => 'using profanity',
        'category_id' => 3,
      ],
      'category' => [
        'id' => 3,
        'name' => 'Anxiety',
      ],
    ], [
      'id' => 824,
      'user_id' => 2,
      'behavior_id' => 41,
      'category_id' => 3,
      'date' => '2016-09-10 19:26:04',
      'behavior' => [
        'id' => 41,
        'name' => 'co-dependent rescuing',
        'category_id' => 3,
      ],
      'category' => [
        'id' => 3,
        'name' => 'Anxiety',
      ],
    ], [
      'id' => 825,
      'user_id' => 2,
      'behavior_id' => 48,
      'category_id' => 4,
      'date' => '2016-09-10 19:26:04',
      'behavior' => [
        'id' => 48,
        'name' => 'workaholic',
        'category_id' => 4,
      ],
      'category' => [
        'id' => 4,
        'name' => 'Speeding Up',
      ],
    ], [
      'id' => 826,
      'user_id' => 2,
      'behavior_id' => 72,
      'category_id' => 5,
      'date' => '2016-09-10 19:26:04',
      'behavior' => [
        'id' => 72,
        'name' => 'black and white, all or nothing thinking',
        'category_id' => 5,
      ],
      'category' => [
        'id' => 5,
        'name' => 'Ticked Off',
      ],
    ], [
      'id' => 827,
      'user_id' => 2,
      'behavior_id' => 79,
      'category_id' => 5,
      'date' => '2016-09-10 19:26:04',
      'behavior' => [
        'id' => 79,
        'name' => 'blaming',
        'category_id' => 5,
      ],
      'category' => [
        'id' => 5,
        'name' => 'Ticked Off',
      ],
      'date' => '2016-09-10 19:26:04',
    ], [
      'id' => 828,
      'user_id' => 2,
      'behavior_id' => 89,
      'category_id' => 5,
      'date' => '2016-09-10 19:26:04',
      'behavior' => [
        'id' => 89,
        'name' => 'obsessive (stuck) thoughts',
        'category_id' => 5,
      ],
      'category' => [
        'id' => 5,
        'name' => 'Ticked Off',
      ],
    ], [
      'id' => 829,
      'user_id' => 2,
      'behavior_id' => 111,
      'category_id' => 6,
      'date' => '2016-09-10 19:26:04',
      'behavior' => [
        'id' => 111,
        'name' => 'seeking out old unhealthy people and places',
        'category_id' => 6,
      ],
      'category' => [
        'id' => 6,
        'name' => 'Exhausted',
      ],
    ], [
      'id' => 830,
      'user_id' => 2,
      'behavior_id' => null,
      'category_id' => 6,
      'date' => '2016-09-10 19:26:04',
      'custom_behavior' => 'some other custom behavior',
      'category' => [
        'id' => 6,
        'name' => 'Exhausted',
      ],
    ], [
      'id' => 831,
      'user_id' => 2,
      'behavior_id' => null,
      'category_id' => 7,
      'date' => '2016-09-10 19:26:04',
      'custom_behavior' => 'some_custom_behavior',
      'category' => [
        'id' => 7,
        'name' => 'Relapse/Moral Failure',
      ],
    ],
  ];

    public $userBehaviors = [
    1 => [
        'making eye contact' => [
          'id' => 7,
          'name' => 'making eye contact',
        ],
    ],
    2 => [
        'less time/energy for God, meetings, and church' => [
          'id' => 13,
          'name' => 'less time/energy for God, meetings, and church',
        ],
        'changes in goals' => [
          'id' => 18,
          'name' => 'changes in goals',
        ],
    ],
    3 => [
        'using profanity' => [
          'id' => 29,
          'name' => 'using profanity',
        ],
        'co-dependent rescuing' => [
          'id' => 41,
          'name' => 'co-dependent rescuing',
        ],
    ],
    4 => [
        'workaholic' => [
          'id' => 48,
          'name' => 'workaholic',
        ],
    ],
    5 => [
        'black and white, all or nothing thinking' => [
          'id' => 72,
          'name' => 'black and white, all or nothing thinking',
        ],
        'blaming' => [
          'id' => 79,
          'name' => 'blaming',
        ],
        'obsessive (stuck) thoughts' => [
          'id' => 89,
          'name' => 'obsessive (stuck) thoughts',
        ],
    ],
    6 => [
        'seeking out old unhealthy people and places' => [
          'id' => 111,
          'name' => 'seeking out old unhealthy people and places',
        ],
        'some other custom behavior' => [
          'id' => null,
          'name' => 'some other custom behavior',
        ],
    ],
    7 => [
        'some_custom_behavior' => [
          'id' => null,
          'name' => 'some_custom_behavior',
        ],
    ],
  ];


    public function setUp(): void
    {
        // pull in test data
        $data = require(__DIR__.'/../data/checkinData.php');
        $this->singleBhvr = $data['singleBhvr'];
        $this->manyBhvrs = $data['manyBhvrs'];
        $this->allBhvrs = $data['allBhvrs'];
        $this->multipleDates = $data['multipleDates'];

        $this->container = new \yii\di\Container;
        $this->container->set('common\interfaces\UserInterface', '\site\tests\_support\MockUser');
        $this->container->set('common\interfaces\QuestionInterface', '\site\tests\_support\MockQuestion');
        $this->container->set('common\interfaces\BehaviorInterface', 'common\models\Behavior');
        $this->container->set('common\interfaces\TimeInterface', function () {
            return new \common\components\Time('America/Los_Angeles');
        });

        $time = $this->container->get('common\interfaces\TimeInterface');
        $behavior = $this->container->get('common\interfaces\BehaviorInterface');
        $user = $this->container->get('common\interfaces\UserInterface');

        $this->user_behavior = $this
                            ->getMockBuilder('common\models\UserBehavior')
                            ->setConstructorArgs([$behavior, $time])
                            ->setMethods(['getIsNewRecord', 'save', 'getBehaviorsWithCounts'])
                            ->getMock();
        parent::setUp();
    }

    protected function tearDown(): void
    {
        $this->user_behavior = null;
        parent::tearDown();
    }

    public function testRules()
    {
        expect('rules', $this->assertEquals($this->user_behavior->rules(), [
      [['user_id', 'behavior_id', 'category_id', 'date'], 'required'],
      [['user_id', 'behavior_id', 'category_id'], 'integer'],
      ['custom_behavior', 'string'],
    ]));
    }

    public function testAttributeLabels()
    {
        expect('attributeLabels', $this->assertEquals($this->user_behavior->attributelabels(), [
      'id'        => 'ID',
      'date'      => 'Date',
      'user_id'   => 'User ID',
      'behavior_id' => 'Behavior ID',
      'category_id' => 'Category ID',
      'custom_behavior' => 'Personal Behavior Name',
    ]));
    }

    public function testDecorate()
    {
        expect('decorate should add Behavior data to an array of UserBehaviors', $this->assertEquals(
            $this->user_behavior->decorate($this->singleSimpleBehaviorNoBehavior),
            [['id' => 396,
                      'user_id' => 2,
                      'behavior_id' => 107,
                      'category_id' => 6,
                      'date' => '2016-06-17 04:12:43',
                      'behavior' => [
                        'id' => 107,
                        'name' => 'numb',
                        'category_id' => 6,
                      ],
                      'category' => [
                        'id' => 6,
                        'name' => 'Exhausted',
                      ]]]
        ));

        expect(
            'decorate should add Category data but no Behavior data when the category_id is valid and the behavior_id is invalid',
            $this->assertEquals(
          $this->user_behavior->decorate($this->badSingleSimpleBehaviorNoBehavior),
          [[
          'id' => 396,
          'user_id' => 2,
          'behavior_id' => 99999,
          'category_id' => 6,
          'date' => '2016-06-17 04:12:43',
          'category' => [
            'id' => 6,
            'name' => 'Exhausted',
          ],
        ]]
      )
        );
    }

    public function testParseBehaviorData()
    {
        expect('parseBehaviorData should return the correct structure with expected data', $this->assertEquals($this->user_behavior->parseBehaviorData($this->behaviorData), $this->userBehaviors));
        expect('parseBehaviorData should return empty with the empty set', $this->assertEmpty($this->user_behavior->parseBehaviorData([])));
    }

    public function testGetBehaviorsByCategory()
    {
        expect('getBehaviorsByCategory to return the empty array if the user has not logged any behaviors', $this->assertEquals([], $this->user_behavior->getBehaviorsByCategory($this->user_behavior::decorate([]))));
        expect('getBehaviorsByCategory to return the empty array if the user has not logged any behaviors', $this->assertEquals([
      1 => [
        'name' => 'Restoration',
        'count' => 5,
        'color' => '#008000',
        'highlight' => '#199919'
      ],
      2 => [
        'name' => 'Forgetting Priorities',
        'count' => 4,
        'color' => '#4CA100',
        'highlight' => '#61B219'
      ],
      4 => [
        'name' => 'Speeding Up',
        'count' => 3,
        'color' => '#E5E500',
        'highlight' => '#E5E533'
      ]
    ], $this->user_behavior->getBehaviorsByCategory($this->user_behavior::decorate([
        [
          'user_id' => 1,
          'behavior_id' => 1,
          'category_id' => 1,
          'count' => 5
        ], [
          'user_id' => 1,
          'behavior_id' => 20,
          'category_id' => 2,
          'count' => 4
        ], [
          'user_id' => 1,
          'behavior_id' => 50,
          'category_id' => 4,
          'count' => 3
        ]
      ]))));
    }

    public function testGetCheckinBreakdown()
    {
        Yii::configure(Yii::$app, [
      'components' => [
        'user' => [
          'class' => 'yii\web\User',
          'identityClass' => 'common\tests\unit\FakeUser',
        ],
      ],
    ]);
        $identity = new \common\tests\unit\FakeUser();
        $tz = 'America/Los_Angeles';
        $identity->timezone = $tz;
        // logs in the user -- we use Yii::$app->user->id in getCheckinBreakdown()
        Yii::$app->user->setIdentity($identity);

        /* get mocked Time */
        $tz = new \DateTimeZone($tz);
        $date_range = new \DatePeriod(
            new \DateTime('2019-02-03', $tz),
            new \DateInterval('P1D'),
            new \DateTime('2019-03-05', $tz)
        );
        $time = $this
        ->getMockBuilder("common\components\Time")
        ->setConstructorArgs(['America/Los_Angeles'])
        ->setMethods(['getDateTimesInPeriod'])
        ->getMock();
        $time
      ->method('getDateTimesInPeriod')
      ->willReturn($date_range);

        $behavior = new \common\models\Behavior();

        $this->user_behavior = $this
                            ->getMockBuilder("common\models\UserBehavior")
                            ->setConstructorArgs([$behavior, $time])
                            ->setMethods(['getIsNewRecord', 'save', 'getBehaviorsWithCounts'])
                            ->getMock();

        $bhvrs = require(__DIR__.'/../data/behaviorsWithCounts.php');
        $expected = require(__DIR__.'/../data/expected_getCheckinBreakdown.php');
        $this->user_behavior->method('getBehaviorsWithCounts')->willReturn(...$bhvrs);
        expect('asdf', $this->assertEquals($expected, $this->user_behavior->getCheckinBreakdown()));
    }
}
