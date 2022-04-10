<?php
namespace Tephida\Database\Exception;

use Tephida\Corner\CornerTrait;

/**
 * ConstructorFailed.
 *
 * @package Tephida\Database
 */
class ConstructorFailed extends \RuntimeException implements ExceptionInterface
{
    use CornerTrait;

    /** @var \PDOException|null $realException */
    private $realException = null;

    /**
     * @param \PDOException $ex
     * @return ConstructorFailed
     */
    public function setRealException(\PDOException $ex): self
    {
        $this->realException = $ex;
        return $this;
    }

    /**
     * @return \PDOException|null
     */
    public function getRealException()
    {
        return $this->realException;
    }
}
