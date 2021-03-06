<?php
declare(strict_types=1);

namespace Tephida\Database\Tests;

/**
 * Class ExecTest
 * @package Tephida\Database\Tests
 */
class QuoteThenExecThenLastInsertIdTest extends DatabaseWriteTest
{

    /**
     * @dataProvider goodFactoryCreateArgument2DatabaseInsertManyProvider
     * @depends      Tephida\Database\Tests\QuoteTest::testQuote
     * @depends      Tephida\Database\Tests\EscapeIdentifierTest::testEscapeIdentifier
     * @depends      Tephida\Database\Tests\EscapeIdentifierTest::testEscapeIdentifierThrowsSomething
     * @depends      Tephida\Database\Tests\QuoteThenExecTest::testExec
     * @param callable $cb
     * @param array $maps
     */
    public function testLastInsertId(callable $cb, array $maps)
    {
        $db = $this->databaseExpectedFromCallable($cb);
        $table = 'irrelevant_but_valid_tablename';

        $first = $maps[0];

        // Let's make sure our keys are escaped.
        $keys = \array_keys($first);
        foreach ($keys as $i => $v) {
            $keys[$i] = $db->escapeIdentifier($v);
        }

        foreach ($maps as $params) {
            $queryString = "INSERT INTO " . $db->escapeIdentifier($table) . " (";

            // Now let's append a list of our columns.
            $queryString .= \implode(', ', $keys);

            // This is the middle piece.
            $queryString .= ") VALUES (";

            // Now let's concatenate the ? placeholders
            $queryString .= \implode(
                ', ',
                \array_map(
                    function ($val) use ($db) {
                        return $db->quote($val);
                    },
                    $params
                )
            );

            // Necessary to close the open ( above
            $queryString .= ");";

            $db->exec($queryString);

            $this->assertSame($db->lastInsertId(), current($params));
        }
    }
}
