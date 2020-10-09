<?php
/*****************************************************************************************************
 * @name YamlToAsgardForm
 * @note: From a YAML matrix, export under other array formats to manage different libs
 * @author: Jgauthi <github.com/jgauthi>, created at [21nov2019]

 ******************************************************************************************************/
namespace Jgauthi\Component\Bootstrap\DataTransformer;

use Symfony\Component\Yaml\Yaml;

class YamlToAsgardForm
{
    private iterable $content;

    public function __construct(string $yamlFile)
    {
        $this->content = Yaml::parseFile($yamlFile);
    }

    /**
     * Export current matrice to \Jgauthi\Component\BootstrapForm\Fields
     * @return array
     */
    public function exportToBootstratForm(): array
    {
        $export = [];
        foreach ($this->content as $key => $value) {
            $export[$key] = [
                'type' 	=> $value['type'],
                'label' => $value['label'],
            ];

            if (!empty($value['attributes'])) {
                $export[$key]['attributes'] = $value['attributes'];
            }
        }

        return $export;
    }

    /**
     * Export current matrice to applis/taf/*.php
     * @return array
     */
    public function exportToSqlSearch(): array
    {
        $export = [];
        foreach ($this->content as $key => $value) {
            $export[$key] = [
                'field' 	=> "{$value['table']}.{$value['field']}",
                'method' 	=> (($value['type'] == 'text') ? 'LIKE' : '='),
            ];
        }

        return $export;
    }

    // todo: support Matrice Field ?
}