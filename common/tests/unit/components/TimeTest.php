<?php

namespace common\tests\unit\components;

use \DateTime;
use \DateTimeZone;
use Yii;
use common\components\Time;

date_default_timezone_set('UTC');

/**
 * Time test
 */

class TimeTest extends \Codeception\Test\Unit
{
    public function setUp(): void
    {
        $this->container = new \yii\di\Container;
        $this->container->set('common\interfaces\UserInterface', '\site\tests\_support\MockUser');
        $this->container->set('common\interfaces\UserBehaviorInterface', '\site\tests\_support\MockUserBehavior');
        $this->container->set('common\interfaces\QuestionInterface', '\site\tests\_support\MockQuestion');

        $this->container->set('common\interfaces\TimeInterface', function () {
            return new \common\components\Time('America/Los_Angeles');
        });

        $this->time = $this->container->get('common\interfaces\TimeInterface');

        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    public function testNow()
    {
        expect('now should return a \DateTime object', $this->assertInstanceOf(\DateTime::class, $this->time->now()));
        expect('now should return a \DateTime object with the correct timezone', $this->assertEquals('America/Los_Angeles', $this->time->now()->getTimezone()->getName()));
        expect('now should return a \DateTime object with the current date', $this->assertEquals($this->time->getLocalDate(), $this->time->now()->format('Y-m-d')));
    }

    public function testGetLocalTime()
    {
        expect("getLocalTime should work with user's set time", $this->assertEquals($this->time->getLocalTime(), (new DateTime("now", new DateTimeZone("America/Los_Angeles")))->format("Y-m-d H:i:s")));
        expect('getLocalTime should work with a custom timezone', $this->assertEquals($this->time->getLocalTime("UTC"), (new DateTime("now"))->format("Y-m-d H:i:s")));
    }

    public function testConvertLocalToUTC()
    {
        $la_tz = (new DateTime("now", new DateTimeZone("America/Los_Angeles")))->format("Y-m-d H:i:s");

        expect('convertLocalToUTC should convert a Los Angeles tz to UTC with the included time', $this->assertEquals($this->time->convertLocalToUTC($la_tz), (new DateTime("now"))->format("Y-m-d H:i:s")));
        expect('convertLocalToUTC should convert a Los Angeles tz to UTC without the included time', $this->assertEquals($this->time->convertLocalToUTC($la_tz, false), (new DateTime("now"))->format("Y-m-d")));
        // with UTC
        $this->container->set('common\interfaces\TimeInterface', function () {
            return new \common\components\Time('UTC');
        });
        $time = $this->container->get('common\interfaces\TimeInterface');

        $utc_tz = (new DateTime("now"))->format("Y-m-d H:i:s");
        expect('convertLocalToUTC should convert a UTC tz correctly with the included time', $this->assertEquals($time->convertLocalToUTC($utc_tz), (new DateTime("now"))->format("Y-m-d H:i:s")));
        expect('convertLocalToUTC should convert a UTC tz correctly without the included time', $this->assertEquals($time->convertLocalToUTC($utc_tz, false), (new DateTime("now"))->format("Y-m-d")));
    }

    public function testConvertUTCToLocal()
    {
        $utc_tz = (new DateTime("now"))->format("Y-m-d H:i:s");

        expect('convertUTCToLocal should convert a UTC tz to Los Angeles with the included timezone', $this->assertEquals((new DateTime("now", new DateTimeZone("America/Los_Angeles")))->format(DateTime::ATOM), $this->time->convertUTCToLocal($utc_tz)));
        expect('convertUTCToLocal should convert a UTC tz to Los Angeles without the included timezone', $this->assertEquals($this->time->convertUTCToLocal($utc_tz, false), (new DateTime("now", new DateTimeZone("America/Los_Angeles")))->format("Y-m-d H:i:s")));

        // with UTC
        $this->container->set('common\interfaces\TimeInterface', function () {
            return new \common\components\Time('UTC');
        });
        $time = $this->container->get('common\interfaces\TimeInterface');
        $utc_tz = (new DateTime("now"))->format("Y-m-d H:i:s");

        expect('convertLocalToUTC should convert a UTC tz correctly with the included time', $this->assertEquals($time->convertLocalToUTC($utc_tz), (new DateTime("now"))->format("Y-m-d H:i:s")));
        expect('convertLocalToUTC should convert a UTC tz correctly without the included time', $this->assertEquals($time->convertLocalToUTC($utc_tz, false), (new DateTime("now"))->format("Y-m-d")));
    }

    public function testGetLocalDate()
    {
        expect("getLocalDate should correctly get the user's local date", $this->assertEquals($this->time->getLocalDate(), (new DateTime("now", new DateTimeZone("America/Los_Angeles")))->format("Y-m-d")));
        expect("getLocalDate should correctly get the local date of a specified timezone", $this->assertEquals($this->time->getLocalDate("UTC"), (new DateTime("now", new DateTimeZone("UTC")))->format("Y-m-d")));
    }

    public function testAlterLocalDate()
    {
        expect('alterLocalDate should add one day to the local time', $this->assertEquals($this->time->alterLocalDate('2016-05-01 00:00:00', '+1 day'), (new DateTime("2016-05-01 00:00:00 +1 day"))->format("Y-m-d")));
        expect('alterLocalDate should subtract one week to the local time and change the year correctly', $this->assertEquals($this->time->alterLocalDate('2016-01-01 04:03:00', '-1 week'), (new DateTime("2016-01-01 04:03:00 -1 week"))->format("Y-m-d")));
    }

    public function testGetUTCBookends()
    {
        expect('getUTCBookends should return false if there is a space in the time string', $this->assertFalse($this->time->getUTCBookends('2016-05-30 00:00:00')));
        expect('getUTCBookends should return false if there is a space at the start of the time string', $this->assertFalse($this->time->getUTCBookends(' 2016-05-30 00:00:00')));
        expect('getUTCBookends should return false if there is a space at the end of the time string', $this->assertFalse($this->time->getUTCBookends('2016-05-30 00:00:00 ')));
        expect('getUTCBookends should return UTC bookend times from the Los_Angeles tz', $this->assertEquals($this->time->getUTCBookends('2016-05-30'), ['2016-05-30 07:00:00', '2016-05-31 06:59:59']));
        // with UTC
        $this->container->set('common\interfaces\TimeInterface', function () {
            return new \common\components\Time('UTC');
        });
        $time = $this->container->get('common\interfaces\TimeInterface');
        expect('getUTCBookends should return UTC bookend times from UTC tz', $this->assertEquals($time->getUTCBookends('2016-05-30'), ['2016-05-30 00:00:00', '2016-05-30 23:59:59']));
    }

    public function testParse()
    {
        $good = new DateTime('2016-05-05', new DateTimeZone('America/Los_Angeles'));
        expect('parse should accept a String time and verify it is in YYYY-MM-DD format, then return it as a \DateTime', $this->assertEquals($this->time->parse('2016-05-05'), $good));

        $observer = $this
        ->getMockBuilder("common\components\Time")
        ->setConstructorArgs(['America/Los_Angeles'])
        ->setMethods(['inBounds'])
        ->getMock();
        $observer->expects($this->once())
        ->method('inBounds')
        ->with($this->equalTo($good))
        ->willReturn(true);
        expect('parse should return a \DateTime object if the date is in bounds', $this->assertInstanceOf(DateTime::class, $observer->parse('2016-05-05')));
        expect('parse should return the default value (false) if the date itself is null', $this->assertFalse($this->time->parse(null)));

        $observer2 = $this
        ->getMockBuilder("common\components\Time")
        ->setConstructorArgs(['America/Los_Angeles'])
        ->setMethods(['inBounds'])
        ->getMock();
        $observer2->expects($this->exactly(2))
        ->method('inBounds')
        ->with($this->equalTo($good))
        ->willReturn(false);
        expect('parse should return the default value (false) if the date is not in bounds', $this->assertFalse($observer2->parse('2016-05-05')));
        expect('parse should return the default value if the date is not in bounds', $this->assertEquals($observer2->now()->format('Y-m-d'), $observer2->parse('2016-05-05', $observer2->now())->format('Y-m-d')));

        expect('parse should return false if the date itself is empty', $this->assertFalse($this->time->parse('')));
        expect('parse should return false if the date itself is unacceptable or is in an unacceptable format', $this->assertFalse($this->time->parse('aaaa-aa-aa')));
        expect('parse should return false if the date is not in bounds', $this->assertFalse($this->time->parse('aaaa-aa-aa')));
        expect('parse should return false if the date itself is nonsensical', $this->assertFalse($this->time->parse('2018-22-44')));

        $this->time->timezone = 'UTC';
        $good = new DateTime('2016-05-05', new DateTimeZone('UTC'));
        expect('parse should accept a String time and verify it is in YYYY-MM-DD format, then return it as a \DateTime', $this->assertEquals($this->time->parse('2016-05-05'), $good));

        expect('parse should return false if the date itself is empty', $this->assertFalse($this->time->parse('')));
        expect('parse should return false if the date itself is unacceptable or is in an unacceptable format', $this->assertFalse($this->time->parse('aaaa-aa-aa')));
        expect('parse should return false if the date is not in bounds', $this->assertFalse($this->time->parse('aaaa-aa-aa')));
        expect('parse should return false if the date itself is nonsensical', $this->assertFalse($this->time->parse('2018-22-44')));
    }

    public function testInBounds()
    {
        $dt = new DateTime('2016-05-05');
        expect('a sensible date should be in bounds', $this->assertTrue($this->time->inBounds($dt)));

        $early = '0111-10-01';
        $dt = new DateTime($early);
        $this->assertTrue($this->time::EARLIEST_DATE > $early);
        expect('a too-early date should be not in bounds', $this->assertFalse($this->time->inBounds($dt)));

        $tomorrow = new DateTime("now + 1 day", new DateTimeZone('America/Los_Angeles'));
        expect('tomorrow\'s date should be not in bounds', $this->assertFalse($this->time->inBounds($tomorrow)));
    
        $dt = new DateTime('2016-05-05 05:14:22');
        expect('different formats of sensible dates should be in bounds', $this->assertTrue($this->time->inBounds($dt)));
    }

    public function testValidate()
    {
        expect('validate should return the local date by default', $this->assertEquals($this->time->validate(), $this->time->now()->format('Y-m-d')));
        expect('validate should return same date string if given a valid format', $this->assertEquals($this->time->validate('2015-02-02'), '2015-02-02'));
        expect('validate should return the local date if given an invalid format', $this->assertEquals($this->time->validate('definitelynotadate9000'), $this->time->now()->format('Y-m-d')));
    }

    public function testGetDateTimesInPeriod()
    {
        expect('getDateTImesInPeriod should return an a \DatePeriod', $this->assertInstanceOf(\DatePeriod::class, $this->time->getDateTimesInPeriod()));
        expect('getDateTimesInPeriod should return a 30 day period by default', $this->assertCount(30, $this->time->getDateTimesInPeriod()));
        expect('getDateTimesInPeriod should return an arbitrary period length if supplied', $this->assertCount(42, $this->time->getDateTimesInPeriod(42)));

        $tz = 'America/Los_Angeles';
        $this->time->timezone = 'America/Los_Angeles';

        $today =  new \DateTime("now + 1 day", new \DateTimeZone($tz)); // we do an extra day to include today as the last day
        $should_be_today = $this->time->getDateTimesInPeriod()->getEndDate();
        expect('getDateTimesInPeriod should return a list of \DatePeriods, the last being for today', $this->assertEquals($today->format('Y-m-d H'), $should_be_today->format('Y-m-d H')));

        $start =  new \DateTime("30 days ago", new \DateTimeZone($tz));
        $should_be_start = $this->time->getDateTimesInPeriod()->getStartDate();
        expect('getDateTimesInPeriod should return a list of \DatePeriods, the first being for 30 days ago (by default)', $this->assertEquals($start->format('Y-m-d H'), $should_be_start->format('Y-m-d H')));
    }
}
