<?php
declare(strict_types=1);

namespace Tephida\Database\Tests;

use PDOStatement;

/**
 * Class ExecTest
 * @package Tephida\Database\Tests
 */
class QuoteThenQueryTest extends DatabaseWriteTest
{

    /**
     * @dataProvider goodFactoryCreateArgument2DatabaseInsertManyProvider
     * @depends      Tephida\Database\Tests\QuoteTest::testQuote
     * @depends      Tephida\Database\Tests\EscapeIdentifierTest::testEscapeIdentifier
     * @depends      Tephida\Database\Tests\EscapeIdentifierTest::testEscapeIdentifierThrowsSomething
     * @param callable $cb
     * @param array $maps
     */
    public function testQuery(callable $cb, array $maps)
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

            $this->assertInstanceOf(PDOStatement::class, $db->query($queryString));
        }
    }
}
