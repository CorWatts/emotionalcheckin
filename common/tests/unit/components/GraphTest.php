<?php

namespace common\tests\unit\components;

use Yii;
use common\components\Graph;

/**
 * Graph test
 */

class GraphTest extends \Codeception\Test\Unit
{
    private $user;
    private $filepath = __DIR__.'/../../_output/test_graph.png';
    private $filepath_extra = __DIR__.'/../../_output/charts/test_graph.png';

    public function setUp(): void
    {
        $this->user = $this->getMockBuilder('\site\tests\_support\MockUser')
          ->setMethods(['getIdHash'])
          ->getMock();

        parent::setUp();
    }

    protected function tearDown(): void
    {
        $this->user = null;
        parent::tearDown();
    }

    public function testGetFilepath()
    {
        $this->user
      ->method('getIdHash')
      ->willReturn('random1DH4sh');
        $graph = new Graph($this->user);

        expect('the expected graph image filepath will be returned', $this->assertEquals(dirname(dirname(dirname(dirname(__DIR__)))).'/site/web/charts/random1DH4sh.png', $graph->getFilepath()));
    }

    public function testGetUrl()
    {
        $this->user
      ->method('getIdHash')
      ->willReturn('random1DH4sh');
        $graph = new Graph($this->user);

        expect('the expected graph image filepath will be returned', $this->assertStringEndsWith('/charts/random1DH4sh.png', $graph->getUrl()));
    }

    public function testDestroy()
    {
        $this->graph = $this->getMockBuilder('\common\components\Graph', [$this->user])
      ->setConstructorArgs([$this->user])
      ->setMethods(['getFilepath'])
      ->getMock();
        $this->graph
      ->method('getFilepath')
      ->willReturn($this->filepath);

        if (!file_exists($this->filepath) && preg_match('%/_output/test_graph.png$%', $this->filepath)) {
            touch($this->filepath);
            expect('just a check to be sure $filepath is sane', $this->assertStringEndsWith('/_output/test_graph.png', $this->filepath));
            expect('the generated file should exist', $this->assertFileExists($this->filepath));
            expect('the generated file should be readable', $this->assertFileExists($this->filepath));
            $this->graph->destroy();
            expect('the generated file should no longer exist', $this->assertFileNotExists($this->filepath));
        } else {
            expect('the file should not exist yet. If this fails, delete the file', $this->assertFileNotExists($this->filepath));
            expect('this should NOT happen', $this->assertTrue(false));
        }
    }

    public function testCreate()
    {
        $this->graph = $this->getMockBuilder('\common\components\Graph', [$this->user])
      ->setConstructorArgs([$this->user])
      ->setMethods(['getFilepath'])
      ->getMock();
        $this->graph
      ->method('getFilepath')
      ->willReturn($this->filepath_extra);

        expect('the file should not exist yet. If this fails, delete the file', $this->assertFileNotExists($this->filepath));
        expect('the containing directory should not exist yet either. If this fails, delete the directory', $this->assertFalse(is_dir(dirname($this->filepath_extra))));

        $this->graph->create(checkinBreakdown(), true);

        expect('just a check to be sure $filepath_extra is sane', $this->assertStringEndsWith('/_output/charts/test_graph.png', $this->filepath_extra));
        expect('the generated file should exist', $this->assertFileExists($this->filepath_extra));
        expect('the generated file should be readable', $this->assertFileExists($this->filepath_extra));

        // cleanup
        if (file_exists($this->filepath_extra) && preg_match('%/_output/charts/test_graph.png%', $this->filepath_extra)) {
            // just in case something is weird, we don't want to straight rm this file
            unlink($this->filepath_extra);
            rmdir(dirname($this->filepath_extra));
        }
    }
}

function checkinBreakdown()
{
    return [
      '2019-01-31' => [],
      '2019-02-01' => [],
      '2019-02-02' => [
        1 => [
          'name' => 'Restoration',
          'count' => 4,
          'color' => '#008000',
          'highlight' => '#199919',
        ], 2 => [
          'name' => 'Forgetting Priorities',
          'count' => 4,
          'color' => '#4CA100',
          'highlight' => '#61B219',
        ], 3 => [
          'name' => 'Anxiety',
          'count' => 7,
          'color' => '#98C300',
          'highlight' => '#AACC33',
        ], 4 => [
          'name' => 'Speeding Up',
          'count' => 5,
          'color' => '#E5E500',
          'highlight' => '#E5E533',
        ], 5 => [
          'name' => 'Ticked Off',
          'count' => 5,
          'color' => '#E59900',
          'highlight' => '#E5AA33',
        ], 6 => [
          'name' => 'Exhausted',
          'count' => 5,
          'color' => '#E54B00',
          'highlight' => '#E56D33',
        ], 7 => [
          'name' => 'Relapse/Moral Failure',
          'count' => 3,
          'color' => '#CC0000',
          'highlight' => '#CC3333',
        ],
      ],
      '2019-02-03' => [],
      '2019-02-04' => [
        1 => [
          'name' => 'Restoration',
          'count' => 3,
          'color' => '#008000',
          'highlight' => '#199919',
        ], 2 => [
          'name' => 'Forgetting Priorities',
          'count' => 5,
          'color' => '#4CA100',
          'highlight' => '#61B219',
        ], 3 => [
          'name' => 'Anxiety',
          'count' => 3,
          'color' => '#98C300',
          'highlight' => '#AACC33',
        ], 4 => [
          'name' => 'Speeding Up',
          'count' => 1,
          'color' => '#E5E500',
          'highlight' => '#E5E533',
        ], 5 => [
          'name' => 'Ticked Off',
          'count' => 3,
          'color' => '#E59900',
          'highlight' => '#E5AA33',
        ], 6 => [
          'name' => 'Exhausted',
          'count' => 7,
          'color' => '#E54B00',
          'highlight' => '#E56D33',
        ],
      ],
      '2019-02-05' => [],
      '2019-02-06' => [],
      '2019-02-07' => [],
      '2019-02-08' => [],
      '2019-02-09' => [
        2 => [
          'name' => 'Forgetting Priorities',
          'count' => 6,
          'color' => '#4CA100',
          'highlight' => '#61B219',
        ], 3 => [
          'name' => 'Anxiety',
          'count' => 3,
          'color' => '#98C300',
          'highlight' => '#AACC33',
        ], 4 => [
          'name' => 'Speeding Up',
          'count' => 4,
          'color' => '#E5E500',
          'highlight' => '#E5E533',
        ], 5 => [
          'name' => 'Ticked Off',
          'count' => 8,
          'color' => '#E59900',
          'highlight' => '#E5AA33',
        ], 6 => [
          'name' => 'Exhausted',
          'count' => 6,
          'color' => '#E54B00',
          'highlight' => '#E56D33',
        ], 7 => [
          'name' => 'Relapse/Moral Failure',
          'count' => 7,
          'color' => '#CC0000',
          'highlight' => '#CC3333',
        ],
      ],
      '2019-02-10' => [],
      '2019-02-11' => [],
      '2019-02-12' => [],
      '2019-02-13' => [],
      '2019-02-14' => [],
      '2019-02-15' => [],
      '2019-02-16' => [],
      '2019-02-17' => [],
      '2019-02-18' => [],
      '2019-02-19' => [],
      '2019-02-20' => [],
      '2019-02-21' => [],
      '2019-02-22' => [],
      '2019-02-23' => [],
      '2019-02-24' => [],
      '2019-02-25' => [],
      '2019-02-26' => [],
      '2019-02-27' => [],
      '2019-02-28' => [],
      '2019-03-01' => [
        2 => [ 'name' => 'Forgetting Priorities',
        'count' => 6,
        'color' => '#4CA100',
        'highlight' => '#61B219',
      ], 3 => [
        'name' => 'Anxiety',
        'count' => 5,
        'color' => '#98C300',
        'highlight' => '#AACC33',
      ], 4 => [
        'name' => 'Speeding Up',
        'count' => 6,
        'color' => '#E5E500',
        'highlight' => '#E5E533',
      ],
    ],
  ];
}
