<?php
declare(strict_types=1);

namespace Tephida\Database\Tests;

use Tephida\Database\Database;
use Tephida\Database\Factory;
use PHPUnit\Framework\TestCase;

/**
 * Class FactoryTest
 * @package Tephida\Database\Tests
 */
class FactoryTest extends TestCase
{
    public function testFactoryCreate()
    {
        $this->assertInstanceOf(
            Database::class,
            Factory::create('sqlite::memory:')
        );
    }
}
