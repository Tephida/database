<?php
namespace Tephida\Database\Exception;

use Tephida\Corner\CornerTrait;

/**
 * QueryError.
 *
 * @package Tephida\Database
 */
class QueryError extends \RuntimeException implements ExceptionInterface
{
    use CornerTrait;
}
