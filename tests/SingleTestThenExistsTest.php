<?php
declare(strict_types=1);

namespace Tephida\Database\Tests;

use Tephida\Database\Database;

class SingleTestThenExistsTest extends DatabaseWriteTest
{
    protected function getResultForMethod(Database $db, $statement, $params)
    {
        $args = $params;
        array_unshift($args, $statement);
        return call_user_func_array([$db, 'exists'], $args);
    }

    /**
     * @dataProvider goodFactoryCreateArgument2DatabaseInsertManyProvider
     * @depends      Tephida\Database\Tests\Is1DArrayThenDeleteReadOnlyTest::testDeleteThrowsException
     * @depends      Tephida\Database\Tests\Is1DArrayThenDeleteReadOnlyTest::testDeleteTableNameEmptyThrowsException
     * @depends      Tephida\Database\Tests\Is1DArrayThenDeleteReadOnlyTest::testDeleteTableNameInvalidThrowsException
     * @depends      Tephida\Database\Tests\Is1DArrayThenDeleteReadOnlyTest::testDeleteConditionsReturnsNull
     * @depends      Tephida\Database\Tests\InsertManyTest::testInsertMany
     * @depends      Tephida\Database\Tests\SingleTest::testMethod
     * @param callable $cb
     * @param array $insertMany
     */
    public function testExists(callable $cb, array $insertMany)
    {
        $db = $this->databaseExpectedFromCallable($cb);
        $this->assertFalse(
            $db->exists('SELECT COUNT(*) FROM irrelevant_but_valid_tablename')
        );
        $db->insertMany('irrelevant_but_valid_tablename', $insertMany);
        $this->assertTrue(
            $db->exists('SELECT COUNT(*) FROM irrelevant_but_valid_tablename')
        );
        foreach ($insertMany as $insertVal) {
            $this->assertTrue(
                $this->getResultForMethod(
                    $db,
                    'SELECT COUNT(*) FROM irrelevant_but_valid_tablename WHERE foo = ?',
                    array_values($insertVal)
                )
            );
            $db->delete('irrelevant_but_valid_tablename', $insertVal);
            $this->assertFalse(
                $this->getResultForMethod(
                    $db,
                    'SELECT COUNT(*) FROM irrelevant_but_valid_tablename WHERE foo = ?',
                    array_values($insertVal)
                )
            );
        }
        $this->assertFalse(
            $db->exists('SELECT COUNT(*) FROM irrelevant_but_valid_tablename')
        );
    }
}
