<?php

namespace console\tests\unit\controllers;

use Yii;
use \console\controllers\FsaController;
use \common\fixtures\UserFixture;

class FsaControllerTest extends \Codeception\Test\Unit
{
    /**
     * @return array
     */
    public function _fixtures()
    {
        return [
            'user' => [
                'class' => UserFixture::className(),
                'dataFile' => codecept_data_dir() . 'user.php'
            ]
        ];
    }

    public function testRemoveOldUnconfirmedAccounts()
    {
        $expire = \Yii::$app->params['user.verifyAccountTokenExpire'];

        $count = (new \yii\db\Query())->select('count(*)')->from('user')->one();
        expect("count before cleanup", $this->assertEquals($count['count'], 10));
        $controller = new FsaController('fsa', 'console');
        $controller->actionRemoveOldUnconfirmedAccounts();
        $new_count = (new \yii\db\Query())->select('count(*)')->from('user')->one();
        expect("count after cleanup", $this->assertEquals($new_count['count'], 9));
    }

    public function testGetTimeThreshold()
    {
        $controller = new FsaController('fsa', 'console');
        $threshold = $controller->getTimeThreshold();
        expect("should be an integer", $this->assertIsInt($threshold));
        expect("should be a time in the past", $this->assertLessThan(time(), $threshold));
        expect("should have the right number of digits", $this->assertEquals(10, strlen((string)$threshold)));
    }
}

