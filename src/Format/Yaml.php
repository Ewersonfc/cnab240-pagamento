<?php
/**
 * Created by PhpStorm.
 * User: ewerson
 * Date: 02/08/18
 * Time: 12:08
 */
namespace Ewersonfc\CNAB240Pagamento\Format;

use Ehtl\Model\TipoPagamento;
use Ewersonfc\CNAB240Pagamento\Constants\TipoRetorno;
use Ewersonfc\CNAB240Pagamento\Constants\TipoTransacao;
use Ewersonfc\CNAB240Pagamento\Exceptions\HeaderYamlException;
use Ewersonfc\CNAB240Pagamento\Exceptions\LayoutException;
use Ewersonfc\CNAB240Pagamento\Helpers\CNAB240Helper;
use Ewersonfc\CNAB240Pagamento\Bancos;

/**
 * Class Yaml
 * @package Ewersonfc\CNAB240Pagamento\Format
 */
class Yaml extends \Symfony\Component\Yaml\Yaml
{
    /**
     * @var
     */
    private $path;

    /**
     * @var
     */
    private $fields;

    /**
     * Yaml constructor.
     * @param $path
     */
    function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * @return mixed
     * @throws HeaderYamlException
     * @throws LayoutException
     */
    public function readHeaderArquivo()
    {
        $filename = "{$this->path}/header_arquivo.yml";

        if (!file_exists($filename))
            throw new HeaderYamlException("Arquivo de configuração header.yml não encontrado em: $this->path");

        $this->fields = $this->parse(file_get_contents($filename));

        return $this->validateLayout();
    }

    /**
     * @return mixed
     * @throws HeaderYamlException
     * @throws LayoutException
     */
    public function readHeaderLote()
    {
        //CONFIGURADO PARA ARQUIVOS DE BOLETO
        $filename = "{$this->path}/header_lote_boleto.yml";

        if (!file_exists($filename))
            throw new HeaderYamlException("Arquivo de configuração header.yml não encontrado em: $this->path");

        $this->fields = $this->parse(file_get_contents($filename));

        return $this->validateLayout();
    }

    /**
     * @return mixed
     * @throws HeaderYamlException
     * @throws LayoutException
     */
    public function readDetail($type)
    {
        switch ($type) {
            case TipoTransacao::BOLETO:
                $filename = "{$this->path}/detalhe_boleto.yml";
                break;
            case TipoTransacao::BOLETOJ52:
                $filename = "{$this->path}/detalhe_boletoJ52.yml";
                break;
            case TipoTransacao::CHEQUE:
                $filename = "{$this->path}/detalhe_cheque.yml";
                break;
        }

        if (!file_exists($filename))
            throw new HeaderYamlException("Arquivo de configuração detail_{$type}.yml não encontrado em: $this->path");

        $this->fields = $this->parse(file_get_contents($filename));

        return $this->validateLayout();
    }

    public function readTrailerLote($banco)
    {

        if ($banco['codigo_banco'] == Bancos::ITAU) {
            $filename = "{$this->path}/trailer_lote_boleto.yml";
        } elseif ( $banco['codigo_banco'] == Bancos::BANCODOBRASIL) {
            $filename = "{$this->path}/trailer_lote.yml";
        }

        if(!file_exists($filename))
            throw new HeaderYamlException("Arquivo de configuração trailer.yml não encontrado em: $this->path");

        $this->fields = $this->parse(file_get_contents($filename));

        return $this->validateLayout();
    }

    public function readTrailerArquivo()
    {
        $filename = "{$this->path}/trailer_arquivo.yml";

        if (!file_exists($filename))
            throw new HeaderYamlException("Arquivo de configuração trailer.yml não encontrado em: $this->path");

        $this->fields = $this->parse(file_get_contents($filename));

        return $this->validateLayout();
    }

    /**
     * @return mixed
     * @throws LayoutException
     */
    private function validateLayout()
    {
        if (empty($this->fields))
            throw new LayoutException("No field found");

        $this->validateCollision();

        return $this->fields;
    }

    /**
     * @throws LayoutException
     */
    private function validateCollision()
    {
        foreach ($this->fields as $name => $field) {
            $pos_start = $field['pos'][0];
            $pos_end = $field['pos'][1];

            foreach ($this->fields as $current_name => $current_field) {
                if (!CNAB240Helper::picture($current_field['picture']))
                    throw new LayoutException("The picture of the attribute {$current_name} is invalid.");

                if ($current_name === $name)
                    continue;
                $current_pos_start = $current_field['pos'][0];
                $current_pos_end = $current_field['pos'][1];
                if (!is_numeric($current_pos_start) || !is_numeric($current_pos_end))
                    continue;
                if ($current_pos_start > $current_pos_end)
                    throw new LayoutException("In the {$current_name} field the starting position ({$current_pos_start}) must be less than or equal to the final position ({$current_pos_end})");
                if (($pos_start >= $current_pos_start && $pos_start <= $current_pos_end) || ($pos_end <= $current_pos_end && $pos_end >= $current_pos_start))
                    throw new LayoutException("The {$name} field collides with the field {$current_name}");
            }
        }
    }
}
