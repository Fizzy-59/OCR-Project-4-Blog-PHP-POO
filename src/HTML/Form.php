<?php


namespace App\HTML;


use DateTimeInterface;

class Form
{
    private $data;
    private $errors;

    public function __construct($data, array $errors)
    {
        $this->data = $data;
        $this->errors = $errors;

    }

    /**
     * Generate input field
     *
     * @param  string $key
     * @param  string $label
     * @return string
     */
    public function input (string $key, string $label): string
    {
        $value = $this->getValue($key);
        $type = $key === "password" ? "password" : "text";

        return <<<HTML
          <div class="form-group">
            <label for="field{$key}">{$label}</label>
            <input type="{$type}" id="field{$key}" class="{$this->getInputClass($key)}" name="{$key}" value="{$value}" required>
            {$this->getErrorFeedback($key)}
        </div>
HTML;
    }

    /**
     * Generate textarea field
     *
     * @param  string $key
     * @param  string $label
     * @return string
     */
    public function textarea(string $key, string $label)
    {
        $value = $this->getValue($key);

        return <<<HTML
        <div class="form-group">
            <label for="field{$key}">{$label}</label>
            <textarea type="text" id="field{$key}" class="{$this->getInputClass($key)}" name="{$key}" required> {$value} </textarea>
            {$this->getErrorFeedback($key)}
        </div>
HTML;

    }

    /**
     *
     *
     * @param  string $key
     * @return mixed|string|null
     */
    private function getValue(string $key)
    {
        if(is_array($this->data))
            {
                return $this->data[$key] ?? null;
            }

        $method = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));

        $value =  $this->data->$method();

        if ($value instanceof DateTimeInterface)
        {
            return $value->format('Y-m-d H:i:s');
        }

        return $value;
    }

    /**
     * Generate the class from a key
     *
     * @param string $key
     */
    private function getInputClass(string $key): string
    {
        $inputClass = 'form-control';

        if (isset($this->errors[$key]))
        {
            $inputClass .= 'is-invalid';
        }
        return $inputClass;
    }

    /**
     * Error management
     *
     * @param  string $key
     * @return string
     */
    private function getErrorFeedback (string $key): string
    {
        if (isset($this->errors[$key]))
        {
            if(is_array($this->errors[$key]))
            {
                $error = implode('<br>', $this->errors[$key]);
            }
            else
            {
                $error = $this->errors[$key];
            }

            return '<div class="alert alert-danger">' . $error . '</div>';
        }
        return '';
    }

    /**
     * Generate option for link categories in form Post
     *
     * @param  string $key
     * @param  string $label
     * @param  array $options
     * @return string
     */
    public function select (string $key, string $label, array $options = [] ): string
    {
        $optionsHTML = [];
        $value = $this->getValue($key);

        foreach ($options as $k => $v)
        {
            $selected = in_array($k, $value) ? " selected" : "";
            $optionsHTML[] = "<option value=\"$k\"$selected>$v</option>";
        }

        $value = $this->getValue($key);
        $optionsHTML = implode('', $optionsHTML);
        return <<<HTML
          <div class="form-group">
            <label for="field{$key}">{$label}</label>
            <select id="field{$key}" class="{$this->getInputClass($key)}" name="{$key}[]" required multiple>{$optionsHTML}</select>
            {$this->getErrorFeedback($key)}
        </div>
HTML;
    }
}
