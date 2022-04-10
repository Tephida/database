<?php
declare(strict_types=1);

namespace Tephida\Database\Tests;

/**
 * Class ExecTest
 * @package Tephida\Database\Tests
 */
class QuoteTest extends DatabaseTest
{

    /**
     * @dataProvider goodFactoryCreateArgument2DatabaseQuoteProvider
     * @depends      Tephida\Database\Tests\EscapeIdentifierTest::testEscapeIdentifier
     * @depends      Tephida\Database\Tests\EscapeIdentifierTest::testEscapeIdentifierThrowsSomething
     * @param callable $cb
     * @param $quoteThis
     * @param array $expectOneOfThese
     */
    public function testQuote(callable $cb, $quoteThis, array $expectOneOfThese)
    {
        $db = $this->databaseExpectedFromCallable($cb);

        $this->assertTrue(count($expectOneOfThese) > 0);

        $matchedOneOfThose = false;
        $quoted = $db->quote((string)$quoteThis);

        foreach ($expectOneOfThese as $expectThis) {
            if ($quoted === $expectThis) {
                $this->assertSame($quoted, $expectThis);
                $matchedOneOfThose = true;
            }
        }
        if (!$matchedOneOfThose) {
            $this->assertTrue(
                false,
                'Did not match ' . $quoted . ' against any of ' . implode('; ', $expectOneOfThese)
            );
        }
    }
}
