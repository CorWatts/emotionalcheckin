<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper as AH;
use \common\interfaces\BehaviorInterface;

/**
 * @property integer $id
 * @property string $name
 * @property integer $category_id
 */
class Behavior extends \yii\base\BaseObject implements BehaviorInterface
{
    public static $behaviors = [
    ['id' => 1,   'name' => 'no current secrets', 'category_id' => 1],
    ['id' => 2,   'name' => 'resolving problems', 'category_id' => 1],
    ['id' => 3,   'name' => 'identifying fears and feelings', 'category_id' => 1],
    ['id' => 4,   'name' => 'keeping commitments to meetings, prayer, family, church, people, goals, and self', 'category_id' => 1],
    ['id' => 5,   'name' => 'being open', 'category_id' => 1],
    ['id' => 6,   'name' => 'being honest', 'category_id' => 1],
    ['id' => 7,   'name' => 'making eye contact', 'category_id' => 1],
    ['id' => 8,   'name' => 'reaching out to others', 'category_id' => 1],
    ['id' => 9,   'name' => 'increasing in relationships with God and others', 'category_id' => 1],
    ['id' => 10,  'name' => 'accountability', 'category_id' => 1],
    ['id' => 11,  'name' => 'secrets', 'category_id' => 2],
    ['id' => 12,  'name' => 'bored', 'category_id' => 2],
    ['id' => 13,  'name' => 'less time/energy for God, meetings, and church', 'category_id' => 2],
    ['id' => 14,  'name' => 'avoiding support and accountability towards people', 'category_id' => 2],
    ['id' => 15,  'name' => 'superficial conversations', 'category_id' => 2],
    ['id' => 16,  'name' => 'sarcasm', 'category_id' => 2],
    ['id' => 17,  'name' => 'isolating yourself', 'category_id' => 2],
    ['id' => 18,  'name' => 'changes in goals', 'category_id' => 2],
    ['id' => 19,  'name' => 'flirting', 'category_id' => 2],
    ['id' => 20,  'name' => 'obsessed with relationships', 'category_id' => 2],
    ['id' => 21,  'name' => 'breaking promises/commitments', 'category_id' => 2],
    ['id' => 22,  'name' => 'neglecting family', 'category_id' => 2],
    ['id' => 23,  'name' => 'preoccupation with material things, television, or entertainment', 'category_id' => 2],
    ['id' => 24,  'name' => 'procrastination', 'category_id' => 2],
    ['id' => 25,  'name' => 'lying', 'category_id' => 2],
    ['id' => 26,  'name' => 'over-confidence', 'category_id' => 2],
    ['id' => 27,  'name' => 'hiding money', 'category_id' => 2],
    ['id' => 28,  'name' => 'worry', 'category_id' => 3],
    ['id' => 29,  'name' => 'using profanity', 'category_id' => 3],
    ['id' => 30,  'name' => 'being fearful', 'category_id' => 3],
    ['id' => 31,  'name' => 'being resentful', 'category_id' => 3],
    ['id' => 32,  'name' => 'replaying old, negative thoughts', 'category_id' => 3],
    ['id' => 33,  'name' => 'perfectionism', 'category_id' => 3],
    ['id' => 34,  'name' => 'judging others\' motives', 'category_id' => 3],
    ['id' => 35,  'name' => 'making goals and lists you can\'t complete', 'category_id' => 3],
    ['id' => 36,  'name' => 'poor planning', 'category_id' => 3],
    ['id' => 37,  'name' => 'mind reading', 'category_id' => 3],
    ['id' => 38,  'name' => 'fantasy', 'category_id' => 3],
    ['id' => 41,  'name' => 'co-dependent rescuing', 'category_id' => 3],
    ['id' => 42,  'name' => 'sleep problems', 'category_id' => 3],
    ['id' => 43,  'name' => 'trouble concentrating', 'category_id' => 3],
    ['id' => 44,  'name' => 'seeking/creating drama', 'category_id' => 3],
    ['id' => 45,  'name' => 'gossip', 'category_id' => 3],
    ['id' => 46,  'name' => 'using over-the-counter medication for pain, sleep, and weight control', 'category_id' => 3],
    ['id' => 47,  'name' => 'super busy', 'category_id' => 4],
    ['id' => 48,  'name' => 'workaholic', 'category_id' => 4],
    ['id' => 49,  'name' => 'can\'t relax', 'category_id' => 4],
    ['id' => 50,  'name' => 'driving too fast', 'category_id' => 4],
    ['id' => 51,  'name' => 'avoiding slowing down', 'category_id' => 4],
    ['id' => 52,  'name' => 'feeling driven', 'category_id' => 4],
    ['id' => 53,  'name' => 'in a hurry', 'category_id' => 4],
    ['id' => 54,  'name' => 'can\'t turn off thoughts', 'category_id' => 4],
    ['id' => 55,  'name' => 'skipping meals', 'category_id' => 4],
    ['id' => 56,  'name' => 'binge eating (usually at night)', 'category_id' => 4],
    ['id' => 57,  'name' => 'overspending', 'category_id' => 4],
    ['id' => 58,  'name' => 'can\'t identify own feelings/needs', 'category_id' => 4],
    ['id' => 59,  'name' => 'repetitive, negative thoughts', 'category_id' => 4],
    ['id' => 60,  'name' => 'irritable', 'category_id' => 4],
    ['id' => 61,  'name' => 'making excuses for "having to do it all"', 'category_id' => 4],
    ['id' => 62,  'name' => 'dramatic mood swings', 'category_id' => 4],
    ['id' => 63,  'name' => 'lust', 'category_id' => 4],
    ['id' => 64,  'name' => 'too much caffeine', 'category_id' => 4],
    ['id' => 65,  'name' => 'over exercising', 'category_id' => 4],
    ['id' => 66,  'name' => 'nervousness', 'category_id' => 4],
    ['id' => 67,  'name' => 'difficulty being alone or with people', 'category_id' => 4],
    ['id' => 68,  'name' => 'difficulty listening to others', 'category_id' => 4],
    ['id' => 69,  'name' => 'avoiding support', 'category_id' => 4],
    ['id' => 70,  'name' => 'procrastination causing crises in money, work, or relationships', 'category_id' => 5],
    ['id' => 71,  'name' => 'sarcasm', 'category_id' => 5],
    ['id' => 72,  'name' => 'black and white, all or nothing thinking', 'category_id' => 5],
    ['id' => 73,  'name' => 'feeling that no one understands', 'category_id' => 5],
    ['id' => 74,  'name' => 'overreacting', 'category_id' => 5],
    ['id' => 75,  'name' => 'road rage', 'category_id' => 5],
    ['id' => 76,  'name' => 'constant resentments', 'category_id' => 5],
    ['id' => 77,  'name' => 'pushing others away', 'category_id' => 5],
    ['id' => 78,  'name' => 'increased isolation', 'category_id' => 5],
    ['id' => 79,  'name' => 'blaming', 'category_id' => 5],
    ['id' => 80,  'name' => 'self pity', 'category_id' => 5],
    ['id' => 81,  'name' => 'arguing', 'category_id' => 5],
    ['id' => 82,  'name' => 'irrationality', 'category_id' => 5],
    ['id' => 83,  'name' => 'can\'t handle criticism', 'category_id' => 5],
    ['id' => 84,  'name' => 'defensive', 'category_id' => 5],
    ['id' => 85,  'name' => 'people are avoiding you', 'category_id' => 5],
    ['id' => 86,  'name' => 'having to be right', 'category_id' => 5],
    ['id' => 87,  'name' => 'digestive problems', 'category_id' => 5],
    ['id' => 88,  'name' => 'headaches', 'category_id' => 5],
    ['id' => 89,  'name' => 'obsessive (stuck) thoughts', 'category_id' => 5],
    ['id' => 90,  'name' => 'can\'t forgive', 'category_id' => 5],
    ['id' => 91,  'name' => 'feeling grandiose (superior)', 'category_id' => 5],
    ['id' => 92,  'name' => 'intimidation', 'category_id' => 5],
    ['id' => 93,  'name' => 'feeling aggressive', 'category_id' => 5],
    ['id' => 94,  'name' => 'depressed', 'category_id' => 6],
    ['id' => 95,  'name' => 'panicked', 'category_id' => 6],
    ['id' => 96,  'name' => 'confused', 'category_id' => 6],
    ['id' => 97,  'name' => 'hopeless', 'category_id' => 6],
    ['id' => 98,  'name' => 'sleeping too much or too little', 'category_id' => 6],
    ['id' => 99,  'name' => 'can\'t cope', 'category_id' => 6],
    ['id' => 100, 'name' => 'overwhelmed', 'category_id' => 6],
    ['id' => 101, 'name' => 'crying for "no reason"', 'category_id' => 6],
    ['id' => 102, 'name' => 'can\'t think', 'category_id' => 6],
    ['id' => 103, 'name' => 'forgetful', 'category_id' => 6],
    ['id' => 104, 'name' => 'pessimistic', 'category_id' => 6],
    ['id' => 105, 'name' => 'helpless', 'category_id' => 6],
    ['id' => 106, 'name' => 'tired', 'category_id' => 6],
    ['id' => 107, 'name' => 'numb', 'category_id' => 6],
    ['id' => 108, 'name' => 'wanting to run', 'category_id' => 6],
    ['id' => 109, 'name' => 'constant cravings for old coping behaviors', 'category_id' => 6],
    ['id' => 110, 'name' => 'thinking of using porn, sex, drugs, or alcohol', 'category_id' => 6],
    ['id' => 111, 'name' => 'seeking out old unhealthy people and places', 'category_id' => 6],
    ['id' => 112, 'name' => 'really isolated', 'category_id' => 6],
    ['id' => 113, 'name' => 'people are angry with you', 'category_id' => 6],
    ['id' => 114, 'name' => 'self-abuse', 'category_id' => 6],
    ['id' => 115, 'name' => 'suicidal thoughts', 'category_id' => 6],
    ['id' => 116, 'name' => 'no goals', 'category_id' => 6],
    ['id' => 117, 'name' => 'survival mode', 'category_id' => 6],
    ['id' => 118, 'name' => 'not returning phone calls', 'category_id' => 6],
    ['id' => 119, 'name' => 'missing work', 'category_id' => 6],
    ['id' => 120, 'name' => 'irritability', 'category_id' => 6],
    ['id' => 121, 'name' => 'loss of appetite', 'category_id' => 6],
    ['id' => 122, 'name' => 'returning to the place you swore you would never go again', 'category_id' => 7],
    ['id' => 123, 'name' => 'giving up', 'category_id' => 7],
    ['id' => 124, 'name' => 'giving in', 'category_id' => 7],
    ['id' => 125, 'name' => 'out of control', 'category_id' => 7],
    ['id' => 126, 'name' => 'lost in your addiction', 'category_id' => 7],
    ['id' => 127, 'name' => 'lying to yourself and others', 'category_id' => 7],
    ['id' => 128, 'name' => 'feeling you just can\'t manage without your coping behavior, at least for now', 'category_id' => 7],
    ['id' => 129, 'name' => 'shame', 'category_id' => 7],
    ['id' => 130, 'name' => 'condemnation', 'category_id' => 7],
    ['id' => 131, 'name' => 'guilt', 'category_id' => 7],
    ['id' => 132, 'name' => 'aloneness', 'category_id' => 7],
  ];

    /**
     * @inheritdoc
     * @codeCoverageIgnore
     */
    public function rules()
    {
        return [
      [['name', 'category_id'], 'required'],
      [['name'], 'string'],
      [['category_id'], 'integer']
    ];
    }

    /**
     * @inheritdoc
     * @codeCoverageIgnore
     */
    public function attributeLabels()
    {
        return [
      'id'          => 'ID',
      'name'        => 'Name',
      'category_id' => 'Category ID',
    ];
    }

    /**
     * This grabs all the categories from Category and adds the count of
     * behaviors in each category, it also renames the "id" field to be
     * "category_id".
     *
     * @returns Array
     */
    public static function getCategories()
    {
        $bhvrs_by_cat = AH::index(self::$behaviors, null, 'category_id');
        $cats = AH::index(\common\models\Category::$categories, "id");
        foreach ($cats as $id => &$cat) {
            $cat['behavior_count'] = count($bhvrs_by_cat[$id]); // add count of behaviors
      $cat['category_id'] = $cat['id']; // rename id to category_id
      unset($cat['id']);
        }
        return $cats;
    }

    /**
     * Given a $key => $value pair, returns the matching behavior.
     * Example:
     *     getBehavior('id', 1);
     * Should return:
     *     ['id' => 1,   'name' => 'no current secrets', 'category_id' => 1]
     *
     * @param string $key the name of the attribute to filter on
     * @param string $val the value of the attribute to filter on
     * @return a single behavior
     */
    public static function getBehavior($key, $val)
    {
        $ret = array_values(array_filter(self::$behaviors, function ($bvr) use ($key, $val) {
            return $bvr[$key] === $val;
        }));
        return $ret ? $ret[0] : null;
    }
}
