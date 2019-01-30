<?php
/**
 * Created by PhpStorm.
 * User: ewerson
 * Date: 27/07/18
 * Time: 14:51
 */
namespace Ewersonfc\CNAB240Pagamento;

use Ewersonfc\CNAB240Pagamento\Constants\TipoRetorno;
use Ewersonfc\CNAB240Pagamento\Exceptions\CNAB240PagamentoException;
/**
 * Class Bancos
 */
class Bancos
{
    /**
     * @var integer|341
     */
    const ITAU = 341;
    const BANCODOBRASIL = 1;

    /**
     * @param int $banco
     * @return array
     * @throws CNAB240PagamentoException
     */
    public static function getBankData(int $banco)
    {
        switch ($banco) {
            case self::ITAU:
                return [
                    'codigo_banco' => '341',
                    'nome_banco' => 'ITAU',
                    'path_remessa' => realpath(dirname(__FILE__)."/../resources/Itau/remessa"),
                    'path_retorno' => realpath(dirname(__FILE__)."/../resources/Itau/retorno")
                ];
                break;
            case self::BANCODOBRASIL:
                return [
                    'codigo_banco' => '001',
                    'nome_banco' => 'BANCODOBRASIL',
                    'path_remessa' => realpath(dirname(__FILE__) . "/../resources/Bb/remessa"),
                    'path_retorno' => realpath(dirname(__FILE__) . "/../resources/Bb/retorno")
                ];
                break;
            default:
                throw new CNAB240PagamentoException("Banco não encontrado.");
                break;
        }
    }

    public static function getItauDetailType($type)
    {
        switch ($type)
        {
            case 'DB':
                return TipoRetorno::CONFIRMACAO_REJEICAO;
            case '00':
                return TipoRetorno::LIQUIDACAO;
            default:
                return TipoRetorno::CONFIRMACAO_REJEICAO;
        }
    }
}