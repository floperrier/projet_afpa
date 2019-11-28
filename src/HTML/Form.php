<?php

namespace App\HTML;

use App\Model\Post;

class Form
{
    private $data;
    private $errors = [];

    public function __construct($data, array $errors)
    {
        $this->data = $data;
        $this->errors = $errors;
    }

    public function input(string $key, string $label)
    {
        $value = $this->getValue($key);
        $type = $key == "password" ? "password" : "text";
        return <<<HTML
        <div class="form-group">
        <label for="field{$key}">$label</label>
        <input class="{$this->getInputClass($key)}" type="{$type}" name="{$key}" id="field{$key}" value="{$value}">
        {$this->getInvalidFeedback($key)}
        </div>
HTML;
    }

    public function select(string $key, string $label, $options = [])
    {
        $optionsHTML = [];
        $value = $this->getValue($key);
        foreach ($options as $k => $v) {
            $selected = in_array($k,$value) ? "selected" : "";
            $optionsHTML[] = "<option value='$k' $selected>$v</option>";
        }
        $optionsHTML = implode("", $optionsHTML);
        $optionsNumber = count($options);
        return <<<HTML
        <div class="form-group">
        <label for="field{$key}">$label</label>
        <select class="{$this->getInputClass($key)}" type="text" name="{$key}[]" id="field{$key}" size="{$optionsNumber}" required multiple>{$optionsHTML}</select>
        {$this->getInvalidFeedback($key)}
        </div>
HTML;
    }

    public function textarea(string $key, string $label)
    {
        $value = $this->getValue($key);
        return <<<HTML
        <div class="form-group">
        <label for="field{$key}">$label</label>
        <textarea class="{$this->getInputClass($key)}" rows="15" type="text" name="{$key}" id="field{$key}">$value</textarea>
        {$this->getInvalidFeedback($key)}
        </div>
HTML;
    }

    private function getValue(string $key)
    {
        if (is_array($this->data)) {
            return $this->data[$key] ?? null;
        }
        $method = 'get' . str_replace(' ','',ucwords(str_replace('_',' ',$key)));
        $value = $this->data->$method();
        if ($value instanceof \DateTimeInterface) {
            return $value->format("Y-m-d H:i:s");
        }
        return $value;
    }

    private function getInputClass(string $key): string
    {
        $inputClass = 'form-control';
        if (isset($this->errors[$key])) {
            $inputClass .= ' is-invalid';
        }
        return $inputClass;
    }

    private function getInvalidFeedback(string $key): string
    {
        $invalidFeedback = '';
        if (isset($this->errors[$key])) {
            if (is_array($this->errors[$key])) {
                $error = implode('<br>', $this->errors[$key]);
            } else {
                $error = $this->errors[$key];
            }
            $invalidFeedback .= '<div class="invalid-feedback">' . $error . '</div>';
        }
        return $invalidFeedback;
    }
}