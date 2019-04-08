<?php

namespace Ewersonfc\CNAB240Pagamento\Factories;

use Ewersonfc\CNAB240Pagamento\Exceptions\CNAB240PagamentoException;
use Ewersonfc\CNAB240Pagamento\Exceptions\LayoutException;
use Ewersonfc\CNAB240Pagamento\Helpers\CNAB240Helper;
use mikehaertl\tmp\File;
use Ewersonfc\CNAB240Pagamento\Bancos;

/**
 * Class RemessaFactory
 * @package Ewersonfc\CNAB240Pagamento\Factories
 */
class RemessaFactory
{
    /**
     * @var array
     */
    private $header_arquivo;

    /**
     * @var array
     */
    private $header_lote;

    /**
     * @var array
     */
    private $detail;

    /**
     * @var array
     */
    private $trailer_lote;

    /**
     * @var array
     */
    private $trailer_arquivo;

    /**
     * @var string
     */
    private $content;

    /**
     * @var integer
     */
    private $control_arquivo;

    /**
     * @var integer
     */
    private $control_lote;

    /**
     * @var integer
     */
    private $valor_total_lote;

    /**
     * @var integer
     */
    private $banco;

    /**
     * RemessaFactory constructor.
     * @param array $header_arquivo
     * @param array $header_lote
     * @param array $detail
     * @param array $header_lote
     * @param array $header_arquivo
     */
    function __construct(array $header_arquivo, array $header_lote, array $detail, array $trailer_lote, array $trailer_arquivo, $banco)
    {
        $this->banco = $banco;
        $this->header_arquivo = $header_arquivo;
        $this->header_lote = $header_lote;
        $this->detail = $detail;
        $this->trailer_lote = $trailer_lote;
        $this->trailer_arquivo = $trailer_arquivo;
        $this->control_lote = 0;
        $this->control_arquivo = 0;
        $this->valor_total_lote = 0;
    }

    /**
     * @param array $fieldData
     * @param $nameField
     * @return string
     * @throws \Exception
     */
    private function makeField(array $fieldData, $nameField, $lastField = false)
    {

        $valueDefined = null;
        if(preg_match('/branco/', $nameField)) {
            $valueDefined = ' ';
        }

        if(isset($fieldData['value']) && $valueDefined === null) {
            $valueDefined = $fieldData['value'];
        } else if($valueDefined === null && isset($fieldData['default'])){
            $valueDefined = $fieldData['default'];
        }

        $pictureData = CNAB240Helper::explodePicture($fieldData['picture']);

        if(strlen($valueDefined) > $pictureData['firstQuantity'])
            throw new LayoutException("O Valor Passado no campo {$nameField} / {$valueDefined} está maior que os campos disponíveis ".$pictureData['firstQuantity']);

        if($pictureData['firstType'] == 9)
            return str_pad($valueDefined, $pictureData['firstQuantity'], "0", STR_PAD_LEFT);


        if($pictureData['firstType'] == 'X') {
            if ($this->banco['codigo_banco'] == Bancos::ITAU) {
                return str_pad(strtr(
                    utf8_decode($valueDefined),
                    utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY')
                , $pictureData['firstQuantity'], " ", STR_PAD_LEFT);

            } elseif ($this->banco['codigo_banco'] == Bancos::BANCODOBRASIL) {
                return str_pad(strtr(
                    utf8_decode($valueDefined),
                    utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY')
                , $pictureData['firstQuantity'], " ", STR_PAD_RIGHT);
            }
        }
    }

    /**
     * @throws \Exception
     */
    private function makeHeaderArquivo()
    {
        if(!is_array($this->header_arquivo))
            throw new LayoutException("Header do Arquivo inválido.");

        foreach($this->header_arquivo as $nameField => $fieldData) {
            $arrayKeys = array_keys($this->header_arquivo);
            $lastField = end($arrayKeys) == $nameField?true:false;

            $this->content .= $this->makeField($fieldData, $nameField, $lastField);

            $message = "O Campo {$nameField} deve conter caracteres neste padrão: {$fieldData['picture']}";
            if(strlen($this->content) > $fieldData['pos'][1])
                throw new LayoutException($message);
        }
        unset($nameField, $fieldData, $arrayKeys, $lastField);
        $this->content .= "\r\n";
        $this->control_arquivo++;
    }

    /**
     * @throws \Exception
     */
    private function makeHeaderLote()
    {
        if(!is_array($this->header_lote))
            throw new LayoutException("Header inválido.");

        $header_lote = null;
        foreach($this->header_lote as $nameField => $fieldData) {
            $arrayKeys = array_keys($this->header_lote);
            $lastField = end($arrayKeys) == $nameField?true:false;

            $header_lote .= $this->makeField($fieldData, $nameField, $lastField);

            $message = "O Campo {$nameField} deve conter caracteres neste padrão: {$fieldData['picture']}. makeHeaderLote";

            if(strlen($header_lote) > $fieldData['pos'][1])
                throw new LayoutException($message);
        }
        unset($nameField, $fieldData, $arrayKeys, $lastField);
        $this->content .= $header_lote . "\r\n";
        $this->control_arquivo++;
        $this->control_lote++;

    }

    /**
     * @throws LayoutException
     */
    private function makeDetail()
    {
        // if(!array_key_exists("0", $this->detail))
        //     throw new LayoutException("Lista de detalhes está inválida.");

        foreach($this->detail as $keyDetail => $data) {
            $detail = null;

            foreach($data as $nameField => $fieldData) {

                $arrayKeys = array_keys($data);
                $lastField = end($arrayKeys) == $nameField?true:false;

                if(($nameField == 'numero_registro') || ($nameField == 'numero_registro52')) {
                    $fieldData['value'] = $this->control_lote;
                    $this->control_lote++;
                    $this->control_arquivo++;
                }


                if($nameField == 'numero_registro52') {
                    $fieldData['value'] = $this->control_lote-1;
                }

                if($nameField == 'valor_pagamento') {
                    $this->valor_total_lote = $this->valor_total_lote + $fieldData['value'];
                }

                $detail .= $this->makeField($fieldData, $nameField, $lastField);
                $message = "O Campo {$nameField} deve conter caracteres neste padrão: {$fieldData['picture']}";
                if(strlen($detail) > $fieldData['pos'][1]) {
                    throw new LayoutException($message);
                }

                //SE A ULTIMA POSIÇÃO FOR MAIOR QUE 240, QUEBRA LINHA E JOGA RESTANTE DOS DADOS PARA BAIXO
                if($fieldData['pos'][1] >= 240) {
                    $this->content .= $detail . "\r\n";
                    $detail = null;
                }



            }
            unset($nameField, $fieldData, $arrayKeys, $lastField);
            $this->content .= $detail;
        }
    }

    /**
     * @throws LayoutException
     */
    private function makeTrailerLote()
    {
        if(!is_array($this->trailer_lote))
            throw new LayoutException("Trailer do Lote inválido.");

        $trailer_lote = null;
        foreach($this->trailer_lote as $nameField => $fieldData) {
            $arrayKeys = array_keys($this->trailer_lote);
            $lastField = end($arrayKeys) == $nameField?true:false;

            if($nameField == 'total_qtd_registros') {
                $fieldData['value'] = $this->control_lote+1;
            }

            if($nameField == 'total_valor_pagtos') {
                $fieldData['value'] = $this->valor_total_lote;
            }

            $trailer_lote .= $this->makeField($fieldData, $nameField, $lastField);

            if(strlen($trailer_lote) > $fieldData['pos'][1])
                throw new LayoutException("O Campo {$nameField} deve conter caracteres neste padrão: {$fieldData['picture']}");
        }
        unset($nameField, $fieldData);
        $this->content .= $trailer_lote . "\r\n";
        $this->control_lote = 0;
        $this->valor_total_lote = 0;
        $this->control_arquivo++;
    }

    /**
     * @throws LayoutException
     */
    private function makeTrailerArquivo()
    {
        if(!is_array($this->trailer_arquivo))
            throw new LayoutException("Trailer do Arquivo inválido.");

        $trailer_arquivo = null;
        foreach($this->trailer_arquivo as $nameField => $fieldData) {
            $arrayKeys = array_keys($this->trailer_arquivo);
            $lastField = end($arrayKeys) == $nameField?true:false;

            if($nameField == 'total_qtd_registros') {
                $fieldData['value'] = $this->control_arquivo+1;
            }

            $trailer_arquivo .= $this->makeField($fieldData, $nameField, $lastField);

            if(strlen($trailer_arquivo) > $fieldData['pos'][1])
                throw new LayoutException("O Campo {$nameField} deve conter caracteres neste padrão: {$fieldData['picture']}");
        }
        unset($nameField, $fieldData);
        if ($this->banco['codigo_banco'] == Bancos::ITAU)
            $this->content .= $trailer_arquivo."\r\n\r\n";
        elseif($this->banco['codigo_banco'] == Bancos::BANCODOBRASIL)
            $this->content .= $trailer_arquivo."\r\n";
    }

    /**
     * @throws \Exception
     */
    public function generateFile()
    {
        $this->makeHeaderArquivo();
        $this->makeHeaderLote();
        $this->makeDetail();
        $this->makeTrailerLote();
        $this->makeTrailerArquivo();

        try {
            $file = new File($this->content, '.txt');
            $file->delete = false;
        } catch(\Exception $e) {
            throw new CNAB240PagamentoException("Não foi possível baixar o arquivo.");
        }

        return $file->getFileName();
    }
}