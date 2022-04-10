<?php
namespace Tephida\Database\Exception;

use Tephida\Corner\CornerTrait;

/**
 * InvalidIdentifier.
 *
 * @package Tephida\Database
 */
class InvalidIdentifier extends \InvalidArgumentException implements ExceptionInterface
{
    use CornerTrait;
}
