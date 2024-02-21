<?php

namespace common\unit\models;

use Yii;
use Codeception\Stub;
use common\models\User;
use common\models\LoginForm;

date_default_timezone_set('UTC');

/**
 * Login test
 */

class LoginFormTest extends \Codeception\Test\Unit
{
    private function mockUser($mocks = [])
    {
        $defaultMocks = [
      'validatePassword' => true,
      'isVerified' => true,
      'login' => 'LOGGING IN',
      'save' => true
    ];

        $user = $this->getMockBuilder('common\models\User')
      ->disableOriginalConstructor()
      ->setMethods(['save', 'login', 'attributes', 'validatePassword', 'isVerified', 'findByEmail'])
      ->getMock();

        $mocks = \yii\helpers\ArrayHelper::merge($defaultMocks, $mocks);
        foreach ($mocks as $name => $val) {
            $user->method($name)->willReturn($val);
        }
        $user->method('findByEmail')->will($this->returnSelf());
        $user->method('attributes')->willReturn([
      'id',
      'password_hash',
      'password_reset_token',
      'verify_email_token',
      'email',
      'auth_key',
      'role',
      'status',
      'created_at',
      'updated_at',
      'password',
      'timezone',
      'email_threshold',
      'partner_email1',
      'partner_email2',
      'partner_email3',
    ]);

        return $user;
    }

    private function mockForm($user)
    {
        $form = Stub::make('\common\models\LoginForm', [
      'user' => $user,
    ]);
        return $form;
    }

    public function setUp(): void
    {
        $this->container = new \yii\di\Container;
        $this->user = $this->mockUser();
        $this->form = $this->mockForm($this->user);
        parent::setUp();

        Yii::configure(Yii::$app, [
      'components' => [
        'user' => [
          'class' => '\common\tests\unit\FakeWebUser',
          'identityClass' => '\common\tests\unit\FakeUser',
        ],
      ],
    ]);

        $identity = new \common\tests\unit\FakeUser();
        $identity->timezone = "America/Los_Angeles";
    }

    public function testRules()
    {
        $vals = [
      'email' => 'hunter2@hunter2.com',
      'password' => 'hunter2',
      'remeberMe' => true,
    ];
        $form = $this->container->get('\common\models\LoginForm', [$this->user]);

        $form->attributes = $vals; // massive assignment
        expect('the form should pass validation with good values', $this->assertTrue($form->validate()));

        $form->attributes = $vals; // massive assignment
        $form->email = 'notanemail';
        expect('the form should fail validation with a bad email', $this->assertFalse($form->validate()));

        $form->attributes = $vals; // massive assignment
        $form->email = '    whItESPAce@EMAIL.com    ';
        expect('the form should trim off whitespace and lowercase an email address', $this->assertTrue($form->validate()));
        expect('the emails to match now', $this->assertEquals('whitespace@email.com', $form->email));
    }

    public function testGetUser()
    {
        expect('getUser should return the user if the user attr on the form object is already set', $this->assertInstanceOf('\common\models\User', $this->form->getUser()));
    }

    public function testValidatePassword()
    {
        expect('validatePassword should always return null', $this->assertNull($this->form->validatePassword()));
        expect('validatePassword should by default in this test class not set errors', $this->assertEmpty($this->form->getErrors()));

        $this->user = $this->mockUser(['validatePassword' => false]);
        $this->form = $this->mockForm($this->user);
        $this->form->validatePassword();
        expect('validatePassword should set errors on the model if something goes wrong', $this->assertNotEmpty($this->form->getErrors('password')));
    }
    public function testLogin()
    {
        $this->form->email = 'hunter2@hunter2.com';
        $this->form->password = 'hunter2';
        expect('login should return true if able to log in user', $this->assertTrue($this->form->login()));

        $this->user = $this->mockUser(['isVerified' => false]);
        $this->form = $this->mockForm($this->user);
        $this->form->email = 'hunter2@hunter2.com';
        $this->form->password = 'hunter2';

        expect('login should return false if account is not verified', $this->assertFalse($this->form->login()));
        expect('login should set a flash message if account is not verified', $this->assertNotEmpty(Yii::$app->session->getFlash('warning', null, true)));

        $this->user = $this->mockUser(['validatePassword' => false]);
        $this->form = $this->mockForm($this->user);
        $this->form->email = 'hunter2@hunter2.com';
        $this->form->password = 'hunter2';
        expect('login should return false credentials are bad', $this->assertFalse($this->form->login()));
        expect('login should NOT set a flash message if credentials are bad', $this->assertEmpty(Yii::$app->session->getFlash('warning', null, true)));
    }
}
