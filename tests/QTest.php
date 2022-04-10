<?php
declare(strict_types=1);

namespace Tephida\Database\Tests;

use Tephida\Database\Database;

class QTest extends RunTest
{
    protected function getResultForMethod(Database $db, $statement, $offset, $params)
    {
        $args = $params;
        array_unshift($args, $statement);

        return call_user_func_array([$db, 'q'], $args);
    }
}
