<?php
declare(strict_types=1);

namespace Tephida\Database\Tests;

use Tephida\Database\Exception\ConstructorFailed;
use Tephida\Database\Factory;

class ConstructorFailedTest extends DatabaseTest
{

    /**
     * @dataProvider badFactoryCreateArgumentProvider
     * @param $dsn
     * @param null $username
     * @param null $password
     * @param array $options
     */
    public function testConstructorFailed($dsn, $username = null, $password = null, $options = [])
    {
        $this->expectException(ConstructorFailed::class);
        Factory::create($dsn, $username, $password, $options);
    }
}
