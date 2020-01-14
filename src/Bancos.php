<?php
namespace Ewersonfc\CNAB240Pagamento;

use Ewersonfc\CNAB240Pagamento\Constants\TipoRetorno;
use Ewersonfc\CNAB240Pagamento\Constants\TipoTransacao;
use Ewersonfc\CNAB240Pagamento\Exceptions\CNAB240PagamentoException;
use Ewersonfc\CNAB240Pagamento\Helpers\ItauMatchDataHelper;

class Bancos
{
    const ITAU = 341;

    public static function getBankData(int $banco)
    {
        switch ($banco) {
            case self::ITAU:
                return [
                    'codigo_banco' => '341',
                    'nome_banco' => 'ITAU',
                    'path_remessa' => realpath(dirname(__FILE__)."/../resources/Itau/remessa"),
                    'path_retorno' => realpath(dirname(__FILE__)."/../resources/Itau/retorno"),
                ];
            default:
                throw new CNAB240PagamentoException("Banco não encontrado.");
                break;
        }
    }

}