<?php
namespace Ewersonfc\CNAB240Pagamento\Entities;

class DataFile
{
    // Remessa trabalha de forma a fazer um lote por arquivo, retorno foi possibilitado multiplos lotes

    public $header_arquivo;
    // Retorno Param
    public $lotes = [];
    // Remessa Param
    public $detail = [];
    public $trailer_arquivo;

    public function addLote(LoteEntity $lote)
    {
        $this->lotes[] = $lote;
    }
}