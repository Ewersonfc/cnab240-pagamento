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

    /**
     * @var 'cheque'
     */
    const CHEQUE = 'cheque';

    /**
     * @var 'tranferencia'
     * ESSE VALOR ENGLOBA TIPOS: TED, DOC E TRANFERENCIA ENTRE CONTAS
     */
    const TRANSFERENCIA = 'transferencia';
}