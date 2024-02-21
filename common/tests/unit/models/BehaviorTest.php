<?php

namespace common\unit\models;

use Yii;
use common\models\Behavior;

date_default_timezone_set('UTC');

/**
 * Time test
 */

class BehaviorTest extends \Codeception\Test\Unit
{
    public function testGetCategories()
    {
        $behaviors = Behavior::getCategories();

        expect('getCategories should return an array of 7 categories', $this->assertEquals(count($behaviors), 7));

        foreach ($behaviors as $behavior) {
            expect('this behavior to have a "name" key', $this->assertArrayHasKey('name', $behavior));
            expect('this behavior to have a "behavior_count" key', $this->assertArrayHasKey('behavior_count', $behavior));
            expect('this behavior to have a "category_id" key', $this->assertArrayHasKey('category_id', $behavior));
        }
    }

    public function testGetBehavior()
    {
        expect('getBehavior should return false when asked for an behavior that does not exist', $this->assertEquals(Behavior::getBehavior('id', 99999999), false));

        expect('getBehavior should return the asked-for behavior data', $this->assertEquals(Behavior::getBehavior('id', 3), ["id" => 3, "name" => "identifying fears and feelings", "category_id" => 1]));

        expect('getBehavior SHOULD NOT work quite right for indexing by category_id', $this->assertEquals(Behavior::getBehavior('category_id', 3), ["id" => 28, "name" => "worry", "category_id" => 3]));
    }
}
