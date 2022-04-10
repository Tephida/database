<?php
declare(strict_types=1);

namespace Tephida\Database\Tests;

use InvalidArgumentException;
use PDOException;

class InsertManyTest extends DatabaseWriteTest
{

    /**
     * @dataProvider goodFactoryCreateArgument2DatabaseProvider
     * @param callable $cb
     */
    public function testInsertManyNoFieldsThrowsException(callable $cb)
    {
        $db = $this->databaseExpectedFromCallable($cb);
        $this->expectException(InvalidArgumentException::class);
        $this->assertFalse($db->insertMany('irrelevant_but_valid_tablename', []));
    }

    /**
     * @dataProvider goodFactoryCreateArgument2DatabaseProvider
     * @param callable $cb
     */
    public function testInsertManyNoFieldsThrowsPdoException(callable $cb)
    {
        $db = $this->databaseExpectedFromCallable($cb);
        $this->expectException(PDOException::class);
        $db->insertMany('irrelevant_but_valid_tablename', [[], [1]]);
    }

    /**
     * @dataProvider goodFactoryCreateArgument2DatabaseProvider
     * @param callable $cb
     */
    public function testInsertManyArgTableThrowsException(callable $cb)
    {
        $db = $this->databaseExpectedFromCallable($cb);
        $this->expectException(InvalidArgumentException::class);
        $db->insertMany('', [['foo' => 1], ['foo' => 2]]);
    }

    /**
     * @dataProvider goodFactoryCreateArgument2DatabaseProvider
     * @param callable $cb
     */
    public function testInsertManyArgMapKeysThrowsException(callable $cb)
    {
        $db = $this->databaseExpectedFromCallable($cb);
        $this->expectException(InvalidArgumentException::class);
        $db->insertMany('irrelevant_but_valid_tablename', [['1foo' => 1]]);
    }

    /**
     * @dataProvider goodFactoryCreateArgument2DatabaseProvider
     * @param callable $cb
     */
    public function testInsertManyArgMapIs1DArrayThrowsException(callable $cb)
    {
        $db = $this->databaseExpectedFromCallable($cb);
        $this->expectException(InvalidArgumentException::class);
        $db->insertMany('irrelevant_but_valid_tablename', [['foo' => [1]]]);
    }

    /**
     * @dataProvider goodFactoryCreateArgument2DatabaseProvider
     * @param callable $cb
     */
    public function testInsertMany(callable $cb)
    {
        $db = $this->databaseExpectedFromCallable($cb);
        $db->insertMany('irrelevant_but_valid_tablename', [['foo' => '1'], ['foo' => '2']]);
        $this->assertEquals(
            $db->single('SELECT COUNT(*) FROM irrelevant_but_valid_tablename'),
            2
        );
    }
}
