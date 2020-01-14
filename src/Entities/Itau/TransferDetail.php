<?php

namespace Ewersonfc\CNAB240Pagamento\Entities\Itau;

use Carbon\Carbon;
use Ewersonfc\CNAB240Pagamento\Contracts\DetailContract;
use Ewersonfc\CNAB240Pagamento\Helpers\ItauMatchDataHelper;


class TransferDetail implements DetailContract
{
    protected $helperOcorrencias;
    private $codigoBanco;
    private $codigoLote;
    private $tipoRegistro;
    private $numeroRegistro;
    private $segmento;
    private $tipoMovimento;
    private $bancoFavorecido;
    private $valor;
    private $nomeFavorecido;
    private $descontos;
    private $acrescimos;
    private $dataPagamento;
    private $valorPagamento;
    private $zeros;
    private $seuNumero;
    private $nossoNumero;
    private $ocorrencias;
    private $aprovado;
    private $tipoAprovacao;
    private $rejeicao;
    private $moedaTipo;
    private $agencia;
    private $conta;
    private $dac;
    private $codigoIspb;
    private $camara;
    private $dataEfetiva;
    private $finalidadeDetalhe;
    private $numeroDocumento;
    private $numeroInscricao;
    private $finalidadeDoc;
    private $finalidadeTed;
    private $aviso;


    //Metodos seguindo a nomenclatura do Safra, para padronização

    public function getOperacao()
    {
        $occurrences = $this->getOcorrencias();

        if ($occurrences[0] == "00")
            return "L";

        if ($occurrences[0] == "BD")
            return "C";

        return false;
    }

    //Limpa espaços em branco e 00 e BD, mantendo apenas rejeições

    /**
     * @return mixed
     */
    public function getOcorrencias()
    {
        return $this->ocorrencias;
    }

    /**
     * @param mixed $ocorrencias
     *
     * @return self
     */
    public function setOcorrencias($ocorrencias)
    {
        $this->ocorrencias = array_filter(array_map('trim', str_split($ocorrencias, 2)));
        return $this;
    }

    public function getRejeicao()
    {
        $occurrences = $this->getOcorrencias();
        $tmp_cleaned = array_diff($occurrences, ['00', 'BD']);
        return $tmp_cleaned;
    }

    /**
     * @param mixed $rejeicao
     *
     * @return self
     */
    public function setRejeicao($rejeicao)
    {
        $this->rejeicao = $rejeicao;

        return $this;
    }

    public function getCompromisso()
    {
        return $this->getSeuNumero();
    }

    /**
     * @return mixed
     */
    public function getSeuNumero()
    {
        return $this->seuNumero;
    }

    /**
     * @param mixed $seuNumero
     *
     * @return self
     */
    public function setSeuNumero($seuNumero)
    {
        $this->seuNumero = $seuNumero;

        return $this;
    }

    public function getOcorrencia()
    {
        return $this->getOcorrencias();
    }

    public function getValorCompromisso()
    {
        return $this->getValorPagamento();
    }


    /**
     * @return mixed
     */
    public function getVencimento()
    {
        return $this->vencimento;
    }

    /**
     * @return mixed
     */
    public function getCodigoBanco()
    {
        return $this->codigoBanco;
    }

    /**
     * @param mixed $codigoBanco
     *
     * @return self
     */
    public function setCodigoBanco($codigoBanco)
    {
        $this->codigoBanco = $codigoBanco;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodigoLote()
    {
        return $this->codigoLote;
    }

    /**
     * @param mixed $codigoLote
     *
     * @return self
     */
    public function setCodigoLote($codigoLote)
    {
        $this->codigoLote = $codigoLote;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTipoRegistro()
    {
        return $this->tipoRegistro;
    }

    /**
     * @param mixed $tipoRegistro
     *
     * @return self
     */
    public function setTipoRegistro($tipoRegistro)
    {
        $this->tipoRegistro = $tipoRegistro;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNumeroRegistro()
    {
        return $this->numeroRegistro;
    }

    /**
     * @param mixed $numeroRegistro
     *
     * @return self
     */
    public function setNumeroRegistro($numeroRegistro)
    {
        $this->numeroRegistro = $numeroRegistro;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSegmento()
    {
        return $this->segmento;
    }

    /**
     * @param mixed $segmento
     *
     * @return self
     */
    public function setSegmento($segmento)
    {
        $this->segmento = $segmento;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTipoMovimento()
    {
        return $this->tipoMovimento;
    }

    /**
     * @param mixed $tipoMovimento
     *
     * @return self
     */
    public function setTipoMovimento($tipoMovimento)
    {
        $this->tipoMovimento = $tipoMovimento;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBancoFavorecido()
    {
        return $this->bancoFavorecido;
    }

    /**
     * @param mixed $bancoFavorecido
     *
     * @return self
     */
    public function setBancoFavorecido($bancoFavorecido)
    {
        $this->bancoFavorecido = $bancoFavorecido;

        return $this;
    }


    /**
     * @return mixed
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * @param mixed $valor
     *
     * @return self
     */
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }



    /**
     * @return mixed
     */
    public function getNomeFavorecido()
    {
        return $this->nomeFavorecido;
    }

    /**
     * @param mixed $nomeFavorecido
     *
     * @return self
     */
    public function setNomeFavorecido($nomeFavorecido)
    {
        $this->nomeFavorecido = $nomeFavorecido;

        return $this;
    }


    /**
     * @param mixed $valorTitulo
     *
     * @return self
     */
    public function setValorTitulo($valorTitulo)
    {
        $this->valorTitulo = $valorTitulo;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescontos()
    {
        return $this->descontos;
    }

    /**
     * @param mixed $descontos
     *
     * @return self
     */
    public function setDescontos($descontos)
    {
        $this->descontos = $descontos;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAcrescimos()
    {
        return $this->acrescimos;
    }

    /**
     * @param mixed $acrescimos
     *
     * @return self
     */
    public function setAcrescimos($acrescimos)
    {
        $this->acrescimos = $acrescimos;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDataPagamento()
    {
        return $this->dataPagamento;
    }

    /**
     * @param mixed $dataPagamento
     *
     * @return self
     */
    public function setDataPagamento($dataPagamento)
    {
        $this->dataPagamento = $dataPagamento;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getValorPagamento()
    {
        return $this->valorPagamento;
    }

    /**
     * @param mixed $valorPagamento
     *
     * @return self
     */
    public function setValorPagamento($valorPagamento)
    {
        $this->valorPagamento = $valorPagamento;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getZeros()
    {
        return $this->zeros;
    }

    /**
     * @param mixed $zeros
     *
     * @return self
     */
    public function setZeros($zeros)
    {
        $this->zeros = $zeros;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNossoNumero()
    {
        return $this->nossoNumero;
    }

    /**
     * @param mixed $nossoNumero
     *
     * @return self
     */
    public function setNossoNumero($nossoNumero)
    {
        $this->nossoNumero = $nossoNumero;

        return $this;
    }


    /**
     * @return mixed
     */
    public function getAprovado()
    {
        return $this->aprovado;
    }

    /**
     * @param mixed $aprovado
     *
     * @return self
     */
    public function setAprovado($aprovado)
    {
        $this->aprovado = $aprovado;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTipoAprovacao()
    {
        return $this->tipoAprovacao;
    }

    /**
     * @return mixed
     */
    // public function getRejeicao()
    // {
    //     return $this->rejeicao;
    // }

    /**
     * @param mixed $tipoAprovacao
     *
     * @return self
     */
    public function setTipoAprovacao($tipoAprovacao)
    {
        $this->tipoAprovacao = $tipoAprovacao;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMoedaTipo()
    {
        return $this->moedaTipo;
    }

    /**
     * @param mixed $moedaTipo
     */
    public function setMoedaTipo($moedaTipo)
    {
        $this->moedaTipo = $moedaTipo;
    }

    /**
     * @return mixed
     */
    public function getCodigoIspb()
    {
        return $this->codigoIspb;
    }

    /**
     * @param mixed $codigoIspb
     */
    public function setCodigoIspb($codigoIspb)
    {
        $this->codigoIspb = $codigoIspb;
    }

    /**
     * @return mixed
     */
    public function getCamara()
    {
        return $this->camara;
    }

    /**
     * @param mixed $camara
     */
    public function setCamara($camara)
    {
        $this->camara = $camara;
    }

    /**
     * @return mixed
     */
    public function getDataEfetiva()
    {
        return $this->dataEfetiva;
    }

    /**
     * @param mixed $dataEfetiva
     */
    public function setDataEfetiva($dataEfetiva)
    {
        $this->dataEfetiva = $dataEfetiva;
    }


    /**
     * @return mixed
     */
    public function getFinalidadeDetalhe()
    {
        return $this->finalidadeDetalhe;
    }

    /**
     * @param mixed $finalidadeDetalhe
     */
    public function setFinalidadeDetalhe($finalidadeDetalhe)
    {
        $this->finalidadeDetalhe = $finalidadeDetalhe;
    }

    /**
     * @return mixed
     */
    public function getNumeroDocumento()
    {
        return $this->numeroDocumento;
    }

    /**
     * @param mixed $numeroDocumento
     */
    public function setNumeroDocumento($numeroDocumento)
    {
        $this->numeroDocumento = $numeroDocumento;
    }

    /**
     * @return mixed
     */
    public function getNumeroInscricao()
    {
        return $this->numeroInscricao;
    }

    /**
     * @param mixed $numeroInscricao
     */
    public function setNumeroInscricao($numeroInscricao)
    {
        $this->numeroInscricao = $numeroInscricao;
    }

    /**
     * @return mixed
     */
    public function getFinalidadeDoc()
    {
        return $this->finalidadeDoc;
    }

    /**
     * @param mixed $finalidadeDoc
     */
    public function setFinalidadeDoc($finalidadeDoc)
    {
        $this->finalidadeDoc = $finalidadeDoc;
    }

    /**
     * @return mixed
     */
    public function getFinalidadeTed()
    {
        return $this->finalidadeTed;
    }

    /**
     * @param mixed $finalidadeTed
     */
    public function setFinalidadeTed($finalidadeTed)
    {
        $this->finalidadeTed = $finalidadeTed;
    }

    /**
     * @return mixed
     */
    public function getAviso()
    {
        return $this->aviso;
    }

    /**
     * @param mixed $aviso
     */
    public function setAviso($aviso)
    {
        $this->aviso = $aviso;
    }

    /**
     * @return mixed
     */
    public function getAgencia()
    {
        return $this->agencia;
    }

    /**
     * @return mixed
     */
    public function getConta()
    {
        return $this->conta;
    }

    /**
     * @param mixed $agenciaConta
     */
    public function setAgenciaConta($agenciaConta)
    {
        $this->agencia = substr($agenciaConta, 0, 5);
        $this->conta = substr($agenciaConta, 6, 12);
        $this->dac = substr($agenciaConta, 18, 2);
    }

    /**
     * @return mixed
     */
    public function getDac()
    {
        return $this->dac;
    }
}