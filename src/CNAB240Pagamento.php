<?php
namespace Ewersonfc\CNAB240Pagamento;

use Ewersonfc\CNAB240Pagamento\Entities\DataFile;
use Ewersonfc\CNAB240Pagamento\Services\ParseReturnService;
use Ewersonfc\CNAB240Pagamento\Services\RemessaService;
use Ewersonfc\CNAB240Pagamento\Services\RetornoService;

class CNAB240Pagamento
{
    private $serviceRemessa;
    private $serviceRetorno;

    public function __construct($banco)
    {
        $bankData = Bancos::getBankData($banco);
        $this->serviceRemessa = new RemessaService($bankData);
        $this->serviceRetorno = new RetornoService($bankData);
    }

    public function gerarArquivo(DataFile $dataFile)
    {
        return json_encode([
            'file' => $this->serviceRemessa->makeFile($dataFile)
        ]);
    }

    public function processarRetorno($archivePath)
    {
        return $this->serviceRetorno->readFile($archivePath);
    }

}