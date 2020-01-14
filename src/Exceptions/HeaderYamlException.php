<?php
/**
 * Created by PhpStorm.
 * User: ewerson
 * Date: 02/08/18
 * Time: 12:12
 */

namespace Ewersonfc\CNAB240Pagamento\Exceptions;


use Throwable;

/**
 * Class HeaderYamlException
 * @package Ewersonfc\CNAB240Pagamento\Exceptions
 */
class HeaderYamlException extends CNAB240PagamentoException
{
    /**
     * HeaderYamlException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    function __construct($message = null, $code = null, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}