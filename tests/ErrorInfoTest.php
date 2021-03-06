<?php
declare(strict_types=1);

namespace Tephida\Database\Tests;

/**
 * Class ErrorInfoTest
 * @package Tephida\Database\Tests
 */
class ErrorInfoTest extends DatabaseTest
{

    /**
     * @dataProvider goodFactoryCreateArgument2DatabaseProvider
     * @param callable $cb
     */
    public function testNoError(callable $cb)
    {
        $db = $this->databaseExpectedFromCallable($cb);

        $info = $db->errorInfo();
        $this->assertTrue(is_array($info));
        $this->assertSame($info[0], '00000');
        $this->assertSame($info[1], null);
        $this->assertSame($info[2], null);
    }
}
