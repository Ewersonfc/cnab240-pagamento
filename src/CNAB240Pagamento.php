<?php
/**
 * User: William
 * Date: 28/11/2018
 * Time: 17:51
 */
namespace Ewersonfc\CNAB240Pagamento;

use Ewersonfc\CNAB240Pagamento\Constants\TipoRetorno;
use Ewersonfc\CNAB240Pagamento\Entities\DataFile;
use Ewersonfc\CNAB240Pagamento\Exceptions\FileRetornoException;
use Ewersonfc\CNAB240Pagamento\Services\ServiceRemessa;
use Ewersonfc\CNAB240Pagamento\Services\ServiceRetorno;

/**
 * Class CNAB240Pagamento
 */
class CNAB240Pagamento
{
    /**
     * @var ServiceRemessa
     */
    private $serviceRemessa;

    /**
     * @var ServiceRetorno
     */
    private $serviceRetorno;

    /**
     * CNAB240Pagamento constructor.
     * @param $banco
     * @throws Exceptions\CNAB240PagamentoException
     */
    function __construct($banco)
    {
        $this->serviceRemessa = new ServiceRemessa(Bancos::getBankData($banco));
        $this->serviceRetorno = new ServiceRetorno(Bancos::getBankData($banco));
    }

    /**
     * @param DataFile $dataFile
     * @return string
     * @throws Exceptions\CNAB240PagamentoException
     * @throws Exceptions\HeaderYamlException
     * @throws Exceptions\LayoutException
     */
    public function gerarArquivo(DataFile $dataFile)
    {
        $file = $this->serviceRemessa->makeFile($dataFile);
        return json_encode([
            'file' => $file,
        ]);
    }

    /**
     * @param $archivePath
     * @param $tipoRetorno
     * @return Factories\DataFile
     * @throws FileRetornoException
     */
    public function processarRetorno($archivePath)
    {
        $data = $this->serviceRetorno->readFile($archivePath);
        return $data;
    }
}