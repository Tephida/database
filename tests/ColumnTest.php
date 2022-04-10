<?php
declare(strict_types=1);

namespace Tephida\Database\Tests;

use Tephida\Database\Database;

class ColumnTest extends ColTest
{
    protected function getResultForMethod(Database $db, $statement, $offset, $params)
    {
        return $db->column($statement, $params, $offset);
    }
}
