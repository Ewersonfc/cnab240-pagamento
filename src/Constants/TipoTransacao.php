<?php
/**
 * Created by PhpStorm.
 * User: ewerson
 * Date: 06/08/18
 * Time: 14:51
 */

namespace Ewersonfc\CNAB240Pagamento\Constants;
/**
 * Class TipoTransacao
 */
class TipoTransacao
{
    /**
     * @var 'boleto'
     */
    const BOLETO = 'boleto';
    const BOLETOJ52 = 'boletoJ52';
    const TRANSFERENCIA = 'transferencia';
    const CHEQUE = 'transferencia';

    public static function getPaymentTypes()
    {
        return [
            TipoTransacao::BOLETO,
            TipoTransacao::TRANSFERENCIA,
            TipoTransacao::CHEQUE,
        ];
    }

    public static function getFileSuffix($transactionType)
    {
        if (in_array($transactionType, [TipoTransacao::TRANSFERENCIA, TipoTransacao::CHEQUE])) {
            return TipoTransacao::TRANSFERENCIA;
        } else {
            return TipoTransacao::BOLETO;
        }
    }
}