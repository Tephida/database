<?php
declare(strict_types=1);
namespace Tephida\Database\Exception;

use Tephida\Corner\CornerInterface;
use Tephida\Corner\CornerTrait;
use Throwable;

/**
 * Class MustBeOneDimensionalArray
 * @package Tephida\Database\Exception
 */
class MustBeOneDimensionalArray extends \InvalidArgumentException implements CornerInterface
{
    use CornerTrait;

    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->supportLink = 'https://github.com/Tephida/Database#only-one-dimensional-arrays-are-allowed';
        $this->helpfulMessage = "Many of the Database methods expect variadic parameters.

Instead of doing something like this:

    \$rows = \$db->run(\$query, \$params);

You want to do something like this:

    \$rows = \$db->run(\$query, ...\$params);
    \$rows = \$db->safeQuery(\$query, \$params);

A list of variadic methods and their array-expecting equivalents is as follows:
 
   * col() -> column(): array
   * cell() -> single(): scalar
   * first() -> column(): array
   * exists() -> single(): bool
   * q() -> safeQuery(): array[]
   * row() -> safeQuery(): array
   * run() -> safeQuery(): array[]
";
    }
}
