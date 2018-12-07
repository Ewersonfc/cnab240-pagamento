<?php

namespace Ewersonfc\CNAB240Pagamento\Responses;

use Carbon\Carbon;

class FileResponse
{
    private $codigoBanco;
    private $codigoLote;
    private $tipoRegistro;
    private $numeroRegistro;
    private $segmento;
    private $tipoMovimento;
    private $bancoFavorecido;
    private $moeda;
    private $dv;
    private $vencimento;
    private $valor;
    private $campoLivre;
    private $nomeFavorecido;
    private $dataVencto;
    private $valorTitulo;
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


    //Metodos seguindo a nomenclatura do Safra, para padronização

    public function getOperacao() {
        $tmp = str_split($this->getOcorrencias(), 2);
        $trimmed = array_filter(array_map('trim', $tmp));

        if($trimmed[0] == "00")
            return "L";

        if($trimmed[0] == "BD")
            return "C";

        return false;
    }

    //Limpa espaços em branco e 00 e BD, mantendo apenas rejeições
    public function getRejeicao() {
        $tmp = str_split($this->getOcorrencias(), 2);
        $tmp_trimmed = array_filter(array_map('trim', $tmp));
        $tmp_cleaned = array_diff($tmp_trimmed, ['00','BD']);
        return $tmp_cleaned;
    }

    public function getCompromisso() {
        return $this->getSeuNumero();
    }

    public function getOcorrencia() {
        return $this->getOcorrencias();
    }

    public function getValorCompromisso() {
        return $this->getValorTitulo();
    }

    public function getDataVencimento() {
        $dateBase = Carbon::parse('1997-10-07');
        $dateBase->addDays($tmp = $this->getVencimento())->format('Y-m-d');

        return $dateBase;
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
    public function getMoeda()
    {
        return $this->moeda;
    }

    /**
     * @param mixed $moeda
     *
     * @return self
     */
    public function setMoeda($moeda)
    {
        $this->moeda = $moeda;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDv()
    {
        return $this->dv;
    }

    /**
     * @param mixed $dv
     *
     * @return self
     */
    public function setDv($dv)
    {
        $this->dv = $dv;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getVencimento()
    {
        return $this->vencimento;
    }

    /**
     * @param mixed $vencimento
     *
     * @return self
     */
    public function setVencimento($vencimento)
    {
        $this->vencimento = $vencimento;

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
    public function getCampoLivre()
    {
        return $this->campoLivre;
    }

    /**
     * @param mixed $campoLivre
     *
     * @return self
     */
    public function setCampoLivre($campoLivre)
    {
        $this->campoLivre = $campoLivre;

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
     * @return mixed
     */
    public function getDataVencto()
    {
        return $this->dataVencto;
    }

    /**
     * @param mixed $dataVencto
     *
     * @return self
     */
    public function setDataVencto($dataVencto)
    {
        $this->dataVencto = $dataVencto;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getValorTitulo()
    {
        return $this->valorTitulo;
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
        $this->ocorrencias = $ocorrencias;

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
    // public function getRejeicao()
    // {
    //     return $this->rejeicao;
    // }

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

}