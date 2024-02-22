<?php

namespace common\tests\unit\components;

use Yii;
use common\components\Utility;

/**
 * Utility test
 *
 * NOTE:
 * If these tests seem bizarre, a stray REVISION file might exist in the
 * same directory you're running the codecept command in. Delete that,
 * then run these tests again.
 */

class UtilityTest extends \Codeception\Test\Unit
{

    protected function tearDown(): void
    {
        // just in case we're forgetful :)
        if (file_exists(Utility::$REVISION_FILE)) {
            $this->_deleteRevFile();
        }
        parent::tearDown();
    }

    private function _createRevFile($data = false)
    {
        $data = $data ?: "abcdefghijklmnop";
        file_put_contents(Utility::$REVISION_FILE, $data);
    }

    private function _deleteRevFile()
    {
        unlink(Utility::$REVISION_FILE);
    }

    public function testGetRevHash()
    {
        expect('getRevHash should return false when the file does not exist', $this->assertFalse(Utility::getRevHash()));

        $this->_createRevFile();
        expect('getRevHash should return abcdefg', $this->assertEquals('abcdefg', Utility::getRevHash()));
        chmod(Utility::$REVISION_FILE, 0222);
        expect('getRevHash should return false when the file is not readable', $this->assertFalse(Utility::getRevHash()));
        $this->_deleteRevFile();

        $this->_createRevFile('abc');
        expect('getRevHash should return abc when there are less than 7 chars', $this->assertEquals('abc', Utility::getRevHash()));
        $this->_deleteRevFile();
    }

    public function testGetGithubRevUrl()
    {
        expect('getRevHash should return false when the getRevHash() returns false', $this->assertFalse(Utility::getGithubRevUrl()));
        $this->_createRevFile();
        expect('getRevHash should return the correct Github url when the getRevHash() returns data', $this->assertEquals("https://github.com/CorWatts/fasterscale/commit/abcdefg", Utility::getGithubRevUrl()));
        $this->_deleteRevFile();
    }
}
