<?php

namespace Ewersonfc\CNAB240Pagamento\Format;

use Ehtl\Model\TipoPagamento;
use Ewersonfc\CNAB240Pagamento\Exceptions\HeaderYamlException;
use Ewersonfc\CNAB240Pagamento\Exceptions\LayoutException;
use Ewersonfc\CNAB240Pagamento\Helpers\CNAB240Helper;

class YamlRetorno extends \Symfony\Component\Yaml\Yaml
{
    private $path;
    private $fields;

    function __construct($path)
    {
        $this->path = $path;
    }

    public function readHeaderArquivo()
    {
        $filename = "{$this->path}/header_arquivo.yml";
        return $this->validateFile($filename);
    }

    private function validateFile($filename)
    {
        if (!file_exists($filename))
            throw new HeaderYamlException("Arquivo de configuração não encontrado em: {$this->path}/{$filename}");

        $this->fields = $this->parse(file_get_contents($filename));
        return $this->validateCollision();
    }

    private function validateCollision()
    {
        if (empty($this->fields))
            throw new LayoutException("No field found");

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
        return $this->fields;
    }

    public function readHeaderLote($layout)
    {
        $filename = "{$this->path}/{$layout}/header_lote.yml";
        return $this->validateFile($filename);
    }

    public function readDetail($layout, $segment)
    {
        $filename = "{$this->path}/{$layout}/detalhe_{$segment}.yml";
        return $this->validateFile($filename);
    }

    public function readTrailerLote($layout)
    {
        $filename = "{$this->path}/{$layout}/trailer_lote.yml";
        return $this->validateFile($filename);
    }

    public function readTrailerArquivo()
    {
        $filename = "{$this->path}/trailer_arquivo.yml";
        return $this->validateFile($filename);
    }
}