<?php

namespace Ewersonfc\CNAB240Pagamento\Factories;


use Ewersonfc\CNAB240Pagamento\Responses\FileResponse;
use Ewersonfc\CNAB240Pagamento\Entities\DataFile;

class RetornoFactory
{
    /**
     * @var
     */
    private $header_arquivo;

    /**
     * @var
     */
    private $header_lote;

    /**
     * @var
     */
    private $detail;

    /**
     * RetornoFactory constructor.
     * @param $header_arquivo
     * @param $header_lote
     * @param $detail
     * @param $trailer
     */
    function __construct($header_arquivo, $header_lote, $detail)
    {
        $this->header_arquivo = $header_arquivo;
        $this->header_lote = $header_lote;
        $this->detail = $detail;
    }

    private function makeRejeicao($dataRejeicao)
    {
        return str_split($dataRejeicao, 2);
    }

    /**
     * @return DataFile
     */
    public function generateItauResponse()
    {
        $response = [];
        foreach($this->detail as $detail) {
            $approved = false;
            $fileResponse = new FileResponse;

            $operacao = substr($detail['ocorrencias']['value'], 0, 2);

            if( $operacao == '00' || $operacao == 'BD') 
                $approved = true;

            //CONSIDERAR SE CONVERTO O 00 E BD PARA "LIQUIDACAO" E "CONFIRMAÇÃO" PARA FICAR IGUAL AO SAFRA

            $fileResponse->setAprovado($approved);

            $fileResponse->setTipoAprovacao($operacao);
            if(!$approved) {
                $fileResponse->setRejeicao($this->makeRejeicao($detail['ocorrencias']['value']));
            }
            $fileResponse->setCodigoBanco($detail['codigo_banco']['value']);
            $fileResponse->setCodigoLote($detail['codigo_lote']['value']);
            $fileResponse->setTipoRegistro($detail['tipo_registro']['value']);
            $fileResponse->setNumeroRegistro($detail['numero_registro']['value']);
            $fileResponse->setSegmento($detail['segmento']['value']);
            $fileResponse->setTipoMovimento($detail['tipo_movimento']['value']);
            $fileResponse->setBancoFavorecido($detail['banco_favorecido']['value']);
            $fileResponse->setMoeda($detail['moeda']['value']);
            $fileResponse->setDv($detail['dv']['value']);
            $fileResponse->setVencimento($detail['vencimento']['value']);
            $fileResponse->setValor($detail['valor']['value']);
            $fileResponse->setCampoLivre($detail['campo_livre']['value']);
            $fileResponse->setNomeFavorecido($detail['nome_favorecido']['value']);
            $fileResponse->setDataVencto($detail['data_vencto']['value']);
            $fileResponse->setValorTitulo($detail['valor_titulo']['value']);
            $fileResponse->setDescontos($detail['descontos']['value']);
            $fileResponse->setAcrescimos($detail['acrescimos']['value']);
            $fileResponse->setDataPagamento($detail['data_pagamento']['value']);
            $fileResponse->setValorPagamento($detail['valor_pagamento']['value']);
            $fileResponse->setZeros($detail['zeros']['value']);
            $fileResponse->setSeuNumero($detail['seu_numero']['value']);
            $fileResponse->setNossoNumero($detail['nosso_numero']['value']);
            $fileResponse->setOcorrencias($detail['ocorrencias']['value']);

            $response[] = $fileResponse;
        }
        return $response;
    }
}


