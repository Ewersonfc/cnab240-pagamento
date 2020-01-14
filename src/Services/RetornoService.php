<?php
/**
 * Created by PhpStorm.
 * User: ewerson
 * Date: 29/08/18
 * Time: 14:43
 */

namespace Ewersonfc\CNAB240Pagamento\Services;

use Ewersonfc\CNAB240Pagamento\Bancos;
use Ewersonfc\CNAB240Pagamento\Constants\TipoRegistro;
use Ewersonfc\CNAB240Pagamento\Entities\DataFile;
use Ewersonfc\CNAB240Pagamento\Entities\LoteEntity;
use Ewersonfc\CNAB240Pagamento\Exceptions\FileRetornoException;
use Ewersonfc\CNAB240Pagamento\Factories\RetornoFactory;
use Ewersonfc\CNAB240Pagamento\Format\Yaml;
use Ewersonfc\CNAB240Pagamento\Format\YamlRetorno;
use Ewersonfc\CNAB240Pagamento\Helpers\ItauMatchDataHelper;

/**
 * Class ServicoRetorno
 * @package Ewersonfc\CNAB240Pagamento\Services
 */
class RetornoService
{
    private $dataFile;
    private $banco;
    private $retornoFactory;
    private $matchHelper;

    function __construct($banco)
    {
        $this->banco = $banco;
        $this->yaml = new YamlRetorno($this->banco['path_retorno']);
        $this->retornoFactory = new RetornoFactory();
        $this->matchHelper = new ItauMatchDataHelper();
    }

    private function readFileData($filePath)
    {
        try {
            $data = explode("\n", file_get_contents($filePath));
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

    private function formatFromYml($line, $yml)
    {
        $result = [];
        foreach($yml as $key => $field )
        {
            $result[$key] = $this->makefield($line, $field);
        }
        return $result;
    }

    private function getLotesArray()
    {
        $lotes = [];
        $lote = [];
        foreach ($this->dataFile as $line) {
            $lineType = ItauMatchDataHelper::getTipoRegistroFromString($line);
            $lote[] = $line;

            if ($lineType == TipoRegistro::TRAILER_LOTE) {
                $lotes[] = $lote;
                $lote = [];
            }
        }
        return $lotes;
    }

    final public function readFile($filePath)
    {
        $this->readFileData($filePath);

        $header_arquivo  = array_shift($this->dataFile);
        $trailer_arquivo = array_pop($this->dataFile);
        $lotes = $this->getLotesArray();

        $retorno = new DataFile;

        $retorno->header_arquivo = $this->formatFromYml( $header_arquivo, $this->yaml->readHeaderArquivo());
        $retorno->trailer_arquivo = $this->formatFromYml( $trailer_arquivo, $this->yaml->readTrailerArquivo());

        foreach ($lotes  as $lote){
            $header_lote = array_shift($lote);
            $trailer_lote = array_pop($lote);
            $layout_type = $this->matchHelper->getLayoutTypeFromString($lote[0]);

            $loteEntity = new LoteEntity();

            $loteEntity->header_lote = $this->formatFromYml($header_lote, $this->yaml->readHeaderLote($layout_type));

            $loteEntity->trailer_lote = $this->formatFromYml($trailer_lote, $this->yaml->readTrailerLote($layout_type));

            $this->retornoFactory->setHeader($loteEntity->header_lote);

               $segment_detail = $this->matchHelper->getSegmentoFromString($lote[0]);
               $detail = [];
               foreach($lote as $item){
                   $detail[] = $this->formatFromYml($item, $this->yaml->readDetail($layout_type, $segment_detail));
               }
               $this->retornoFactory->setDetail($detail);
               $loteEntity->detail = $this->retornoFactory->makeDetail($segment_detail);

            $retorno->addLote($loteEntity);
        }

//        $retorno-> = $retornoFactory->makeSegmentoJ();

        return $retorno;

    }
}