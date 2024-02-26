<?php

namespace common\tests\unit\components;

use Yii;
use common\components\View;

/**
 * View test
 */

class ViewTest extends \Codeception\Test\Unit
{
    public function testRegisterJson()
    {
        $view = new View();
        $key = 'test';
        $data = [
        'name' => 'user one',
        'email' => 'userone@example.com'
      ];
        $view->registerJson($data, $key);

        expect("The supplied array should be encoded as JSON and set on the View obj", $this->assertEquals($data, json_decode($view->json[$view::POS_READY][$key], true)));

        $view = new View();
        $data = [
        'name' => 'user one',
        'email' => 'userone@example.com'
      ];
        $view->registerJson($data);

        $expected_key = md5(json_encode($data, true));
        expect("If no key is supplied it should default to the md5 of the json_encoded data", $this->assertArrayHasKey($expected_key, $view->json[$view::POS_READY]));
        expect("If no key is supplied it should default to the md5 of the json_encoded data", $this->assertEquals(json_encode($data, true), $view->json[$view::POS_READY][$expected_key]));
    }
}
