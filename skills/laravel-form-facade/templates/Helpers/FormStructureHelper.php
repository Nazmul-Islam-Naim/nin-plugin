<?php

namespace App\Helpers;

class FormStructureHelper
{
    public static function open(array $options = []): string
    {
        $method = isset($options['method']) ? strtoupper($options['method']) : 'GET';
        $action = isset($options['url']) ? (string)$options['url'] : '';
        
        $attributeString = self::buildFormAttributes($options);
        $hiddenFields = self::buildHiddenFields($method);
        
        return '<form action="' . htmlspecialchars($action) . '" method="' . self::normalizeMethod($method) . '"' . $attributeString . '>' . $hiddenFields;
    }
    
    public static function close(): string
    {
        return '</form>';
    }
    
    private static function buildFormAttributes(array $options): string
    {
        $allowed = ['id', 'class', 'enctype', 'role', 'name', 'style', 'target', 'autocomplete', 'novalidate'];
        $attributes = FormAttributeHelper::buildAttributes([], $options, $allowed);

        return FormAttributeHelper::attributesToString($attributes);
    }
    
    private static function buildHiddenFields(string $method): string
    {
        $hiddenFields = '';
        
        if (!in_array($method, ['GET', 'POST'])) {
            $hiddenFields .= '<input type="hidden" name="_method" value="' . htmlspecialchars($method) . '">';
        }
        
        if (self::normalizeMethod($method) === 'POST') {
            $hiddenFields .= '<input type="hidden" name="_token" value="' . csrf_token() . '">';
        }
        
        return $hiddenFields;
    }
    
    private static function normalizeMethod(string $method): string
    {
        return in_array($method, ['GET', 'POST']) ? $method : 'POST';
    }
}
