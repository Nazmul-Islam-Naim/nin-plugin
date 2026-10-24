<?php

namespace App\Helpers;

class FormElementHelper
{
    public static function textarea(string $name, $value = null, array $options = []): string
    {
        $options = FormAttributeHelper::processLegacyOptions($options);

        $baseAttributes = ['name' => $name];

        $allowedAttributes = [
            'class',
            'id',
            'placeholder',
            'rows',
            'cols',
            'readonly',
            'disabled',
            'maxlength',
            'onchange',
            'onclick',
            'onkeyup',
            'onblur',
            'style',
            'required',
            'title',
            'tabindex'
        ];

        $attributes = FormAttributeHelper::buildAttributes($baseAttributes, $options, $allowedAttributes);
        $attributeString = FormAttributeHelper::attributesToString($attributes);

        return '<textarea' . $attributeString . '>' . htmlspecialchars($value ?: '') . '</textarea>';
    }

    public static function select(string $name, array $list = [], $selected = null, array $options = []): string
    {
        $options = FormAttributeHelper::processLegacyOptions($options);

        $baseAttributes = ['name' => $name];

        $allowedAttributes = [
            'class',
            'id',
            'placeholder',
            'onchange',
            'onblur',
            'onkeydown',
            'disabled',
            'readonly',
            'multiple',
            'style',
            'required',
            'title',
            'tabindex'
        ];

        $attributes = FormAttributeHelper::buildAttributes($baseAttributes, $options, $allowedAttributes);
        $attributeString = FormAttributeHelper::attributesToString($attributes);

        $html = '<select' . $attributeString . '>';
        $html .= self::buildSelectOptions($list, $selected);
        $html .= '</select>';

        return $html;
    }

    private static function buildSelectOptions(array $list, $selected): string
    {
        $html = '';

        foreach ($list as $value => $text) {
            $selectedValue = self::normalizeSelectedValue($selected);
            $selectedAttr = ($value == $selectedValue) ? ' selected="selected"' : '';
            $html .= '<option value="' . htmlspecialchars($value) . '"' . $selectedAttr . '>' . htmlspecialchars($text) . '</option>';
        }

        return $html;
    }

    private static function normalizeSelectedValue($selected)
    {
        // Handle Collection objects that might be passed as selected value
        if ($selected instanceof \Illuminate\Support\Collection) {
            return $selected->first();
        }

        return $selected;
    }

    public static function selectRange(string $name, int $begin, int $end, $selected = null, array $options = []): string
    {
        $list = self::createRangeArray($begin, $end);
        return self::select($name, $list, $selected, $options);
    }

    private static function createRangeArray(int $begin, int $end): array
    {
        $list = [];

        if ($begin > $end) {
            // Descending order (e.g., years from current to past)
            for ($i = $begin; $i >= $end; $i--) {
                $list[$i] = $i;
            }
        } else {
            // Ascending order
            for ($i = $begin; $i <= $end; $i++) {
                $list[$i] = $i;
            }
        }

        return $list;
    }

    public static function button(string $text, array $options = []): string
    {
        $options = FormAttributeHelper::processLegacyOptions($options);

        $baseAttributes = ['type' => $options['type'] ?? 'button'];

        $allowedAttributes = [
            'type',
            'class',
            'id',
            'name',
            'value',
            'onclick',
            'onchange',
            'onblur',
            'disabled',
            'form',
            'style',
            'title',
            'tabindex'
        ];

        $attributes = FormAttributeHelper::buildAttributes($baseAttributes, $options, $allowedAttributes);
        $attributeString = FormAttributeHelper::attributesToString($attributes);

        // ponytail: $text is raw HTML on purpose (icons like <i class="fa ...">); never pass user input here
        return '<button' . $attributeString . '>' . $text . '</button>';
    }

    public static function label(string $name, $value = null, array $options = []): string
    {
        $attributes = ['for' => $name];
        foreach (['class', 'id', 'style'] as $key) {
            if (isset($options[$key])) {
                $attributes[$key] = $options[$key];
            }
        }

        // ponytail: $value is raw HTML on purpose (e.g. required-star spans); only the $name fallback is escaped
        $text = $value ?: htmlspecialchars($name);

        return '<label' . FormAttributeHelper::attributesToString($attributes) . '>' . $text . '</label>';
    }
}
