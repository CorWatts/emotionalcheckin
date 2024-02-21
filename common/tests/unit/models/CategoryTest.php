<?php

namespace common\unit\models;

use Yii;
use common\models\Category;

date_default_timezone_set('UTC');

/**
 * Time test
 */

class CategoryTest extends \Codeception\Test\Unit
{
    public function testRules()
    {
        $category = new Category;
        expect('rules', $this->assertEquals($category->rules(), [
      ['name', 'required'],
      ['name', 'string', 'max' => 255],
    ]));
    }

    public function testAttributeLabels()
    {
        $category = new Category;
        expect('attributeLabels', $this->assertEquals($category->attributeLabels(), [
      'id' => 'ID',
      'name' => 'Name',
    ]));
    }

    public function testGetCategories()
    {
        expect('getCategories', $this->assertEquals(Category::getCategories(), [
      1 => 'Restoration',
      2 => 'Forgetting Priorities',
      3 => 'Anxiety',
      4 => 'Speeding Up',
      5 => 'Ticked Off',
      6 => 'Exhausted',
      7 => 'Relapse/Moral Failure',
    ]));
    }

    public function testGetCategory()
    {
        expect('getCategory should return a valid category', $this->assertEquals(Category::getCategory('id', 3), ["id" => 3, "name" => "Anxiety"]));
        expect('getCategory should return null if the specified category is not found', $this->assertNull(Category::getCategory('id', 77)));
    }
}
