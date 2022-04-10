<?php
declare(strict_types=1);

namespace Tephida\Database\Tests;

use Tephida\Database\Database;

class RowTest extends SafeQueryTest
{
    protected function getResultForMethod(Database $db, $statement, $offset, $params)
    {
        $args = $params;
        array_unshift($args, $statement);

        return call_user_func_array([$db, 'row'], $args);
    }

    /**
     * @dataProvider goodColArgumentsProvider
     * @param callable $cb
     * @param string $statement
     * @param int $offset
     * @param array $params
     * @param array $expectedResult
     */
    public function testMethod(callable $cb, $statement, $offset, $params, $expectedResult)
    {
        $db = $this->databaseExpectedFromCallable($cb);

        $result = $this->getResultForMethod($db, $statement, $offset, $params);

        $this->assertEquals(array_diff_assoc($result, $expectedResult[0]), []);
    }
}
