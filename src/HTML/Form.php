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
        $invalidFeedback = isset($this->errors[$key]) ? "<div class='invalid-feedback'>" . implode('<br>',$this->errors[$key]) . "</div>" : '';
        $isInvalid = isset($this->errors[$key]) ? "is-invalid" : "";
        return <<<HTML
        <div class="form-group">
        <label for="field{$key}">$label</label>
        <input class="form-control $isInvalid" type="text" name="$key" id="field{$key}" value="$value">
        $invalidFeedback
        </div>
HTML;
    }

    public function textarea(string $key, string $label)
    {
        $value = $this->getValue($key);
        $invalidFeedback = isset($this->errors[$key]) ? "<div class='invalid-feedback'>" . implode('<br>',$this->errors[$key]) . "</div>" : '';
        $isInvalid = isset($this->errors[$key]) ? "is-invalid" : "";
        return <<<HTML
        <div class="form-group">
        <label for="field{$key}">$label</label>
        <textarea class="form-control $isInvalid" rows="15" type="text" name="$key" id="field{$key}">$value</textarea>
        $invalidFeedback
        </div>
HTML;
    }

    private function getValue(string $key)
    {
        if (is_array($this->data)) {
            return $this->data[$key] ?? null;
        }
        $method = 'get' . ucfirst($key);
        return $this->data->$method();
    }
}