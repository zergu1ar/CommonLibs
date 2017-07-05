<?php

namespace Zergular\Common;

/**
 * Class UnknownMethodException
 * @package Zergular\Common
 */
class UnknownMethodException extends \Exception
{
    /**
     * UnknownMethodException constructor.
     * @param string $message
     * @param int $code
     * @param \Throwable|NULL $previous
     */
    public function __construct($message = "", $code = 0, \Throwable $previous = null)
    {
        parent::__construct(
            'Unknown method ' . $message,
            $code,
            $previous
        );
    }
}
