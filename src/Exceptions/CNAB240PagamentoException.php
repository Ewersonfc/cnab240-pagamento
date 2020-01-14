<?php
/**
 * Created by PhpStorm.
 * User: ewerson
 * Date: 27/07/18
 * Time: 15:02
 */
namespace Ewersonfc\CNAB240Pagamento\Exceptions;

use Exception;
use Throwable;

/**
 * Class CNAB240PagamentoException
 * @package Ewersonfc\CNAB240Pagamento\Exceptions
 */
class CNAB240PagamentoException extends Exception
{
    /**
     * CNAB240PagamentoException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    function __construct($message = null, $code = null, Throwable $previous = null)
    {
        if(!$message)
            $message = 'Não foi possível gerar arquivo de remessa.';

        parent::__construct($message, $code, $previous);
    }
}