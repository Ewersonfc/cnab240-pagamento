<?php
/**
 * Created by PhpStorm.
 * User: ewerson
 * Date: 29/08/18
 * Time: 14:43
 */

namespace Ewersonfc\CNAB240Pagamento\Services;

use Ewersonfc\CNAB240Pagamento\Bancos;
use Ewersonfc\CNAB240Pagamento\Exceptions\FileRetornoException;
use Ewersonfc\CNAB240Pagamento\Factories\RetornoFactory;
use Ewersonfc\CNAB240Pagamento\Format\Yaml;
use Ewersonfc\CNAB240Pagamento\Entities\DataFile;

/**
 * Class ServicoRetorno
 * @package Ewersonfc\CNAB240Pagamento\Services
 */
class ServiceRetorno
{
    /**
     * @var
     */
    private $filePath;

    /**
     * @var
     */
    private $dataFile;

    /**
     * @var
     */
    private $banco;

    /**
     * @var RetornoFactory
     */
    private $retornoFactory;

    /**
     * ServicoRetorno constructor.
     */
    function __construct($banco)
    {
        $this->banco = $banco;
        $this->yaml = new Yaml($this->banco['path_retorno']);
    }

    /**
     * @return mixed
     * @throws \Ewersonfc\CNAB240Pagamento\Exceptions\HeaderYamlException
     * @throws \Ewersonfc\CNAB240Pagamento\Exceptions\LayoutException
     */
    private function readHeaderArquivoYml()
    {
        return $this->yaml->readHeaderArquivo();
    }

    /**
     * @return mixed
     * @throws \Ewersonfc\CNAB240Pagamento\Exceptions\HeaderYamlException
     * @throws \Ewersonfc\CNAB240Pagamento\Exceptions\LayoutException
     */
    private function readHeaderLoteYml()
    {
        return $this->yaml->readHeaderLote();
    }

    private function readDetailYml($tipoRetorno)
    {
        $tipoArquivo = Bancos::getItauDetailType($tipoRetorno);
        return $this->yaml->readDetail($tipoArquivo);
    }

    /**
     * @return array
     * @throws FileRetornoException
     */
    private function readFileData()
    {
        try {
            $data = explode("\n", file_get_contents($this->filePath));
        } catch (\Exception $e) {
            throw new FileRetornoException();
        }
        $this->dataFile = $data;
    }

    private function makefield($string, $field)
    {
        $fieldPosition = $field['pos'][0]-1;
        $field['value'] = substr($string, $fieldPosition, $field['pos'][1]-$fieldPosition);

        return $field;
    }

    private function matchHeaderArquivoFileAndHeaderArquivoData()
    {
        $this->readFileData();

        $header_arquivo = array_shift($this->dataFile);
        $headerArquivoYml = $this->readHeaderArquivoYml();

        $headerArquivoComplete = [];
        foreach($headerArquivoYml as $key => $field )
        {
            $headerArquivoComplete[$key] = $this->makefield($header_arquivo, $field);
        }

        return $headerArquivoComplete;
    }

    private function matchHeaderLoteFileAndHeaderLoteData()
    {
        $this->readFileData();

        $header_lote = array_shift($this->dataFile);
        $headerLoteYml = $this->readHeaderLoteYml();

        $headerLoteComplete = [];
        foreach($headerLoteYml as $key => $field )
        {
            $headerLoteComplete[$key] = $this->makefield($header_lote, $field);
        }

        return $headerLoteComplete;
    }

    private function getTypeReturnByBank($detailCompletely)
    {

        //ANALISAR DIREITO ESSA FUNÇÃO - ITAU NÃO TEM UM CAMPO ESPECÍFICO PARA O TIPO DE RETORNO (CONFIRMAÇÃO, LIQUIDAÇÃO, ETC)
        //TALVEZ TENHAMOS QUE ESQUECER ESSE TIPO DE TRATAMENTO
        //POR ENQUANTO, USAREI O CAMPO DE OCORRENCIA
        switch ($this->banco['codigo_banco'])
        {
            case Bancos::ITAU:
                return $this->readDetailYml(substr($detailCompletely, 231, 240));
            break;
            default:
                throw new \Exception("Não foi possivel toma danada");
        }
    }

    private function matchDetailFileAndDetailData()
    {
        $onlyDetails = array_slice($this->dataFile, 1, count(array_filter($this->dataFile)) - 1);
        $detailComplete = [];
        foreach ($onlyDetails as $keyDetail => $detail) {
            $detailYml = $this->getTypeReturnByBank($detail);
            foreach ($detailYml as $key => $field) {
                $detailComplete[$keyDetail][$key] = $this->makefield($detail, $field);
            }
        }

        return $detailComplete;
    }

    /**
     * @param $filePath
     * @param $tipoRetorno
     * @return \Ewersonfc\CNAB240Pagamento\Factories\DataFile
     */
    final public function readFile($filePath)
    {
        $this->filePath = $filePath;

        $header_arquivo = $this->matchHeaderArquivoFileAndHeaderArquivoData();
        $header_lote = $this->matchHeaderLoteFileAndHeaderLoteData();
        $detail = $this->matchDetailFileAndDetailData();

        $retornoFactory = new RetornoFactory($header_arquivo, $header_lote, $detail);
        if($this->banco = Bancos::ITAU) {
            $retorno = new DataFile;
            $retorno->header_arquivo = $header_arquivo;
            $retorno->header_lote = $header_lote;
            $retorno->detail = $retornoFactory->generateItauResponse();

            return $retorno;
        }

        return false;
    }
}