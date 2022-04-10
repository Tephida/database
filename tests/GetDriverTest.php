<?php
declare(strict_types=1);

namespace Tephida\Database\Tests;

use Tephida\Database\Factory;

class GetDriverTest extends DatabaseTest
{
    /**
     * @param $dsn
     * @param string|null $username
     * @param string|null $password
     * @param array $options
     * @param string $expectedDriver
     *
     * @dataProvider goodFactoryCreateArgumentProvider
     */
    public function testGetDriver(
        $expectedDriver,
        $dsn,
        $username = null,
        $password = null,
        $options = []
    ) {
        $db = Factory::create($dsn, $username, $password, $options);
        $this->assertEquals($db->getDriver(), $expectedDriver);
    }
}
