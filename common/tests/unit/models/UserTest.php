<?php

namespace common\unit\models;

use Yii;
use common\models\User;

date_default_timezone_set('UTC');

/**
 * User test
 */

class UserTest extends \Codeception\Test\Unit
{
    private $user;
    private $time;
    private $question;


    public $exportData = [
    [
      'id' => 485,
      'date' => '2017-07-29 10:40:29',
      'behavior_id' => 59,
      'question1' => 'q1',
      'question2' => 'q2',
      'question3' => 'q3',
      'behavior' => [
        'id' => 59,
        'name' => 'repetitive, negative thoughts',
      ],
      'category' => [
        'id' => 4,
        'name' => 'Speeding Up',
      ],
    ], [
      'id' => 487,
      'date' => '2017-07-29 10:40:29',
      'behavior_id' => 106,
      'question1' => 'q1',
      'question2' => 'q2',
      'question3' => 'q3',
      'behavior' => [
        'id' => 106,
        'name' => 'tired',
      ],
      'category' => [
        'id' => 6,
        'name' => 'Exhausted',
      ],
    ], [
      'id' => 488,
      'date' => '2017-07-29 10:40:29',
      'behavior_id' => 125,
      'question1' => 'q1',
      'question2' => 'q2',
      'question3' => 'q3',
      'behavior' => [
        'id' => 125,
        'name' => 'out of control',
      ],
      'category' => [
        'id' => 7,
        'name' => 'Relapse/Moral Failure',
      ],
    ], [
      'id' => 486,
      'date' => '2017-07-29 10:40:29',
      'behavior_id' => 89,
      'question1' => 'q1',
      'question2' => 'q2',
      'question3' => 'q3',
      'behavior' => [
        'id' => 89,
        'name' => 'obsessive (stuck) thoughts',
      ],
      'category' => [
        'id' => 5,
        'name' => 'Ticked Off',
      ],
    ]
  ];

    public function setUp(): void
    {
        $this->container = new \yii\di\Container;
        $this->container->set('common\interfaces\UserInterface', '\site\tests\_support\MockUser');
        $this->container->set('common\interfaces\UserBehaviorInterface', '\site\tests\_support\MockUserBehavior');
        $this->container->set('common\interfaces\TimeInterface', function () {
            return new \common\components\Time('America/Los_Angeles');
        });

        $user_behavior = $this->container->get('common\interfaces\UserBehaviorInterface');
        $this->time    = $this->container->get('common\interfaces\TimeInterface');

        $this->question = $this->getMockBuilder('\common\models\Question')
      ->setMethods(['save', 'attributes'])
      ->getMock();

        $this->user = $this->getMockBuilder(User::class)
      ->setConstructorArgs([$user_behavior, $this->time])
      ->setMethods(['save', 'attributes'])
      ->getMock();
        $this->user->method('save')->willReturn(true);
        $this->user->method('attributes')->willReturn([
      'id',
      'password_hash',
      'password_reset_token',
      'verify_email_token',
      'change_email_token',
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
    ]);

        parent::setUp();
    }

    protected function tearDown(): void
    {
        $this->user = null;
        parent::tearDown();
    }

    public function testIsTokenCurrent()
    {
        $good_token = \Yii::$app
            ->getSecurity()
            ->generateRandomString() . '_' . time();
        expect('isTokenCurrent should return true if the token is still current/alive', $this->assertTrue($this->user->isTokenCurrent($good_token)));
        $expire = \Yii::$app->params['user.passwordResetTokenExpire'];
        $bad_token = \Yii::$app
            ->getSecurity()
            ->generateRandomString() . '_' . (time() - $expire - 1); // subtract the expiration time and a little more from the current time
        expect('isTokenCurrent should return false if the token is expired', $this->assertFalse($this->user->isTokenCurrent($bad_token)));
    }

    public function testIsTokenConfirmed()
    {
        expect('isTokenConfirmed should return true if the token has been confirmed', $this->assertTrue($this->user->isTokenConfirmed('token123_confirmed')));
        expect('isTokenConfirmed should return false if the token has not been confirmed', $this->assertFalse($this->user->isTokenConfirmed('token123')));
        expect('isTokenConfirmed should return false if the token has not been confirmed', $this->assertFalse($this->user->isTokenConfirmed('token123_not_blah')));
    }

    public function testGeneratePasswordResetToken()
    {
        expect('password_reset_token should be null by default', $this->assertNull($this->user->password_reset_token));
        $this->user->generatePasswordResetToken();
        expect('password_reset_token should now have a verification token set', $this->assertRegExp('/.*_[0-9]+/', $this->user->password_reset_token));
    }

    public function testGenerateVerifyEmailToken()
    {
        expect('verify_email_token should be null by default', $this->assertNull($this->user->verify_email_token));
        $this->user->generateVerifyEmailToken();
        expect('verify_email_token should now have a verification token set', $this->assertRegExp('/.*_[0-9]+/', $this->user->verify_email_token));
    }

    public function testConfirmVerifyEmailToken()
    {
        $this->user->verify_email_token = 'hello_world';
        $this->user->confirmVerifyEmailToken();
        expect('confirmVerifyEmailToken should append User::CONFIRMED_STRING to the end of the verify_email_token property', $this->assertEquals($this->user->verify_email_token, 'hello_world'.$this->user::CONFIRMED_STRING));
    }

    public function testIsVerified()
    {
        $this->user->verify_email_token = null;
        expect('isVerified should return true if the token is null', $this->assertTrue($this->user->isVerified()));
        $this->user->verify_email_token = '';
        expect('isVerified should return false if the token is the empty string', $this->assertFalse($this->user->isVerified()));
        $this->user->verify_email_token = 'this_looks_truthy';
        expect('isVerified should return false if the token is still present', $this->assertFalse($this->user->isVerified()));
    }

    public function testRemoveVerifyEmailToken()
    {
        $this->user->verify_email_token = 'faketoken_1234';
        $this->user->removeVerifyEmailToken();
        expect('removeVerifyEmailToken should set the verify_email_token to be null', $this->assertNull($this->user->verify_email_token));
    }

    public function testGetIdHash()
    {
        $hash = 'iegYQgiPZUF48kk5bneuPn9_6ZOZhkMEGJ6Y8yICgKc';

        $this->user->id = 12345;
        $this->user->created_at = "2017-12-31 23:59:59";
        expect('getIdHash should return a url-safe string', $this->assertEquals($this->user->getidHash(), $hash));

        $this->user->created_at = "2018-01-01 00:00:00";
        expect('getIdHash should return a DIFFERENT url-safe string for different params', $this->assertNotEquals($this->user->getidHash(), $hash));
    }

    public function testCleanExportRow()
    {
        // need this for the convertUTCToLocal call
        Yii::configure(Yii::$app, [
      'components' => [
        'user' => [
          'class' => 'yii\web\User',
          'identityClass' => 'common\tests\unit\FakeUser',
        ],
      ],
    ]);
        $identity = new \common\tests\unit\FakeUser();
        $identity->timezone = "America/Los_Angeles";
        // logs in the user
        Yii::$app->user->setIdentity($identity);

        expect('cleanExportRow should clean and mutate the queried data to be suitable for downloading', $this->assertEquals([
      [
        'date' => '2017-07-29 03:40:29',
        'behavior' => 'repetitive, negative thoughts',
        'category' => 'Speeding Up',
        'question1' => 'q1',
        'question2' => 'q2',
        'question3' => 'q3',
      ], [
        'date' => '2017-07-29 03:40:29',
        'behavior' => 'tired',
        'category' => 'Exhausted',
        'question1' => 'q1',
        'question2' => 'q2',
        'question3' => 'q3',
      ], [
        'date' => '2017-07-29 03:40:29',
        'behavior' => 'out of control',
        'category' => 'Relapse/Moral Failure',
        'question1' => 'q1',
        'question2' => 'q2',
        'question3' => 'q3',
      ], [
        'date' => '2017-07-29 03:40:29',
        'behavior' => 'obsessive (stuck) thoughts',
        'category' => 'Ticked Off',
        'question1' => 'q1',
        'question2' => 'q2',
        'question3' => 'q3',
      ]
    ], array_map(function ($row) {
        return $this->user->cleanExportRow($row);
    }, $this->exportData)));
    }

    public function testGenerateChangeEmailToken()
    {
        expect('change_email_token should be null by default', $this->assertNull($this->user->change_email_token));
        $this->user->generateChangeEmailToken();
        expect('change_email_token should now have a verification token set', $this->assertRegExp('/.*_[0-9]+/', $this->user->change_email_token));
    }

    public function testRemoveChangeEmailToken()
    {
        $this->user->change_email_token = 'faketoken_1234';
        $this->user->removeChangeEmailToken();
        expect('removeChangeEmailToken should set the change_email_token to be null', $this->assertNull($this->user->change_email_token));
    }

    public function testSendEmailReport()
    {
        $user_behavior = $this->getMockBuilder(\common\models\UserBehavior::class)
      ->disableOriginalConstructor()
      ->setMethods(['save', 'attributes', 'getCheckinBreakdown'])
      ->getMock();
        $expected = require(__DIR__.'/../data/expected_getCheckinBreakdown.php');
        $user_behavior->method('getCheckinBreakdown')->willReturn($expected);

        $user = $this->getMockBuilder(User::class)
      ->setConstructorArgs([$user_behavior, $this->time])
      ->setMethods(['save', 'attributes'])
      ->getMock();
        $user->method('save')->willReturn(true);
        $user->method('attributes')->willReturn([
      'send_email',
      'email_category',
    ]);

        $user->send_email = false;
        $user->email_category = 6;
        expect('it should not send any emails if the user has disabled send_email', $this->assertFalse($user->sendEmailReport('2019-01-01')));
        expect('it should not send any emails if the user did not select any behaviors for the given day', $this->assertFalse($user->sendEmailReport('2018-01-01')));
        expect('it should not send any emails if the user did not select any behaviors for the given day', $this->assertFalse($user->sendEmailReport('2018-03-02')));
        expect('it should not send any emails if the user did not meet or exceed their email_category', $this->assertFalse($user->sendEmailReport('2019-03-01')));
    }
}
