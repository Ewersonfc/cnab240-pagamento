<?php

namespace Ewersonfc\CNAB240Pagamento\Entities;


use Ewersonfc\CNAB240Pagamento\Contracts\DetailContract;

class LoteEntity
{
    public $header_lote;
    public $detail = [];
    public $trailer_lote;

    public function addDetail(DetailContract $detail)
    {
        $this->detail[] = $detail;
    }
}
