<?php

namespace Ewersonfc\CNAB240Pagamento\Constants;

class TipoRegistro
{
    // O Tipo de Registro é o caracter 8 da linha (index 7)
    const HEADER_ARQUIVO = 0;
    const HEADER_LOTE = 1;
    const DETALHE = 3;
    const TRAILER_LOTE = 5;
    const TRAILER_ARQUIVO = 9;
}