<?php
declare(strict_types=1);
namespace Tephida\Database\Exception;

use Tephida\Corner\CornerInterface;
use Tephida\Corner\CornerTrait;

/**
 * Class MustBeArrayOrEasyStatement
 * @package Tephida\Database\Exception
 */
class MustBeArrayOrEasyStatement extends \TypeError implements CornerInterface
{
    use CornerTrait;

    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->supportLink = 'https://github.com/Tephida/Database';
        $this->helpfulMessage = "This method expects either a 1-dimensional array with string keys,
or an EasyStatement object.

If you're not using the EasyStatement class in your code, make sure you're passing an array.

Each array element should be a key => value pair, where the key is the column name.";
    }
}
