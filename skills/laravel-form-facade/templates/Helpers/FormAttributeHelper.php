<?php

namespace App\Helpers;

class FormAttributeHelper
{
    /**
     * Process legacy conditional options syntax
     * Converts (condition ? 'disabled' : '') to proper boolean attributes
     */
    public static function processLegacyOptions(array $options): array
    {
        $processedOptions = [];

        foreach ($options as $key => $value) {
            // Check if this is a numeric key with a conditional string value
            if (is_numeric($key) && is_string($value)) {
                // Handle legacy conditional syntax like 'disabled', 'readonly', etc.
                if (in_array($value, ['disabled', 'readonly', 'checked', 'selected', 'multiple'])) {
                    $processedOptions[$value] = true;
                } else {
                    // Keep non-boolean string values as is
                    $processedOptions[$key] = $value;
                }
            } else {
                // Keep normal key-value pairs
                $processedOptions[$key] = $value;
            }
        }

        return $processedOptions;
    }

    /**
     * Build attributes array from options for specific allowed attributes
     */
    public static function buildAttributes(array $baseAttributes, array $options, array $allowedAttributes): array
    {
        $attributes = $baseAttributes;

        foreach ($options as $key => $val) {
            if (in_array($key, $allowedAttributes) || strpos($key, 'data-') === 0) {
                $attributes[$key] = $val;
            }
        }

        return $attributes;
    }

    /**
     * Convert attributes array to HTML attribute string
     */
    public static function attributesToString(array $attributes): string
    {
        $attributeString = '';
        $booleanAttributes = ['disabled', 'readonly', 'checked', 'selected', 'multiple', 'required', 'autofocus', 'novalidate'];

        foreach ($attributes as $key => $val) {
            if ($val === null || $val === false) {
                continue;
            }

            // Handle boolean attributes
            if (in_array($key, $booleanAttributes) && self::isBooleanTrue($val)) {
                $attributeString .= ' ' . $key;
            } else {
                $attributeString .= ' ' . $key . '="' . htmlspecialchars($val) . '"';
            }
        }

        return $attributeString;
    }

    /**
     * Check if a value represents a boolean true for HTML attributes
     */
    private static function isBooleanTrue($val): bool
    {
        return $val === true || $val === 'true' || $val === 'disabled' ||
            $val === 'readonly' || $val === 'selected' || $val === 'checked';
    }
}
