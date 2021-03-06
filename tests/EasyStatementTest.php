<?php

namespace Tephida\Database\Tests;

use Tephida\Database\EasyStatement;
use Tephida\Database\Exception\MustBeNonEmpty;
use PHPUnit\Framework\TestCase as TestCase;
use RuntimeException;

/**
 * @package Tephida\Database\Tests
 */
class EasyStatementTest extends TestCase
{
    public function testBasicAndOr()
    {
        $statement = EasyStatement::open()
            ->with('id = ?', 1)
            ->andWith('last_login > ?', 'today')
            ->orWith('last_login IS NULL');

        $this->assertSql($statement, 'id = ? AND last_login > ? OR last_login IS NULL');
        $this->assertValues($statement, [1, 'today']);
    }

    public function testLogicalIn()
    {
        $statement = EasyStatement::open()
            ->in('role_id IN (?*)', [1, 2, 3])
            ->orIn('user_id IN (?*)', [100]);

        $this->assertSql($statement, 'role_id IN (?, ?, ?) OR user_id IN (?)');
        $this->assertValues($statement, [1, 2, 3, 100]);

        $statement = EasyStatement::open()
            ->orIn('role_id IN (?*)', [4, 5, 6]);

        $this->assertSql($statement, 'role_id IN (?, ?, ?)');
        $this->assertValues($statement, [4, 5, 6]);
    }

    public function testEmptyIn()
    {
        try {
            $statement = EasyStatement::open()
                ->in('role_id IN (?*)', [1, 2, 3])
                ->orIn('user_id IN (?*)', []);
            $this->fail("Does not throw MustBeNonEmpty by default!");
        } catch (MustBeNonEmpty $ex) {
            $statement = EasyStatement::open()
                ->setEmptyInStatementsAllowed(true)
                ->in('role_id IN (?*)', [1, 2, 3])
                ->orIn('user_id IN (?*)', []);
        }

        $this->assertSql($statement, 'role_id IN (?, ?, ?)');
        $this->assertValues($statement, [1, 2, 3]);

        $statement = EasyStatement::open()
            ->setEmptyInStatementsAllowed(true)
            ->group()
                ->with('user_id = ?', 100)
                ->orWith('user_id = ?', 101)
                ->orGroup()
                    ->in('role_id IN (?*)', [])
                ->endGroup()
            ->endGroup();

        $this->assertSql($statement, '(user_id = ? OR user_id = ? OR (1 = 0))');
        $this->assertValues($statement, [100, 101]);
    }

    public function testGroupingWithAnd()
    {
        $statement = EasyStatement::open()
            ->with('id = ?', 1);

        $group = $statement->group()
            ->with('last_login > ?', 'today')
            ->orWith('last_login IS NULL');

        $this->assertSame($statement, $group->endGroup());

        $this->assertSql($group, 'last_login > ? OR last_login IS NULL');
        $this->assertSql($statement, 'id = ? AND (' . $group->sql() . ')');

        $this->assertValues($statement, [1, 'today']);
    }

    public function testGroupingWithOr()
    {
        $statement = EasyStatement::open()
            ->orGroup()
                ->with('failed_logins > ?', 5)
                ->andWith('last_login IS NULL')
            ->end()
            ->orGroup()
                ->with('role = ?', 'banned')
            ->end();

        $this->assertSql($statement, '(failed_logins > ? AND last_login IS NULL) OR (role = ?)');
        $this->assertValues($statement, [5, 'banned']);
    }

    public function testGroupParent()
    {
        $this->expectException(RuntimeException::class);

        EasyStatement::open()->endGroup();
    }

    private function assertSql(EasyStatement $statement, $expected)
    {
        $this->assertSame($expected, $statement->sql());
        $this->assertSame($expected, (string) $statement);
    }

    private function assertValues(EasyStatement $statement, array $values)
    {
        $this->assertSame($values, $statement->values());
    }

    public function testEmpty()
    {
        $stmt = EasyStatement::open();
        $this->assertSql($stmt, '1 = 1');
    }

    public function testPrecedence()
    {
        $sth1 = EasyStatement::open();
        $sth1->with("a=1");
        $sth1->orWith("a=2");
        $sth1->orWith("a=3");

        $sth2 = EasyStatement::open();
        $sth2->with("status=1");
        $sth2->andWith($sth1);

        $this->assertSql($sth2, 'status=1 AND (a=1 OR a=2 OR a=3)');
    }
}
