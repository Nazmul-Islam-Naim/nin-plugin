<?php

namespace App\Helpers;

/**
 * Main FormHelper class that delegates to specialized helper classes
 * This class maintains backward compatibility while using a more organized structure
 */
class FormHelper
{
    // Form structure methods
    public function open($options = [])
    {
        return FormStructureHelper::open($options);
    }
    
    public function close()
    {
        return FormStructureHelper::close();
    }
    
    // Basic form elements
    public function label($name, $value = null, $options = [])
    {
        return FormElementHelper::label($name, $value, $options);
    }
    
    public function textarea($name, $value = null, $options = [])
    {
        return FormElementHelper::textarea($name, $value, $options);
    }
    
    public function select($name, $list = [], $selected = null, $options = [])
    {
        // Convert Collection to array if needed
        if ($list instanceof \Illuminate\Support\Collection) {
            $list = $list->toArray();
        }
        
        return FormElementHelper::select($name, $list, $selected, $options);
    }
    
    public function selectRange($name, $begin, $end, $selected = null, $options = [])
    {
        return FormElementHelper::selectRange($name, $begin, $end, $selected, $options);
    }
    
    public function button($text, $options = [])
    {
        return FormElementHelper::button($text, $options);
    }
    
    // Input methods - delegate to FormInputHelper for all input types
    public function text($name, $value = null, $options = [])
    {
        return FormInputHelper::text($name, $value, $options);
    }
    
    public function email($name, $value = null, $options = [])
    {
        return FormInputHelper::email($name, $value, $options);
    }
    
    public function url($name, $value = null, $options = [])
    {
        return FormInputHelper::url($name, $value, $options);
    }
    
    public function password($name, $options = [])
    {
        return FormInputHelper::password($name, $options);
    }
    
    public function hidden($name, $value = null, $options = [])
    {
        return FormInputHelper::hidden($name, $value, $options);
    }
    
    public function file($name, $options = [])
    {
        return FormInputHelper::file($name, $options);
    }
    
    public function checkbox($name, $value = 1, $checked = false, $options = [])
    {
        return FormInputHelper::checkbox($name, $value, $checked, $options);
    }
    
    public function radio($name, $value = null, $checked = false, $options = [])
    {
        return FormInputHelper::radio($name, $value, $checked, $options);
    }
    
    // Catch-all method for other input types to maintain compatibility
    public function __call($name, $arguments)
    {
        $inputTypes = ['number', 'date', 'time', 'datetime', 'tel', 'range', 'color','submit'];

        if (in_array($name, $inputTypes)) {
            $type = $name;

            $arg1 = $arguments[0] ?? null;
            $arg2 = $arguments[1] ?? null;
            $arg3 = $arguments[2] ?? [];

            if (is_array($arg2)) {
                return FormInputHelper::input($type, $arg2['name'] ?? 'submit', $arg1, $arg2);
            } else {
                return FormInputHelper::input($type, $arg1, $arg2, $arg3);
            }
        }
        
        throw new \BadMethodCallException("Method {$name} does not exist");
    }
}
