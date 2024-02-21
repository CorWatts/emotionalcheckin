<?php

namespace common\tests\unit\components;

use Yii;
use common\components\Controller;

/**
 * Controller test
 */

class ControllerTest extends \Codeception\Test\Unit
{
    public function testActions()
    {
        $controller = new Controller('test', 'common');
        expect('actions should return an array of action settings', $this->assertEquals([
        'error' => [
          'class' => 'yii\web\ErrorAction',
        ],
        'captcha' => [
          'class' => 'yii\captcha\CaptchaAction',
        ],
      ], $controller->actions()));
    }

    public function testBeforeAction()
    {
        $controller = new Controller('test', 'common');
        $controller->enableCsrfValidation = true;

        expect("If CSRF token is valid and parent's beforeAction() returns true, return true", $this->assertTrue($controller->beforeAction('test')));

        // this is a terrible way to test this
        // look in common/config/test.php, we are setting the request class to be
        // \common\tests\_support\MockRequest
        Yii::$app->getRequest()->csrfValidationReturn = false;
        expect("If CSRF token is NOT valid and parent's beforeAction() returns true, return false", $this->assertFalse($controller->beforeAction('test')));
    }
}
