<?php
/**
 * Created by PhpStorm.
 * User: ewerson
 * Date: 29/08/18
 * Time: 15:09
 */

namespace Ewersonfc\CNAB240Pagamento\Exceptions;


class FileRetornoException extends CNAB240PagamentoException
{
    /**
     * FileRetornoException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    function __construct($message = null, $code = null, Throwable $previous = null)
    {
        if($message == "")
            $message = "Não foi possível ler o arquivo, verifique o caminho ou a permissão de leitura.";

        parent::__construct($message, $code, $previous);
    }
}