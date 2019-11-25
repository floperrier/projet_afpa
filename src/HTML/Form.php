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
        return <<<HTML
        <div class="form-group">
        <label for="field{$key}">$label</label>
        <input class="{$this->getInputClass($key)}" type="text" name="{$key}" id="field{$key}" value="{$value}">
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

    private function getValue(string $key): ?string
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
            $invalidFeedback .= '<div class="invalid-feedback">' . implode('<br>',$this->errors[$key]) . '</div>';
        }
        return $invalidFeedback;
    }
}