<?php

namespace Ewersonfc\CNAB240Pagamento\Factories;


use Ewersonfc\CNAB240Pagamento\Entities\Itau\BoletoDetail;
use Ewersonfc\CNAB240Pagamento\Entities\Itau\TransferDetail;

class RetornoFactory
{
    private $header_lote;

    private $detail;

    public function setHeader($headerLote)
    {
        $this->header_lote = $headerLote;
    }

    public function makeDetail($segment_detail)
    {
        switch ($segment_detail) {
            case 'A':
                return $this->makeSegmentoA();
                break;
            default:
                return $this->makeSegmentoJ();
        }
    }

    private function makeRejeicao($dataRejeicao)
    {
        return str_split($dataRejeicao, 2);
    }

    public function setDetail($detail)
    {
        $this->detail = $detail;
    }

    public function makeSegmentoJ()
    {
        foreach($this->detail as $detail) {
            $approved = false;
            $fileResponse = new BoletoDetail;

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

    public function makeSegmentoA()
    {
        $response = [];
        foreach ($this->detail as $detail) {
            $approved = false;
            $fileResponse = new TransferDetail();

            $operacao = substr($detail['ocorrencias']['value'], 0, 2);

            if ($operacao == '00' || $operacao == 'BD')
                $approved = true;

            //CONSIDERAR SE CONVERTO O 00 E BD PARA "LIQUIDACAO" E "CONFIRMAÇÃO" PARA FICAR IGUAL AO SAFRA

            $fileResponse->setAprovado($approved);

            $fileResponse->setTipoAprovacao($operacao);
            if (!$approved) {
                $fileResponse->setRejeicao($this->makeRejeicao($detail['ocorrencias']['value']));
            }

            $fileResponse->setCodigoBanco($detail['codigo_banco']['value']);
            $fileResponse->setCodigoLote($detail['codigo_lote']['value']);
            $fileResponse->setTipoRegistro($detail['tipo_registro']['value']);
            $fileResponse->setNumeroRegistro($detail['numero_registro']['value']);
            $fileResponse->setSegmento($detail['segmento']['value']);
            $fileResponse->setTipoMovimento($detail['tipo_movimento']['value']);
            $fileResponse->setBancoFavorecido($detail['banco_favorecido']['value']);
            $fileResponse->setValor($detail['valor_efetivo']['value']);
            $fileResponse->setNomeFavorecido($detail['nome_favorecido']['value']);
            $fileResponse->setValorPagamento($detail['valor_pagamento']['value']);
            $fileResponse->setZeros($detail['zeros']['value']);
            $fileResponse->setSeuNumero($detail['seu_numero']['value']);
            $fileResponse->setNossoNumero($detail['nosso_numero']['value']);
            $fileResponse->setOcorrencias($detail['ocorrencias']['value']);
            $fileResponse->setMoedaTipo($detail['moeda_tipo']['value']);
            $fileResponse->setAgenciaConta($detail['agencia_conta']['value']);
            $fileResponse->setCodigoIspb($detail['codigo_ispb']['value']);
            $fileResponse->setCamara($detail['camara']['value']);
            $fileResponse->setDataEfetiva($detail['data_efetiva']['value']);
            $fileResponse->setFinalidadeDetalhe($detail['finalidade_detalhe']['value']);
            $fileResponse->setNumeroDocumento($detail['numero_documento']['value']);
            $fileResponse->setNumeroInscricao($detail['numero_inscricao']['value']);
            $fileResponse->setFinalidadeDoc($detail['finalidade_doc']['value']);
            $fileResponse->setFinalidadeTed($detail['finalidade_ted']['value']);
            $fileResponse->setAviso($detail['aviso']['value']);
            $response[] = $fileResponse;
        }
        return $response;
    }
}


