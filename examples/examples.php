<?php

require '../vendor/autoload.php';

use Ewersonfc\CNAB240Pagamento\CNAB240Pagamento;
use Ewersonfc\CNAB240Pagamento\Bancos;
use Ewersonfc\CNAB240Pagamento\Entities\DataFile;
use Ewersonfc\CNAB240Pagamento\Helpers\Helper;

$cda_lote = 32;

$header_arquivo = [
   'inscricao_numero' => '12345678901234',
   'agencia' => '12345',
   'conta' => '00000225',
   'dac' => '2',
   'nome_empresa' => 'EHTL',
   'nome_banco' => 'ITAU',
   'arquivo_codigo' => '1',
   'data_geracao' => '29112018', // (20/06/2018) OPCIONAL
   'hora_geracao' => '120000'
];

$header_lote = [
   'codigo_lote' => $cda_lote, // ESSE SERIA O CONTADOR???
   'tipo_pagamento' => '20',
   'forma_pagamento' => '31',
   'inscricao_numero' => '12345678901234',
   'agencia' => '12345',
   'conta' => '00000225',
   'dac' => '2',
   'nome_empresa' => 'EHTL',
   'endereco_empresa' => 'Av. Ipiranga',
   'numero' => '144',
   'complemento' => '4 andar',
   'cidade' => 'Sao Paulo',
   'cep' => '01044000',
   'estado' => 'SP',
];

$detail = [];

$codigo_barras = "00191761000000113330000003071378005071620317";




$detail[] = 
[
   'tipo_transacao' => 'boleto',
   'codigo_lote'=>$cda_lote, //DEVE SER IGUAL DO DO HEADER_LOTE
   'tipo_movimento' => '000',
   //NECESSARIO EXTRAIR A LINHA DIGITÁVEL DO BD, CONVERTE-LA EM COD DE BARRAS E QUEBRAS NOS CAMPOS ABAIXO
   'banco_favorecido' => '000',
   'moeda' => '0',
   'dv' => '0',
   'vencimento' => '000',
   'valor' => '000',
   'campo_livre' => '000',
   'nome_favorecido' => 'William Neves da Silva',
   'data_vencto' => '29112018',
   'valor_titulo' => Helper::valueToNumber(100.00), // irá preencher com 0 à esquerda
   'descontos' => Helper::valueToNumber(20.00), // // irá preencher com 0 à esquerda
   'acrescimos' => Helper::valueToNumber(5.00), // informar se houver | irá preencher com 0 à esquerda
   'data_pagamento' => '29112018', // data que deseja liquidar o título
   'valor_pagamento' => Helper::valueToNumber(85.00), // Valor total - abatimento + juros se houver
   'seu_numero' => 45461, // CDA identificador da linha de remessa_pagamento
];


$detail[] = 
[
   'tipo_transacao' => 'boleto',
   'codigo_lote'=>$cda_lote, //DEVE SER IGUAL DO DO HEADER_LOTE
   'tipo_movimento' => '000',
   //NECESSARIO EXTRAIR A LINHA DIGITÁVEL DO BD, CONVERTE-LA EM COD DE BARRAS E QUEBRAS NOS CAMPOS ABAIXO
   'banco_favorecido' => '000',
   'moeda' => '0',
   'dv' => '0',
   'vencimento' => '000',
   'valor' => '000',
   'campo_livre' => '000',
   'nome_favorecido' => 'José da Silva',
   'data_vencto' => '29112018',
   'valor_titulo' => Helper::valueToNumber(4540.00), // irá preencher com 0 à esquerda
   'descontos' => Helper::valueToNumber(0.00), // // irá preencher com 0 à esquerda
   'acrescimos' => Helper::valueToNumber(15.00), // informar se houver | irá preencher com 0 à esquerda
   'data_pagamento' => '29112018', // data que deseja liquidar o título
   'valor_pagamento' => Helper::valueToNumber(45465.00), // Valor total - abatimento + juros se houver
   'seu_numero' => 45461, // CDA identificador da linha de remessa_pagamento
];


$detail[] = 
[
   'tipo_transacao' => 'boleto',
   'codigo_lote'=>$cda_lote, //DEVE SER IGUAL DO DO HEADER_LOTE
   'tipo_movimento' => '000',
   //NECESSARIO EXTRAIR A LINHA DIGITÁVEL DO BD, CONVERTE-LA EM COD DE BARRAS E QUEBRAS NOS CAMPOS ABAIXO
   'banco_favorecido' => '000',
   'moeda' => '0',
   'dv' => '0',
   'vencimento' => '000',
   'valor' => '000',
   'campo_livre' => '000',
   'nome_favorecido' => 'Roberto da Silva',
   'data_vencto' => '29112018',
   'valor_titulo' => Helper::valueToNumber(4545.32), // irá preencher com 0 à esquerda
   'descontos' => Helper::valueToNumber(20.00), // // irá preencher com 0 à esquerda
   'acrescimos' => Helper::valueToNumber(5.00), // informar se houver | irá preencher com 0 à esquerda
   'data_pagamento' => '29112018', // data que deseja liquidar o título
   'valor_pagamento' => Helper::valueToNumber(99876.32), // Valor total - abatimento + juros se houver
   'seu_numero' => 45461, // CDA identificador da linha de remessa_pagamento
];



$trailer_lote = [
   'codigo_lote'=>$cda_lote, //DEVE SER IGUAL DO DO HEADER_LOTE
   'total_qtd_registros' => 1, //QUANTIDADE DE REGISTROS NO LOTE
   'total_valor_pagtos' => Helper::valueToNumber(85.00),
];

$trailer_arquivo = [
   'total_qtd_lotes' => 1, //QUANTIDADE DE LOTES NO ARQUIVOS (INICIALMENTE SERÁ APENAS 1)
   'total_qtd_registros' => 1 
];

$datafile = new DataFile;
$datafile->header_arquivo = $header_arquivo;
$datafile->header_lote = $header_lote;
$datafile->detail = $detail;
$datafile->trailer_lote = $trailer_lote;
$datafile->trailer_arquivo = $trailer_arquivo;

$CNAB240Pagamento = new CNAB240Pagamento(Bancos::ITAU);
$file = $CNAB240Pagamento->gerarArquivo($datafile);


print_r($file);


// $cnab = new CNAB240Pagamento(\Ewersonfc\CNAB240Pagamento\Bancos::ITAU);
//$tipoRetorno = 'confirmacao_rejeicao';
//$data = $cnab->processarRetorno('/home/ewerson/Downloads/RETORNO_701EHTLRONL0042119206.txt', $tipoRetorno);
// $data = $cnab->processarRetorno('/home/ewerson/Downloads/RETORNO_701EHTLRONL0042119194.txt');
// echo '<pre>';
// print_r($data);
// echo '</pre>';
