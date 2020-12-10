<?php
/*****************************************************************************************************
 * @name Fields
 * @note: Génération de bout de code HTML de formulaire pour bootstrap 4
 * @author: Jgauthi <github.com/jgauthi>, created at [18sept2019]
 * @Requirements:
    - Bootstrap 4: https://getbootstrap.com/docs/4.1/components/forms/

 ******************************************************************************************************/
namespace Jgauthi\Component\Bootstrap\Form;

class Fields
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    //-- Internal methods for generate HTML ------------------------------------------------------------

    protected function htmltxt(string $value): string
    {
        return htmlentities($value, ENT_QUOTES, 'UTF-8');
    }

    public function view(string $inputHtml, ?string $inputId = null, ?string $label = null): string
    {
        if (null === $label) {
            return $inputHtml;
        }

        $html = <<<EOF
		<div class="form-group row">
			<label for="{$inputId}" class="col-sm-2 col-form-label">{$label}</label>
			<div class="col-sm-10">{$inputHtml}</div>
		</div>
EOF;

        return $html;
    }

    protected function get(string $name): ?string
    {
        if (empty($this->data[$name])) {
            return null;
        }

        return $this->htmltxt($this->data[$name]);
    }

    protected function getArray(string $name): ?array
    {
        if (empty($this->data[$name])) {
            return [];
        }

        return $this->data[$name];
    }

    public static function attributes(array $attributes): string
    {
        $html = [];
        foreach ($attributes as $name => $value) {
            $html[] = $name.' = "'.$value.'"';
        }

        return implode(' ', $html);
    }

    protected function input(string $type, string $name, ?string $label = null, array $attributes = []): string
    {
        $default = [
            'id' => $name,
            'class' => 'form-control',
            'value' => $this->get($name),
        ];
        $attributes['name'] = $name;

        $help = null;
        if (!empty($attributes['help'])) {
            $help = '<small id="'.$name.'Help" class="form-text text-muted">'.$attributes['help'].'</small>';
            $attributes['aria-describedby'] = $name.'Help';
            unset($attributes['help']);
        }
        $attributes = array_replace_recursive($default, $attributes);
        $input = "<input type=\"{$type}\" {$this->attributes($attributes)}>";

        return $this->view($input.$help, $attributes['id'], $label);
    }

    //-- Public methods for generate HTML --------------------------------------------------------------

    public function text(string $name, ?string $label = null, array $attributes = []): string
    {
        return $this->input('text', $name, $label, $attributes);
    }

    public function password(string $name, ?string $label = null, array $attributes = []): string
    {
        return $this->input('password', $name, $label, $attributes);
    }

    public function email(string $name, ?string $label = 'Email', array $attributes = []): string
    {
        return $this->input('email', $name, $label, $attributes);
    }

    public function file(string $name, ?string $label = null, array $attributes = []): string
    {
        $attributes['class'] = 'form-control-file';
        if (isset($attributes['multiple'])) {
             $name .= '[]';
        }

        return $this->input('file', $name, $label, $attributes);
    }

    public function hidden(string $name, string $value = '1', array $attributes = []): string
    {
        $default = [
            'id' => $name,
            'value' => $this->get($name),
        ];
        $attributes['name'] = $name;
        $attributes = array_replace_recursive($default, $attributes);

        $input = "<input type=\"hidden\" value=\"{$this->htmltxt($value)}\" {$this->attributes($attributes)}>";

        return $input;
    }

    public function textarea(string $name, ?string $label = null, array $attributes = []): string
    {
        $default = [
            'id' => $name,
            'class' => 'form-control',
        ];
        $attributes['name'] = $name;

        $help = null;
        if (!empty($attributes['help'])) {
            $help = '<small id="'.$name.'Help" class="form-text text-muted">'.$attributes['help'].'</small>';
            $attributes['aria-describedby'] = $name.'Help';
            unset($attributes['help']);
        }
        $attributes = array_replace_recursive($default, $attributes);
        $input = "<textarea {$this->attributes($attributes)}>{$this->get($name)}</textarea>";

        return $this->view($input.$help, $attributes['id'], $label);
    }

    public function radio(string $name, ?string $label = null, array $values = [], array $attributes = []): string
    {
        $default = [
            'id' => $name,
            'class' => 'form-check-input',
        ];
        $attributes['name'] = $name;
        $attributes = array_replace_recursive($default, $attributes);

        $input = [];
        $radioCheckedValue = false;
        foreach ($values as $value => $inputLabel) {
            $attributes['id'] = $name.'_'.$value;

            if (isset($attributes['checked'])) {
                unset($attributes['checked']);
            }

            if (!$radioCheckedValue) {
                if ($value === $this->get($name)) {
                    $attributes['checked'] = 'checked';
                    $radioCheckedValue = true;

                } elseif($this->get($name) === null) { // Checked Radio first value
                    $attributes['checked'] = 'checked';
                    $radioCheckedValue = true;
                }
            }

            $input[] = <<<EOF
			<div class="form-check form-check-inline">
				<input type="radio" value="{$this->htmltxt($value)}" {$this->attributes($attributes)}>
				<label class="form-check-label" for="{$attributes['id']}">{$inputLabel}</label>
			</div>
EOF;
        }
        $input = implode(' ', $input);

        return $this->view($input, $attributes['id'], $label);
    }

    public function checkbox(string $name, ?string $label = null, string $value = '1', array $attributes = []): string
    {
        $default = [
            'id' => $name,
            'class' => 'form-check-input',
        ];
        $attributes['name'] = $name;
        if ($this->get($name) === $value) {
            $attributes['checked'] = 'checked';
        }

        $attributes = array_replace_recursive($default, $attributes);
        $input = <<<EOF
			<div class="form-check">
				<input type="checkbox" value="{$this->htmltxt($value)}" {$this->attributes($attributes)}>
				<label class="form-check-label" for="{$attributes['id']}">{$label}</label>
			</div>
EOF;

        return $this->view($input, $attributes['id'], '');
    }

    public function checkboxes(string $name, ?string $label = null, array $values = [], array $attributes = []): string
    {
        $default = [
            'id' => $name,
            'class' => 'form-check-input',
        ];
        $attributes['name'] = $name.'[]';
        $attributes = array_replace_recursive($default, $attributes);

        $input = [];
        $checkedValue = $this->getArray($name);
        foreach ($values as $value => $inputLabel) {
            $attributes['id'] = $name.'_'.$value;
            if (\in_array($value, $checkedValue, true)) {
                $attributes['checked'] = 'checked';
            } else {
                unset($attributes['checked']);
            }

            $input[] = <<<EOF
			<div class="form-check">
				<input type="checkbox" value="{$this->htmltxt($value)}" {$this->attributes($attributes)}>
				<label class="form-check-label" for="{$attributes['id']}">{$inputLabel}</label>
			</div>
EOF;
        }
        $input = implode(' ', $input);

        return $this->view($input, $attributes['id'], $label);
    }

    public function select(string $name, ?string $label = null, array $values = [], array $attributes = []): string
    {
        $default = [
            'id' => $name,
            'class' => 'form-control',
        ];

        // Method sur-mesure pour check
        if (isset($attributes['multiple'])) {
            $attributes['name'] = $name.'[]';
            $selectedValue = $this->getArray($name);
            $isSelected = function (string $value, ?array $selectedValue = []): bool {
                return \in_array($value, $selectedValue, true);
            };
        } else {
            $attributes['name'] = $name;
            $selectedValue = ((isset($this->data[$name])) ? $this->data[$name] : null);
            $isSelected = function (string $value, ?string $selectedValue = null): bool {
                return $value === $selectedValue;
            };
        }

        $attributes = array_replace_recursive($default, $attributes);

        $options = [];
        foreach ($values as $value => $inputLabel) {
            $selected = null;
            if ($isSelected($value, $selectedValue)) {
                $selected = ' selected="selected"';
            }

            $options[] = "<option value=\"{$this->htmltxt($value)}\"{$selected}>{$inputLabel}</option>";
        }
        $options = implode("\n", $options);
        $input = "<select {$this->attributes($attributes)}>{$options}</select>";

        return $this->view($input, $attributes['id'], $label);
    }

    public function button(string $label, array $attributes = []): string
    {
        $attributes = array_replace_recursive(['class' => 'btn btn-info'], $attributes);
        $input = "<button {$this->attributes($attributes)}>{$label}</button>";

        return $input;
    }

    public function submit(string $label = 'Valider', array $attributes = []): string
    {
        $attributes['type'] = 'submit';
        $attributes = array_replace_recursive(['class' => 'btn btn-primary'], $attributes);

        return $this->button($label, $attributes);
    }

    public function reset(string $label = 'Reset', array $attributes = []): string
    {
        $attributes['type'] = 'reset';
        $attributes = array_replace_recursive(['class' => 'btn btn-secondary'], $attributes);

        return $this->button($label, $attributes);
    }

    //-- New HTML5 Input (https://www.w3schools.com/tags/tag_input.asp) -----------------------------------
    public function color(string $name, ?string $label = null, array $attributes = []): string
    {
        return $this->input('color', $name, $label, $attributes);
    }

    public function date(string $name, ?string $label = null, array $attributes = []): string
    {
        return $this->input('date', $name, $label, $attributes);
    }

    public function datetime(string $name, ?string $label = null, array $attributes = []): string
    {
        return $this->input('datetime-local', $name, $label, $attributes);
    }

    public function month(string $name, ?string $label = null, array $attributes = []): string
    {
        return $this->input('month', $name, $label, $attributes);
    }

    public function number(string $name, ?string $label = null, array $attributes = []): string
    {
        return $this->input('number', $name, $label, $attributes);
    }

    public function range(string $name, ?string $label = null, array $attributes = []): string
    {
        return $this->input('range', $name, $label, $attributes);
    }

    public function tel(string $name, ?string $label = null, array $attributes = []): string
    {
        return $this->input('tel', $name, $label, $attributes);
    }

    public function time(string $name, ?string $label = null, array $attributes = []): string
    {
        return $this->input('time', $name, $label, $attributes);
    }

    public function url(string $name, ?string $label = null, array $attributes = []): string
    {
        return $this->input('url', $name, $label, $attributes);
    }

    public function week(string $name, ?string $label = null, array $attributes = []): string
    {
        return $this->input('week', $name, $label, $attributes);
    }

    /*public function search(string $name, ?string $label = null, array $attributes = []): string
    {
        return $this->input('search', $name, $label, $attributes);
    }*/
}
