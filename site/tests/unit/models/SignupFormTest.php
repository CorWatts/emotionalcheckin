<?php

namespace site\tests\unit\models;

use Yii;

class SignupFormTest extends \Codeception\Test\Unit
{
    private $vals = [
    'password'        => 'password',
    'email'           => 'fake@email.com',
    'timezone'        => "America/Los_Angeles",
    'send_email'      => true,
    'partner_email1'  => 'partner1@email.com',
    'partner_email2'  => 'partner2@email.com',
    'partner_email3'  => 'partner3@email.com',
  ];

    private $whitelist = [
    'email',
    'timezone',
    'send_email',
    'partner_email1',
    'partner_email2',
    'partner_email3'
  ];

    public function setUp(): void
    {
        // define interfaces for injection
        $this->container = new \yii\di\Container;
        $this->container->set('common\interfaces\UserInterface', '\site\tests\_support\MockUser');
        $this->container->set('common\interfaces\UserBehaviorInterface', '\site\tests\_support\MockUserBehavior');
        $this->container->set('common\interfaces\TimeInterface', function () {
            return new \common\components\Time('America/Los_Angeles');
        });

        // instantiate mock objects
        $user_behavior = $this->container->get(\common\interfaces\UserBehaviorInterface::class);
        $time        = $this->container->get(\common\interfaces\TimeInterface::class);

        $question = $this->getMockBuilder(\common\models\Question::class)
      ->setMethods(['save', 'attributes'])
      ->getMock();

        $user_methods = ['save', 'attributes', 'findByEmail', 'sendSignupNotificationEmail', 'sendVerifyEmail'];
        $this->user = $this->getMockBuilder(\common\models\User::class)
      ->setConstructorArgs([$user_behavior, $time])
      ->setMethods($user_methods)
      ->getMock();
        $user_attrs = [
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
      'send_email',
      'email_category',
      'partner_email1',
      'partner_email2',
      'partner_email3',
    ];
        $this->user->method('attributes')->willReturn($user_attrs);

        $this->existing_user = $this->getMockBuilder(\common\models\User::class)
      ->setConstructorArgs([$user_behavior, $time])
      ->setMethods($user_methods)
      ->getMock();
        $this->existing_user->method('attributes')->willReturn($user_attrs);

        $this->existing_user->id          = 1;
        $this->existing_user->email       = "realuser@fsa.com";
        $this->existing_user->setIsNewRecord(false);

        parent::setUp();
    }

    protected function tearDown(): void
    {
        $this->user = null;
        parent::tearDown();
    }

    public function testRules()
    {
        $form = $this->container->get(\site\models\SignupForm::class, [$this->user]);

        $form->attributes = $this->vals; // massive assignment
        expect('the form should pass validation with good values', $this->assertTrue($form->validate()));

        $form->attributes     = $this->vals; // massive assignment
        $form->partner_email1 = null;
        expect('the form should fail validation when send_email is true but no partner_emails are provided', $this->assertFalse($form->validate()));

        $form->attributes = $this->vals; // massive assignment
        $form->timezone = 'FakePlace/FakeCity';
        expect('the form should fail validation when a bad timezone is provided', $this->assertFalse($form->validate()));
    }

    public function testSignupNewUser()
    {

    // set up a side effect to occur when the user's save() function is called
        $saved = false;
        $this->user->method('save')->will($this->returnCallback(function ($runValidation=true, $attributeNames=null) use (&$saved) {
            $saved = true;
            return $this->user;
        }));

        $form = $this->getForm();

        /////////////////////////////////////////////////////////////////////

        // a brand-new user should be able to sign up
        expect('the form should validate correctly', $this->assertTrue($form->validate()));
        $user = $form->signup();
        expect(
            'signup() should return a newly-saved user when the user does not already exist with the submitted values',
            $this->assertEquals($user->getAttributes($this->whitelist), array_splice($this->vals, 1))
        );
        expect(
            'the "Remember Me" auth_key value should be set to a random string (defaults to a 32 char length)',
            $this->assertEquals(32, strlen($user->auth_key))
        );
        expect(
            'password_hash to be set to something like a hash',
            $this->assertStringStartsWith('$2y$13$', $user->password_hash)
        );
        expect(
            'when the user is created, signup() should call save()',
            $this->assertTrue($saved)
        );
        expect('This user should be an isNewRecord -- they should NOT already exist', $this->assertTrue($user->getIsNewRecord()));
    }
 
    public function testSignupExistingConfirmedUser()
    {
        $saved = false;
        $this->existing_user->method('save')->will($this->returnCallback(function ($runValidation=true, $attributeNames=null) use (&$saved) {
            $saved = true;
            return $this->existing_user;
        }));

        $this->user->method('findByEmail')->willReturn($this->existing_user);
        $this->existing_user->verify_email_token = $this->getValidToken() . "_confirmed";
        $form2 = $this->getForm();

        // an existing and confirmed user should not be signed up
        expect('the form should validate correctly', $this->assertTrue($form2->validate()));
        $user = $form2->signup();
        expect('when the user exists and is confirmed, signup() should return null', $this->assertNull($user));
        expect('when the user exists and is confirmed, signup() should NOT call save()', $this->assertFalse($saved));
    }

    public function testSignupExistingUnconfirmedUser()
    {
        $saved = false;
        $this->existing_user->method('save')->will($this->returnCallback(function ($runValidation=true, $attributeNames=null) use (&$saved) {
            $saved = true;
            return $this->existing_user;
        }));
 
        $this->existing_user->verify_email_token = $this->getExpiredToken();
        // make sure our fake token actually _is_ expired
        expect('token is not confirmed', $this->assertFalse($this->existing_user->isTokenConfirmed()));
        expect('token is expired', $this->assertFalse($this->existing_user->isTokenCurrent($this->existing_user->verify_email_token, 'user.verifyAccountTokenExpire')));

        $this->user->method('findByEmail')->willReturn($this->existing_user);
        $form3 = $this->getForm();

        // an existing and unconfirmed user should be able to sign up again, but their information will be UPDATED
        expect('the form should validate correctly', $this->assertTrue($form3->validate()));
        $user = $form3->signup();
        expect('This user should not be an isNewRecord -- they should already exist', $this->assertFalse($user->getIsNewRecord()));
        expect(
            'signup() should return a newly-saved user when the user does not already exist with the submitted values',
            $this->assertEquals($user->getAttributes($this->whitelist), array_splice($this->vals, 1))
        );
        expect('when the user exists and is NOT confirmed, signup() should call save() to UPDATE their info', $this->assertTrue($saved));
    }

    public function testSetFields()
    {
        $container = new yii\di\Container;
        $form = $container->get('\site\models\SignupForm', [$this->user]);

        $form = $this->setAttrs($form, $this->vals);

        $result = $form->setFields($this->user);
        // populate should set the values of the user model from the form
        foreach ($this->whitelist as $attr) {
            $this->assertEquals($this->vals[$attr], $result->$attr);
        }
    }

    private function setAttrs($form, array $vals)
    {
        foreach ($vals as $key => $val) {
            $form->$key = $val;
        }
        return $form;
    }

    private function getExpiredToken()
    {
        // subtract the expiration time and a little more from the current time
        $expire = \Yii::$app->params['user.verifyAccountTokenExpire'];
        return \Yii::$app
      ->getSecurity()
      ->generateRandomString() . '_' . (time() - $expire - 1);
    }

    private function getValidToken()
    {
        return \Yii::$app
      ->getSecurity()
      ->generateRandomString() . '_' . time();
    }

    private function getForm($user = null)
    {
        if (is_null($user)) {
            $user = $this->user;
        }
        $form = $this->container->get('\site\models\SignupForm', [$user]);
        $form->attributes = $this->vals; // massive assignment
        return $form;
    }
}
