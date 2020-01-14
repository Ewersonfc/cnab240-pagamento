<?php

namespace Ewersonfc\CNAB240Pagamento\Helpers;

use Ewersonfc\CNAB240Pagamento\Constants\TipoTransacao;

class ItauMatchDataHelper
{
    public $ocorrencias = [
        '00' => 'PAGAMENTO EFETUADO',
        'AE' => 'DATA DE PAGAMENTO ALTERADA',
        'AG' => 'NÚMERO DO LOTE INVÁLIDO',
        'AH' => 'NÚMERO SEQUENCIAL DO REGISTRO NO LOTE INVÁLIDO',
        'AI' => 'PRODUTO DEMONSTRATIVO DE PAGAMENTO NÃO CONTRATADO',
        'AJ' => 'TIPO DE MOVIMENTO INVÁLIDO',
        'AL' => 'CÓDIGO DO BANCO FAVORECIDO INVÁLIDO',
        'AM' => 'AGÊNCIA DO FAVORECIDO INVÁLIDA',
        'AN' => 'CONTA CORRENTE DO FAVORECIDO INVÁLIDA / CONTA INVESTIMENTO EXTINTA EM 30/04/2011',
        'AO' => 'NOME DO FAVORECIDO INVÁLIDO',
        'AP' => 'DATA DE PAGAMENTO / DATA DE VALIDADE / HORA DE LANÇAMENTO /ARRECADAÇÃO / APURAÇÃO INVÁLIDA',
        'AQ' => 'QUANTIDADE DE REGISTROS MAIOR QUE 999999',
        'AR' => 'VALOR ARRECADADO / LANÇAMENTO INVÁLIDO',
        'BC' => 'NOSSO NÚMERO INVÁLIDO',
        'BD' => 'PAGAMENTO AGENDADO',
        'BE' => 'PAGAMENTO AGENDADO COM FORMA ALTEARADA PARA OP',
        'BI' => 'CNPJ/CPF DO BENEFICIÁRIO INVÁLIDO NO SEGMENTO J-52 ou B INVÁLIDO',
        'BL' => 'VALOR DA PARCELA INVÁLIDO',
        'CD' => 'CNPJ / CPF INFORMADO DIVERGENTE DO CADASTRADO',
        'CE' => 'PAGAMENTO CANCELADO',
        'CF' => 'VALOR DO DOCUMENTO INVÁLIDO',
        'CG' => 'VALOR DO ABATIMENTO INVÁLIDO',
        'CH' => 'VALOR DO DESCONTO INVÁLIDO',
        'CI' => 'CNPJ / CPF / IDENTIFICADOR / INSCRIÇÃO ESTADUAL / INSCRIÇÃO NO CAD / ICMS INVÁLIDO',
        'CJ' => 'VALOR DA MULTA INVÁLIDO',
        'CK' => 'TIPO DE INSCRIÇÃO INVÁLIDA',
        'CL' => 'VALOR DO INSS INVÁLIDO',
        'CM' => 'VALOR DO COFINS INVÁLIDO',
        'CN' => 'CONTA NÃO CADASTRADA',
        'CO' => 'VALOR DE OUTRAS ENTIDADES INVÁLIDO',
        'CP' => 'CONFIRMAÇÃO DE OP CUMPRIDA',
        'CQ' => 'SOMA DAS FATURAS DIFERE DO PAGAMENTO',
        'CR' => 'VALOR DO CSLL INVÁLIDO',
        'CS' => 'DATA DE VENCIMENTO DA FATURA INVÁLIDA',
        'DA' => 'NÚMERO DE DEPEND. SALÁRIO FAMILIA INVALIDO',
        'DB' => 'NÚMERO DE HORAS SEMANAIS INVÁLIDO',
        'DC' => 'SALÁRIO DE CONTRIBUIÇÃO INSS INVÁLIDO',
        'DD' => 'SALÁRIO DE CONTRIBUIÇÃO FGTS INVÁLIDO',
        'DE' => 'VALOR TOTAL DOS PROVENTOS INVÁLIDO',
        'DF' => 'VALOR TOTAL DOS DESCONTOS INVÁLIDO',
        'DG' => 'VALOR LÍQUIDO NÃO NUMÉRICO',
        'DH' => 'VALOR LIQ. INFORMADO DIFERE DO CALCULADO',
        'DI' => 'VALOR DO SALÁRIO-BASE INVÁLIDO',
        'DJ' => 'BASE DE CÁLCULO IRRF INVÁLIDA',
        'DK' => 'BASE DE CÁLCULO FGTS INVÁLIDA',
        'DL' => 'FORMA DE PAGAMENTO INCOMPATÍVEL COM HOLERITE',
        'DM' => 'E-MAIL DO FAVORECIDO INVÁLIDO',
        'DV' => 'DOC / TED DEVOLVIDO PELO BANCO FAVORECIDO',
        'D0' => 'FINALIDADE DO HOLERITE INVÁLIDA',
        'D1' => 'MÊS DE COMPETENCIA DO HOLERITE INVÁLIDA',
        'D2' => 'DIA DA COMPETENCIA DO HOLETITE INVÁLIDA',
        'D3' => 'CENTRO DE CUSTO INVÁLIDO',
        'D4' => 'CAMPO NUMÉRICO DA FUNCIONAL INVÁLIDO',
        'D5' => 'DATA INÍCIO DE FÉRIAS NÃO NUMÉRICA',
        'D6' => 'DATA INÍCIO DE FÉRIAS INCONSISTENTE',
        'D7' => 'DATA FIM DE FÉRIAS NÃO NUMÉRICO',
        'D8' => 'DATA FIM DE FÉRIAS INCONSISTENTE',
        'D9' => 'NÚMERO DE DEPENDENTES IR INVÁLIDO',
        'EM' => 'CONFIRMAÇÃO DE OP EMITIDA',
        'EX' => 'DEVOLUÇÃO DE OP NÃO SACADA PELO FAVORECIDO',
        'E0' => 'TIPO DE MOVIMENTO HOLERITE INVÁLIDO',
        'E1' => 'VALOR 01 DO HOLERITE / INFORME INVÁLIDO',
        'E2' => 'VALOR 02 DO HOLERITE / INFORME INVÁLIDO',
        'E3' => 'VALOR 03 DO HOLERITE / INFORME INVÁLIDO',
        'E4' => 'VALOR 04 DO HOLERITE / INFORME INVÁLIDO',
        'FC' => 'PAGAMENTO EFETUADO ATRAVÉS DE FINANCIAMENTO COMPROR',
        'FD' => 'PAGAMENTO EFETUADO ATRAVÉS DE FINANCIAMENTO DESCOMPROR',
        'HA' => 'ERRO NO HEADER DE ARQUIVO',
        'HM' => 'ERRO NO HEADER DE LOTE',
        'IB' => 'VALOR E/OU DATA DO DOCUMENTO INVÁLIDO',
        'IC' => 'VALOR DO ABATIMENTO INVÁLIDO',
        'ID' => 'VALOR DO DESCONTO INVÁLIDO',
        'IE' => 'VALOR DA MORA INVÁLIDO',
        'IF' => 'VALOR DA MULTA INVÁLIDO',
        'IG' => 'VALOR DA DEDUÇÃO INVÁLIDO',
        'IH' => 'VALOR DO ACRÉSCIMO INVÁLIDO',
        'II' => 'DATA DE VENCIMENTO INVÁLIDA',
        'IJ' => 'COMPETÊNCIA / PERÍODO REFERÊNCIA / PARCELA INVÁLIDA',
        'IK' => 'TRIBUTO NÃO LIQUIDÁVEL VIA SISPAG OU NÃO CONVENIADO COM ITAÚ',
        'IL' => 'CÓDIGO DE PAGAMENTO / EMPRESA /RECEITA INVÁLIDO',
        'IM' => 'TIPO X FORMA NÃO COMPATÍVEL',
        'IN' => 'BANCO/AGENCIA NÃO CADASTRADOS',
        'IO' => 'DAC / VALOR / COMPETÊNCIA / IDENTIFICADOR DO LACRE INVÁLIDO',
        'IP' => 'DAC DO CÓDIGO DE BARRAS INVÁLIDO',
        'IQ' => 'DÍVIDA ATIVA OU NÚMERO DE ETIQUETA INVÁLIDO',
        'IR' => 'PAGAMENTO ALTERADO',
        'IS' => 'CONCESSIONÁRIA NÃO CONVENIADA COM ITAÚ',
        'IT' => 'VALOR DO TRIBUTO INVÁLIDO',
        'IU' => 'VALOR DA RECEITA BRUTA ACUMULADA INVÁLIDO',
        'IV' => 'NÚMERO DO DOCUMENTO ORIGEM / REFERÊNCIA INVÁLIDO',
        'IX' => 'CÓDIGO DO PRODUTO INVÁLIDO',
        'LA' => 'DATA DE PAGAMENTO DE UM LOTE ALTERADA',
        'LC' => 'LOTE DE PAGAMENTOS CANCELADO',
        'NA' => 'PAGAMENTO CANCELADO POR FALTA DE AUTORIZAÇÃO',
        'NB' => 'IDENTIFICAÇÃO DO TRIBUTO INVÁLIDA',
        'NC' => 'EXERCÍCIO (ANO BASE) INVÁLIDO',
        'ND' => 'CÓDIGO RENAVAM NÃO ENCONTRADO/INVÁLIDO',
        'NE' => 'UF INVÁLIDA',
        'NF' => 'CÓDIGO DO MUNICÍPIO INVÁLIDO',
        'NG' => 'PLACA INVÁLIDA',
        'NH' => 'OPÇÃO/PARCELA DE PAGAMENTO INVÁLIDA',
        'NI' => 'TRIBUTO JÁ FOI PAGO OU ESTÁ VENCIDO',
        'NR' => 'OPERAÇÃO NÃO REALIZADA',
        'PD' => 'AQUISIÇÃO CONFIRMADA (EQUIVALE A OCORRÊNCIA 02 NO LAYOUT DE RISCO SACADO)',
        'RJ' => 'REGISTRO REJEITADO',
        'RS' => 'PAGAMENTO DISPONÍVEL PARA ANTECIPAÇÃO NO RISCO SACADO – MODALIDADE RISCO SACADO PÓS AUTORIZADO',
        'SS' => 'PAGAMENTO CANCELADO POR INSUFICIÊNCIA DE SALDO/LIMITE DIÁRIO DE PAGTO',
        'TA' => 'LOTE NÃO ACEITO - TOTAIS DO LOTE COM DIFERENÇA',
        'TI' => 'TITULARIDADE INVÁLIDA',
        'X1' => 'FORMA INCOMPATÍVEL COM LAYOUT 010',
        'X2' => 'NÚMERO DA NOTA FISCAL INVÁLIDO',
        'X3' => 'IDENTIFICADOR DE NF/CNPJ INVÁLIDO',
        'X4' => 'FORMA 32 INVÁLIDA',
    ];

    public static function getTipoRegistroFromString($line)
    {
        return $line[7];
    }

    public static function matchArquivo($line)
    {
        $segmento = strtoupper(self::getSegmentoFromString($line));
        $codigoRegistro = self::getCodigoRegistroFromString($line);
        if ($segmento == 'J') {
            if ($codigoRegistro == '52') {
                return TipoTransacao::BOLETOJ52;
            }
            return TipoTransacao::BOLETO;
        } else if ($segmento == 'A') {
            return TipoTransacao::TRANSFERENCIA;
        }
    }

    public static function getLayoutTypeFromString($detail_line)
    {
        if(self::getSegmentoFromString($detail_line) == 'A'){
            return '040';
        }else{
            return '030';
        }
    }

    public static function getSegmentoFromString($detail_line)
    {
        return $detail_line[13];
    }

    public static function getCodigoRegistroFromString($detail_line)
    {
        return $detail_line[17] . $detail_line[18];
    }

}