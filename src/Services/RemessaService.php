<?php
/**
 * Created by PhpStorm.
 * User: ewerson
 * Date: 02/08/18
 * Time: 10:51
 */
namespace Ewersonfc\CNAB240Pagamento\Services;

use Ewersonfc\CNAB240Pagamento\Constants\TipoTransacao;
use Ewersonfc\CNAB240Pagamento\Entities\DataFile;
use Ewersonfc\CNAB240Pagamento\Exceptions\CNAB240PagamentoException;
use Ewersonfc\CNAB240Pagamento\Exceptions\LayoutException;
use Ewersonfc\CNAB240Pagamento\Factories\RemessaFactory;
use Ewersonfc\CNAB240Pagamento\Format\Yaml;

/**
 * Class ServiceRemessa
 * @package Ewersonfc\CNAB240Pagamento\Services
 */
class RemessaService
{
    /**
     * @var
     */
    private $banco;

    /**
     * @var
     */
    private $yaml;

    /**
     * ServiceRemessa constructor.
     */
    function __construct($banco)
    {
        $this->banco = $banco;
        $this->yaml = new Yaml($this->banco['path_remessa']);
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

    /**
     * @return mixed
     * @throws \Ewersonfc\CNAB240Pagamento\Exceptions\HeaderYamlException
     * @throws \Ewersonfc\CNAB240Pagamento\Exceptions\LayoutException
     */
    private function readDetailYml($typeLayout)
    {
        return $this->yaml->readDetail($typeLayout);
    }

    /**
     * @return mixed
     * @throws \Ewersonfc\CNAB240Pagamento\Exceptions\HeaderYamlException
     */
    private function readTrailerLoteYml()
    {
        return $this->yaml->readTrailerLote();
    }

    /**
     * @return mixed
     * @throws \Ewersonfc\CNAB240Pagamento\Exceptions\HeaderYamlException
     */
    private function readTrailerArquivoYml()
    {
        return $this->yaml->readTrailerArquivo();
    }

    /**
     * @return array
     */
    private function typeOfPayments()
    {
        return [
            TipoTransacao::BOLETO,
            TipoTransacao::TRANSFERENCIA,
        ];
    }

    /**
     * @param array $headerArquivoYmlStructure
     * @param DataFile $dataFile
     * @return array
     * @throws CNAB240PagamentoException
     */
    private function matchHeaderArquivoFileAndHeaderArquivoData(DataFile $dataFile)
    {
        if(empty($dataFile->header_arquivo))
            throw new CNAB240PagamentoException();

        $ymlHeaderArquivoToArray = $this->readHeaderArquivoYml();

        foreach($dataFile->header_arquivo as $key => $headerArquivoData) {
            $messageErro = "Chave passada no Header do Arquivo [array] difere do arquivo de configuração yml: {$key}";
            if(!array_key_exists($key, $ymlHeaderArquivoToArray))
                throw new CNAB240PagamentoException($messageErro);

            $ymlHeaderArquivoToArray[$key]['value'] = $headerArquivoData;
        }

        return $ymlHeaderArquivoToArray;
    }

    /**
     * @param array $headerLoteYmlStructure
     * @param DataFile $dataFile
     * @return array
     * @throws CNAB240PagamentoException
     */
    private function matchHeaderLoteFileAndHeaderLoteData(DataFile $dataFile)
    {
        if(empty($dataFile->header_lote))
            throw new CNAB240PagamentoException();

        $ymlHeaderLoteToArray = $this->readHeaderLoteYml();

        foreach($dataFile->header_lote as $key => $headerLoteData) {
            $messageErro = "Chave passada no Header do Lote [array] difere do arquivo de configuração yml: {$key}";
            if(!array_key_exists($key, $ymlHeaderLoteToArray))
                throw new CNAB240PagamentoException($messageErro);

            $ymlHeaderLoteToArray[$key]['value'] = $headerLoteData;
        }

        return $ymlHeaderLoteToArray;
    }

    /**
     * @param DataFile $dataFile
     * @return array
     * @throws CNAB240PagamentoException
     * @throws LayoutException
     * @throws \Ewersonfc\CNAB240Pagamento\Exceptions\HeaderYamlException
     */
    private function matchDetailFileAndDetailData(DataFile $dataFile)
    {
        if(!array_key_exists("0", $dataFile->detail))
            throw new CNAB240PagamentoException("O array de detalhes está inválido, consulte a documentação.");

        $detailMadeByYmlStructure = [];

        foreach($dataFile->detail as $key => $data) {
            /**
             * Load layout of detail
             */
            
            $tipo_transacao = $data['tipo_transacao'];

            if(!in_array($data['tipo_transacao'], $this->typeOfPayments()))
                throw new LayoutException("Tipo de pagamento inválido ou não informado.");

            $ymlDetailToArray = $this->readDetailYml($data['tipo_transacao']);

            /**
             * Delete key
             */
            unset($data['tipo_transacao']);

            foreach($data as $field => $value) {
                $messageErro = "Chave passada no Detail [array] difere do arquivo de configuração yml: {$field}";
                if(!array_key_exists($field, $ymlDetailToArray)) {
                    continue;
                    throw new CNAB240PagamentoException($messageErro);
                }

                $ymlDetailToArray[$field]['value'] = $value;
            }
            $detailMadeByYmlStructure[$key] = $ymlDetailToArray;

            //BANCO ITAU NECESSITA DETALHE EM 2 LINHAS (DETALHE J E J52)
            //AQUI FAREMOS A CHAMADA PARA A SEGUNDA LINHA J52
            //É USADO O MESMO ARRAY POIS HÁ VÁRIOS CAMPOS IGUAIS

            if($tipo_transacao == TipoTransacao::BOLETO && $this->banco['codigo_banco'] == 341) {
                $ymlDetailToArray52 = $this->readDetailYml(TipoTransacao::BOLETOJ52);

                foreach($data as $field => $value) {
                    $messageErro = "Chave passada no Detail [array] difere do arquivo de configuração yml: {$field}";
                    if(!array_key_exists($field, $ymlDetailToArray52)) {
                        continue;
                        throw new CNAB240PagamentoException($messageErro);
                    }

                    $ymlDetailToArray52[$field]['value'] = $value;
                }
                $detailMadeByYmlStructure[$key] = array_merge($ymlDetailToArray, $ymlDetailToArray52);
            }

        }

        return $detailMadeByYmlStructure;
    }

    private function matchTrailerLoteFileAndTrailerLoteData(DataFile $dataFile)
    {
        if(empty($dataFile->trailer_lote))
            throw new CNAB240PagamentoException();

        $ymlTrailerLoteToArray = $this->readTrailerLoteYml();

        foreach($dataFile->trailer_lote as $key => $trailerLoteData) {
            $messageErro = "Chave passada no Trailer do Lote [array] difere do arquivo de configuração yml: {$key}";
            if(!array_key_exists($key, $ymlTrailerLoteToArray))
                throw new CNAB240PagamentoException($messageErro);

            $ymlTrailerLoteToArray[$key]['value'] = $trailerLoteData;
        }
        return $ymlTrailerLoteToArray;
    }

    private function matchTrailerArquivoFileAndTrailerArquivoData(DataFile $dataFile)
    {
        if(empty($dataFile->trailer_arquivo))
            throw new CNAB240PagamentoException();

        $ymlTrailerArquivoToArray = $this->readTrailerArquivoYml();

        foreach($dataFile->trailer_arquivo as $key => $trailerArquivoData) {
            $messageErro = "Chave passada no Trailer do Arquivo [array] difere do arquivo de configuração yml: {$key}";
            if(!array_key_exists($key, $ymlTrailerArquivoToArray))
                throw new CNAB240PagamentoException($messageErro);

            $ymlTrailerArquivoToArray[$key]['value'] = $trailerArquivoData;
        }
        return $ymlTrailerArquivoToArray;
    }

    /**
     * @param DataFile $dataFile
     * @return string
     * @throws CNAB240PagamentoException
     * @throws \Ewersonfc\CNAB240Pagamento\Exceptions\HeaderYamlException
     * @throws \Ewersonfc\CNAB240Pagamento\Exceptions\LayoutException
     */
    final public function makeFile(DataFile $dataFile)
    {
        $matchHeaderArquivo = $this->matchHeaderArquivoFileAndHeaderArquivoData($dataFile);
        $matchHeaderLote = $this->matchHeaderLoteFileAndHeaderLoteData($dataFile);
        $matchDetail = $this->matchDetailFileAndDetailData($dataFile);
        $matchTrailerLote = $this->matchTrailerLoteFileAndTrailerLoteData($dataFile);
        $matchTrailerArquivo = $this->matchTrailerArquivoFileAndTrailerArquivoData($dataFile);

        $remessaFactory = new RemessaFactory(
            $matchHeaderArquivo, 
            $matchHeaderLote, 
            $matchDetail, 
            $matchTrailerLote,
            $matchTrailerArquivo);
        return $remessaFactory->generateFile();
    }
}
