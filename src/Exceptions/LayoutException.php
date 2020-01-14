<?php
/**
 * Created by PhpStorm.
 * User: ewerson
 * Date: 02/08/18
 * Time: 12:28
 */

namespace Ewersonfc\CNAB240Pagamento\Exceptions;

use Throwable;
/**
 * Class LayoutException
 * @package Ewersonfc\CNAB240Pagamento\Exceptions
 */
class LayoutException extends CNAB240PagamentoException
{
    /**
     * LayoutException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = null, $code = null, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}