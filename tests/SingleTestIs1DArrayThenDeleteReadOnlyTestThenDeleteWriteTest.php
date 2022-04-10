<?php
declare(strict_types=1);

namespace Tephida\Database\Tests;

class SingleTestIs1DArrayThenDeleteReadOnlyTestThenDeleteWriteTest extends DatabaseWriteTest
{

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
    public function testDelete(callable $cb, array $insertMany)
    {
        $db = $this->databaseExpectedFromCallable($cb);
        $db->insertMany('irrelevant_but_valid_tablename', $insertMany);
        $insertManyTotal = count($insertMany);
        $this->assertEquals(
            $db->single('SELECT COUNT(*) FROM irrelevant_but_valid_tablename'),
            $insertManyTotal
        );
        foreach ($insertMany as $insertVal) {
            $this->assertEquals(
                $db->single(
                    'SELECT COUNT(*) FROM irrelevant_but_valid_tablename WHERE foo = ?',
                    array_values($insertVal)
                ),
                1
            );
        }
        for ($i=0; $i<$insertManyTotal; ++$i) {
            $db->delete('irrelevant_but_valid_tablename', $insertMany[$i]);
            $this->assertEquals(
                $db->single('SELECT COUNT(*) FROM irrelevant_but_valid_tablename'),
                ($insertManyTotal - ($i + 1))
            );
        }
    }
}
