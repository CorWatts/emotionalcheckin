<?php

namespace site\tests\unit\models;

use Yii;
use yii\helpers\ArrayHelper as AH;

class CheckinFormTest extends \Codeception\Test\Unit
{
    public $behaviors = [
      1 => [
        'making eye contact' => [
          'id' => 7,
          'name' => 'making eye contact',
        ],
      ],
      2 => [
        'bored' => [
          'id' => 12,
          'name' => 'bored',
        ],
        'less time/energy for God, meetings, and church' => [
          'id' => 13,
          'name' => 'less time/energy for God, meetings, and church',
        ],
        'isolating yourself' => [
          'id' => 17,
          'name' => 'isolating yourself',
        ],
        'changes in goals' => [
          'id' => 18,
          'name' => 'changes in goals',
        ],
      ],
      3 => [
        'worry' => [
          'id' => 28,
          'name' => 'worry',
        ],
        'fantasy' => [
          'id' => 38,
          'name' => 'fantasy',
        ],
        'using over-the-counter medication for pain, sleep, and weight control' => [
          'id' => 46,
          'name' => 'using over-the-counter medication for pain, sleep, and weight control',
        ],
      ],
      4 => [
        'super busy' => [
          'id' => 47,
          'name' => 'super busy',
        ],
        'binge eating (usually at night]' => [
          'id' => 56,
          'name' => 'binge eating (usually at night]',
        ],
        'dramatic mood swings' => [
          'id' => 62,
          'name' => 'dramatic mood swings',
        ],
      ],
      5 => [
        'increased isolation' => [
          'id' => 78,
          'name' => 'increased isolation',
        ],
        'blaming' => [
          'id' => 79,
          'name' => 'blaming',
        ],
      ],
      6 => [
        'pessimistic' => [
          'id' => 104,
          'name' => 'pessimistic',
        ],
      ],
      7 => [
        'feeling you just can\'t manage without your coping behavior, at least for now' => [
          'id' => 128,
          'name' => 'feeling you just can\'t manage without your coping behavior, at least for now',
        ],
      ],
    ];

    /* fake custom behaviors */
    public $custom_behaviors = [
      1 => [
        'making eye contact' => [
          'id' => '777-custom',
          'name' => 'making eye contact',
        ],
      ],
      2 => [
        'bored' => [
          'id' => '12333-custom',
          'name' => 'bored',
        ],
        'less time/energy for God, meetings, and church' => [
          'id' => '11122333-custom',
          'name' => 'less time/energy for God, meetings, and church',
        ],
        'isolating yourself' => [
          'id' => '17777-custom',
          'name' => 'isolating yourself',
        ],
        'changes in goals' => [
          'id' => '11888-custom',
          'name' => 'changes in goals',
        ],
      ],
    ];

    protected function setUp(): void
    {
        $this->container = new \yii\di\Container;
        $this->container->set('common\interfaces\UserBehaviorInterface', '\site\tests\_support\MockUserBehavior');
        $this->container->set('common\interfaces\TimeInterface', function () {
            return new \common\components\Time('America/Los_Angeles');
        });
        parent::setUp();
    }

    public function testAttributeLabels()
    {
        $model = $this->container->get('\site\models\CheckinForm');
        expect('attributeLabels should be correct', $this->assertEquals([
            'behaviors1' => 'Restoration',
            'behaviors2' => 'Forgetting Priorities',
            'behaviors3' => 'Anxiety',
            'behaviors4' => 'Speeding Up',
            'behaviors5' => 'Ticked Off',
            'behaviors6' => 'Exhausted',
            'behaviors7' => 'Relapsed/Moral Failure'
        ], $model->attributeLabels()));
    }

    public function testSetBehaviors()
    {
        $model = $this->container->get('\site\models\CheckinForm');

        $model->setBehaviors($this->behaviors);
        expect('behaviors1 should be correct', $this->assertEquals([ 0 => 7 ], $model->behaviors1));
        expect('behaviors2 should be correct', $this->assertEquals([ 0 => 12, 1 => 13, 2 => 17, 3 => 18 ], $model->behaviors2));
        expect('behaviors3 should be correct', $this->assertEquals([ 0 => 28, 1 => 38, 2 => 46 ], $model->behaviors3));
        expect('behaviors4 should be correct', $this->assertEquals([ 0 => 47, 1 => 56, 2 => 62 ], $model->behaviors4));
        expect('behaviors5 should be correct', $this->assertEquals([ 0 => 78, 1 => 79 ], $model->behaviors5));
        expect('behaviors6 should be correct', $this->assertEquals([ 0 => 104 ], $model->behaviors6));
        expect('behaviors7 should be correct', $this->assertEquals([ 0 => 128 ], $model->behaviors7));

        $model->setBehaviors($this->behaviors);
        expect('setBehaviors should not append behaviors to existing ones', $this->assertEquals([ 0 => 7 ], $model->behaviors1));
        expect('setBehaviors should not append behaviors to existing ones', $this->assertEquals([ 0 => 12, 1 => 13, 2 => 17, 3 => 18 ], $model->behaviors2));
        expect('setBehaviors should not append behaviors to existing ones', $this->assertEquals([ 0 => 28, 1 => 38, 2 => 46 ], $model->behaviors3));
        expect('setBehaviors should not append behaviors to existing ones', $this->assertEquals([ 0 => 47, 1 => 56, 2 => 62 ], $model->behaviors4));
        expect('setBehaviors should not append behaviors to existing ones', $this->assertEquals([ 0 => 78, 1 => 79 ], $model->behaviors5));
        expect('setBehaviors should not append behaviors to existing ones', $this->assertEquals([ 0 => 104 ], $model->behaviors6));
        expect('setBehaviors should not append behaviors to existing ones', $this->assertEquals([ 0 => 128 ], $model->behaviors7));
    }

    public function testValidateBehaviors()
    {
        $model = $this->container->get('\site\models\CheckinForm');
        $model->setBehaviors($this->behaviors);
        expect('validation should be good', $this->assertTrue($model->validate()));

        $model->behaviors1[0] = 'bad';
        expect('validation should be bad', $this->assertFalse($model->validate()));
    }

    public function testCompileBehaviors()
    {
        $model = $this->container->get('\site\models\CheckinForm');
        $model->setBehaviors($this->behaviors);
        expect('compiling behaviors should return a boolean true', $this->assertTrue($model->compileBehaviors()));
        expect('compiling behaviors should set the correct array', $this->assertEquals([
                      7, 12, 13, 17, 18, 28, 38, 46, 47, 56, 62, 78, 79, 104, 128,
                    ], $model->compiled_behaviors));

        $model = $this->container->get('\site\models\CheckinForm');
        $model->setBehaviors($this->behaviors);
        $model->behaviors1[0] = null;
        $model->behaviors2[0] = null;
        $model->behaviors3[0] = null;
        $model->compileBehaviors();
        expect('compiling behaviors should strip out any falsy values', $this->assertEquals([
                                                13, 17, 18, 38, 46, 47, 56, 62, 78, 79, 104, 128,
                                              ], $model->compiled_behaviors));

        $model = $this->container->get('\site\models\CheckinForm');
        $model->setBehaviors([]);
        expect('compiling behaviors should return an empty array when no behaviors are set', $this->assertEmpty($model->compileBehaviors()));
        expect('compiling behaviors should not set the compiled_behaviors array to anything when no behaviors are set', $this->assertEmpty($model->compiled_behaviors));

        $model = $this->container->get('\site\models\CheckinForm');
        $model->setBehaviors($this->custom_behaviors);
        $model->compileBehaviors();
        expect('compiling behaviors should identify and set custom behaviors', $this->assertEquals(["777-custom", "12333-custom", "11122333-custom", "17777-custom", "11888-custom"], $model->custom_behaviors));
    }

    public function testGetCustomBehaviors()
    {
        $model = $this->container->get('\site\models\CheckinForm');
        $model->custom_behaviors = ["777-custom", "12333-custom", "11122333-custom", "17777-custom", "11888-custom"];
        expect('to return an array of extracted ids from an array of custom_behaviors', $this->assertEquals([777, 12333, 11122333, 17777, 11888], $model->getCustomBehaviors()));

        $model = $this->container->get('\site\models\CheckinForm');
        $model->custom_behaviors = [];
        $model->compiled_behaviors = [ 1, 2, 3];
        expect('to return an empty array if no custom behaviors are set', $this->assertEquals([], $model->getCustomBehaviors()));
    }

    public function testMergeWithDefault()
    {
        $model = $this->container->get('\site\models\CheckinForm');

        $base_behaviors = AH::index(Yii::$container->get(\common\models\Behavior::class)::$behaviors, 'name', "category_id");
        expect('mergeWithDefault should return the base behaviors when given an empty array', $this->assertEquals($base_behaviors, $model->mergeWithDefault([])));

        $other_behaviors = [
        'some kind of really bad making eye contact' => [
          'id' => 400,
          'name' => 'some kind of really bad making eye contact',
          'category_id' => 7
        ]
      ];
        $expected_behaviors = $base_behaviors;
        $expected_behaviors[7] += $other_behaviors;

        expect('mergeWithDefault should return the given behaviors merged in with the base behaviors', $this->assertEquals($base_behaviors, $model->mergeWithDefault($other_behaviors)));
    }
}
