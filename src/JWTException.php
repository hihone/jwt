<?php
/**
 * JWT异常
 *
 * User: hihone
 * Date: 2019/3/1
 * Time: 14:56
 * Description:
 */

namespace hihone\jwt;

use Exception;
use Throwable;

class JWTException extends Exception
{
    public function __construct($message = "", $code = -1, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}