<?php


namespace Ewersonfc\CNAB240Pagamento\Contracts;

interface DetailContract
{
    public function getAprovado();

    public function getOperacao();

    public function getSeuNumero();

    public function getValorPagamento();

}