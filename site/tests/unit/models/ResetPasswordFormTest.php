<?php

namespace site\tests\unit\models;

use Yii;
use \site\models\ResetPasswordForm;
use \yii\base\InvalidArgumentException;

class ResetPasswordFormTest extends \Codeception\Test\Unit
{
    public function testShouldThrowWhenNotPassedAWellFormattedToken()
    {
        $this->expectException(InvalidArgumentException::class);
        new ResetPasswordForm('', $this->getUser());
    }

    public function testShouldThrowWhenTokenIsNotAssociatedWithAUser()
    {
        $this->expectException(InvalidArgumentException::class);
        $token = 'this_is_fine';
        $user = $this->getUser();
        $user->method('findbypasswordresettoken')->with($this->equalTo($token))->willReturn(null);
        new ResetPasswordForm($token, $user);
    }

    public function testRulesShouldValidate()
    {
        $token = 'doesnt_matter';
        $user = $this->getUser();
        $user->method('findbypasswordresettoken')->with($this->equalTo($token))->willReturn($user);
        $form = new ResetPasswordForm($token, $user);

        $form->attributes = [];
        expect('with no values, the form should not pass validation', $this->assertFalse($form->validate()));
        $form->attributes = ['password' => null];
        expect('the form should not validate if the password is not provided', $this->assertFalse($form->validate()));
        $form->attributes = ['password' => '1'];
        expect('the form should not validate if the password is too short', $this->assertFalse($form->validate()));
        $form->attributes = ['password' => 'super-secret-password-here'];
        expect('the form should validate if the provided password is long enough', $this->assertTrue($form->validate()));
    }

    public function testResetPassword()
    {
        $user = $this->getUser();
        $token = 'doesnt_matter';
        $user->method('findbypasswordresettoken')->with($this->equalTo($token))->willReturn($user);
        $user->password = 'old';

        $form = new ResetPasswordForm($token, $user);

        $user
      ->expects($this->once())
      ->method('removepasswordresettoken');
        $user
      ->expects($this->once())
      ->method('save');

        $form->password = 'new';
        $form->resetPassword();
        $this->assertNotEquals($user->password, 'old');
        $this->assertEquals($user->password, 'new');
    }

    private function getUser()
    {
        $user = $this->getmockbuilder('\common\models\user')
      ->disableoriginalconstructor()
      ->setmethods(['getisnewrecord', 'attributes', 'save', 'findbypasswordresettoken', 'removepasswordresettoken'])
      ->getmock();
        $user->method('attributes')->willReturn([
      'email',
      'password',
      'password_hash',
      'partner_email1',
      'partner_email2',
      'partner_email3',
    ]);
        return $user;
    }
}
