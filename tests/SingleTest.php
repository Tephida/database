<?php
declare(strict_types=1);

namespace Tephida\Database\Tests;

use Tephida\Database\Database;

class SingleTest extends CellTest
{
    protected function getResultForMethod(Database $db, $statement, $offset, $params)
    {
        return $db->single($statement, $params);
    }
}
