<?php

namespace site\tests\unit\models;

use Yii;
use \site\models\ChangeEmailForm;

class ChangeEmailFormTest extends \Codeception\Test\Unit
{
    public function testSimpleRulesShouldValidate()
    {
        $user = $this->getUser();
        $form = new ChangeEmailForm($user);

        $form->attributes = [];
        expect('with no values, the form should not pass validation', $this->assertFalse($form->validate()));
        $form->attributes = ['desired_email' => 'not_an_email'];
        expect('with a value that is not an email, the form should not pass validation', $this->assertFalse($form->validate()));
        $form->attributes = ['desired_email' => 'valid@email.com'];
        expect('with a valid email, the form should pass validation', $this->assertTrue($form->validate()));
    }

    public function testChangeEmail()
    {
        // changeEmail() should return true and do nothing when the email is already taken
        $user = $this->getUser();
        $user
            ->expects($this->never())
            ->method('save');
        $desired_email = 'new_email@email.com';
        $user
            ->method('findByEmail')
            ->willReturn(true); // true, as a stand-in for some valid user object

        $form = new ChangeEmailForm($user);
        $form->desired_email = $desired_email;
        $this->assertTrue($form->changeEmail(), 'should always return true');
        $this->assertNull($user->desired_email, 'should be null because we didn\'t set the new email');
        $this->assertNotEquals($user->desired_email, $desired_email);

        // changeEmail() should return true, set values, and send an email when the email is available
        $user = $this->getUser();
        $desired_email = 'new_email@email.com';
        $user
            ->method('findByEmail')
            ->willReturn(null);
        $user->email = 'email@email.com';

        $form = new ChangeEmailForm($user);
        $form->desired_email = $desired_email;

        expect('changeEmail should return true', $this->assertTrue($form->changeEmail()));

        expect('the user\'s desired email should now match what they entered in the form', $this->assertEquals($user->desired_email, $desired_email));

        $this->tester->seeEmailIsSent();
        $emailMessage = $this->tester->grabLastSentEmail();
        verify($emailMessage)->instanceOf('yii\mail\MessageInterface');
        verify($emailMessage->getTo())->arrayHasKey($desired_email);
        verify($emailMessage->getFrom())->arrayHasKey(Yii::$app->params['supportEmail']);
        verify($emailMessage->getSubject())->equals(Yii::$app->name . ' -- your requested email change');
        verify($emailMessage->toString())->stringContainsString($user->email);
    }

    private function getUser()
    {
        $user = $this->getmockbuilder('\common\models\user')
      ->disableoriginalconstructor()
      ->setmethods(['getisnewrecord', 'attributes', 'generatechangeemailtoken', 'findbyemail', 'save'])
      ->getmock();
        $user->method('attributes')->willreturn([
      'email',
      'desired_email',
      'change_email_token',
    ]);
        return $user;
    }
}
