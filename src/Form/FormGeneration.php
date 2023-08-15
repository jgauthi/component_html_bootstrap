<?php
/*****************************************************************************************************
 * @name FormGeneration
 * @note: Form generation via Bootstrap
 * @author: Jgauthi, created at [28nov2019], url: <github.com/jgauthi/component_html_bootstrap>
 * @Requirements:
    - Bootstrap 5: https://getbootstrap.com/docs/5.3/components/forms/
    - (optional) Symfony Yaml Component: https://symfony.com/doc/current/components/yaml.html

 ******************************************************************************************************/
namespace Jgauthi\Component\Bootstrap\Form;

use Exception;

class FormGeneration
{
    public function __construct(private Fields $fields) { }

    /**
     * @throws Exception
     */
    public function getFieldsByArray(array $data): string
    {
        if (empty($data)) {
            throw new Exception('Invalid argument: $data is empty or not an array');
        }

        $html = [];
        foreach ($data as $name => $args) {
            if (empty($args['type']) || !method_exists($this->fields, $args['type'])) {
                throw new Exception("Invalid form type fields: {$args['type']}");
            }

            $type = $args['type'];
            $label = ((isset($args['label'])) ? $args['label'] : null);
            $attributes = ((isset($args['attributes'])) ? $args['attributes'] : []);

            switch ($type) {
                case 'checkbox':
                    $value = ((isset($args['value'])) ? $args['value'] : 1);
                    $html[] = $this->fields->checkbox($name, $label, $value, $attributes);
                    break;

                case 'select':
                case 'checkboxes':
                case 'radio':
                    $values = ((isset($args['values'])) ? $args['values'] : []);
                    $html[] = $this->fields->$type($name, $label, $values, $attributes);
                    break;

                case 'submit':
                case 'reset':
                case 'button':
                    $html[] = $this->fields->$type($label, $attributes);
                    break;

                case 'hidden':
                    $value = ((isset($args['value'])) ? $args['value'] : 1);
                    $html[] = $this->fields->hidden($name, $value, $attributes);
                    break;

                default:
                    $html[] = $this->fields->$type($name, $label, $attributes);
                    break;
            }
        }

        return implode(PHP_EOL, $html);
    }

    /**
     * @throws Exception
     */
    public function makeFormByArray(array $data, array $formAttributes = []): string
    {
        $default = [
            'action' => $_SERVER['PHP_SELF'],
            'method' => 'POST',
            'enctype' => 'multipart/form-data',
        ];
        $formAttributes = array_replace_recursive($default, $formAttributes);
        $formAttributes = Fields::attributes($formAttributes);

        $data[] = ['type' => 'submit', 'label' => 'Valider'];
        $html = "<form {$formAttributes}>{$this->getFieldsByArray($data)}</form>";

        return $html;
    }

    /**
     * @throws Exception
     */
    public function makeFormByYaml(string $yamlFile): ?string
    {
        if (!class_exists('Symfony\Component\Yaml\Yaml')) {
            throw new Exception('Yaml component not installed.');
        }

        try {
            $yaml = \Symfony\Component\Yaml\Yaml::parseFile($yamlFile);
            $data = $yaml['form']['fields'];

            $formAttributes = [];
            if (isset($yaml['form']['url'])) {
                $formAttributes['action'] = $yaml['form']['url'];
            }
            if (isset($yaml['form']['method'])) {
                $formAttributes['method'] = $yaml['form']['method'];
            }
            if (isset($yaml['form']['enctype'])) {
                $formAttributes['enctype'] = $yaml['form']['enctype'];
            }
        } catch (Exception $exception) {
            printf('Unable to parse the YAML string: %s', $exception->getMessage());
            return null;
        }

        return $this->makeFormByArray($data, $formAttributes);
    }
}
