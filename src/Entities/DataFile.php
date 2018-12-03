<?php
/**
 * Created by PhpStorm.
 * User: ewerson
 * Date: 27/07/18
 * Time: 15:12
 */
namespace Ewersonfc\CNAB240Pagamento\Entities;

/**
 * Class DataFile
 * @package Ewersonfc\CNAB240Pagamento\Entities
 */
class DataFile
{
    /**
     * @var
     */
    public $header_arquivo;

    /**
     * @var
     */
    public $header_lote;

    /**
     * @var array
     */
    public $detail = [];

    /**
     * @var
     */
    public $trailer_lote;

    /**
     * @var
     */
    public $trailer_arquivo;
}