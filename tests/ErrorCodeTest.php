<?php
declare(strict_types=1);

namespace Tephida\Database\Tests;

/**
 * Class DatabaseTest
 * @package Tephida\Database\Tests
 */
class ErrorCodeTest extends DatabaseTest
{

    /**
     * @param callable $cb
     * @dataProvider goodFactoryCreateArgument2DatabaseProvider
     */
    public function testNoError(callable $cb)
    {
        $db = $this->databaseExpectedFromCallable($cb);

        $this->assertSame($db->errorCode(), '00000');
    }
}
