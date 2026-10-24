<?php

namespace App\Helpers;

class FormInputHelper
{
    /**
     * Create input elements
     */
    public static function input(string $type, string $name, $value = null, array $options = []): string
    {
        $options = FormAttributeHelper::processLegacyOptions($options);
        
        $baseAttributes = [
            'type' => $type,
            'name' => $name
        ];
        
        if ($value !== null) {
            $baseAttributes['value'] = $value;
        }
        
        $allowedAttributes = [
            'class', 'id', 'placeholder', 'maxlength', 'onchange', 'onclick',
            'onkeyup', 'onkeydown', 'ondepend', 'onblur', 'readonly', 'disabled', 'checked', 'min', 'max',
            'step', 'size', 'pattern', 'required', 'autocomplete', 'autofocus', 'style', 'title', 'tabindex'
        ];
        
        $attributes = FormAttributeHelper::buildAttributes($baseAttributes, $options, $allowedAttributes);
        
        // Handle checked attribute for radio/checkbox
        if (isset($options['checked']) && $options['checked']) {
            $attributes['checked'] = 'checked';
        }
        
        $attributeString = FormAttributeHelper::attributesToString($attributes);
        
        return '<input' . $attributeString . '>';
    }

    public static function text(string $name, $value = null, array $options = []): string
    {
        return self::input('text', $name, $value, $options);
    }
    
    public static function number(string $name, $value = null, array $options = []): string
    {
        return self::input('number', $name, $value, $options);
    }
    
    public static function date(string $name, $value = null, array $options = []): string
    {
        return self::input('date', $name, $value, $options);
    }
    
    public static function time(string $name, $value = null, array $options = []): string
    {
        return self::input('time', $name, $value, $options);
    }
    
    public static function datetime(string $name, $value = null, array $options = []): string
    {
        return self::input('datetime-local', $name, $value, $options);
    }
    
    public static function tel(string $name, $value = null, array $options = []): string
    {
        return self::input('tel', $name, $value, $options);
    }
    
    public static function range(string $name, $value = null, array $options = []): string
    {
        return self::input('range', $name, $value, $options);
    }
    
    public static function color(string $name, $value = null, array $options = []): string
    {
        return self::input('color', $name, $value, $options);
    }
    
    public static function email(string $name, $value = null, array $options = []): string
    {
        return self::input('email', $name, $value, $options);
    }
    
    public static function url(string $name, $value = null, array $options = []): string
    {
        return self::input('url', $name, $value, $options);
    }
    
    public static function hidden(string $name, $value = null, array $options = []): string
    {
        return self::input('hidden', $name, $value, $options);
    }
    
    public static function password(string $name, array $options = []): string
    {
        return self::input('password', $name, null, $options);
    }
    
    public static function file(string $name, array $options = []): string
    {
        return self::input('file', $name, null, $options);
    }
    
    public static function radio(string $name, $value = null, $checked = false, array $options = []): string
    {
        $options['checked'] = (bool) $checked;
        return self::input('radio', $name, $value, $options);
    }
    
    public static function checkbox(string $name, $value = 1, $checked = false, array $options = []): string
    {
        $options['checked'] = (bool) $checked;
        return self::input('checkbox', $name, $value, $options);
    }
}
