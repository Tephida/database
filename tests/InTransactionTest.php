<?php
declare(strict_types=1);

namespace Tephida\Database\Tests;

use Tephida\Database\Factory;

class InTransactionTest extends DatabaseTest
{

    /**
     * @param $dsn
     * @param string|null $username
     * @param string|null $password
     * @param array $options
     * @dataProvider goodFactoryCreateArgumentProvider
     */
    public function testInTransaction(
        $expectedDriver,
        $dsn,
        $username = null,
        $password = null,
        array $options = []
    ) {
        $db = Factory::create($dsn, $username, $password, $options);
        $this->assertFalse($db->inTransaction());
        $db->beginTransaction();
        $this->assertTrue($db->inTransaction());
        $db->commit();
        $this->assertFalse($db->inTransaction());
        $db->beginTransaction();
        $this->assertTrue($db->inTransaction());
        $db->rollBack();
        $this->assertFalse($db->inTransaction());
    }
}
