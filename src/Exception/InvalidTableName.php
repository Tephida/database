<?php
declare(strict_types=1);

namespace Tephida\Database\Exception;

use Tephida\Corner\CornerInterface;
use Tephida\Corner\CornerTrait;

/**
 * Class InvalidTableName
 * @package Tephida\Database\Exception
 */
class InvalidTableName extends \InvalidArgumentException implements CornerInterface
{
    use CornerTrait;
}
