---
name: laravel-form-facade
description: Use when a Laravel 10+ project has Blade views calling Form::open, Form::text, Form::select etc., when laravelcollective/html is missing, abandoned or won't install on the current Laravel version, or when Blade throws 'Class "Form" not found'.
---

# Laravel Form Facade

**Language:** every reply is in Bangla script (বাংলা অক্ষর), never Banglish. Keep technical terms, paths and code in English.

A drop-in `Form::` facade that replaces `laravelcollective/html`, with no composer package. Existing `{!! Form::text(...) !!}` Blade code keeps working unchanged. The files are in `templates/`.

## Steps

1. **Look first.** If `composer.json` already has `laravelcollective/html` or `spatie/laravel-html`, or `app/Helpers/Form*.php` already exists, stop and use that instead. If `laravelcollective/html` is installed but broken, remove it with `composer remove laravelcollective/html` before going on.
2. **Copy the templates** into the project:
   - `templates/Helpers/*.php` → `app/Helpers/`
   - `templates/Providers/FormServiceProvider.php` → `app/Providers/`
   - `templates/Facades/Form.php` → `app/Facades/`

   If the project uses a different namespace than `App\`, change the `namespace` and `use` lines in all 7 files.
3. **Register the provider:**
   - Laravel 11+: add `App\Providers\FormServiceProvider::class` to `bootstrap/providers.php`.
   - Laravel 10 and older: add it to `providers` in `config/app.php`.

   The provider registers the `Form` alias itself through `AliasLoader`. If `config/app.php` already has a `'Form'` alias pointing elsewhere, remove that entry.
4. **Verify:** run `php artisan optimize:clear`, then
   `php artisan tinker --execute="echo Form::open(['url'=>'/x','method'=>'put']);"`
   The output should contain `method="POST"`, `_method` with value `PUT`, and `_token`. Then open one real Blade form in the browser.

## Quick Reference

| Method | Signature |
|---|---|
| `open` / `close` | `open(['url'=>..., 'method'=>..., 'id','class','enctype','role','name','style','target','autocomplete','novalidate', 'data-*'])` |
| text-like | `text/email/url/hidden/number/date/time/datetime/tel/range/color($name, $value = null, $options = [])` |
| `password` / `file` | `($name, $options = [])` |
| `checkbox` / `radio` | `($name, $value, $checked = false, $options = [])` |
| `select` | `($name, $list = [], $selected = null, $options = [])`. `$list` can be an array or a Collection. |
| `selectRange` | `($name, $begin, $end, $selected = null, $options = [])`. Counts down when `$begin > $end`. |
| `textarea` | `($name, $value = null, $options = [])` |
| `label` | `($name, $value = null, $options = [])`. `$value` is raw HTML. |
| `button` | `($text, $options = [])`. `$text` is raw HTML. |
| `submit` | `($value, $options = [])` |

Legacy positional flags also work: `['class'=>'x', $locked ? 'readonly' : '']`.

## Not supported (unlike laravelcollective)

- In `Form::open`: `route`, `action`, `files => true` (use `'enctype'=>'multipart/form-data'` instead).
- `Form::model()` binding and automatic `old()` refill. Pass `old('name', $model->name)` as the value yourself.
- The `Html::` facade (`Html::image`, `Html::script`). Write plain `<img>`/`<script>` tags instead.
- Attributes outside each method's allowlist are **dropped silently**; only `data-*` always passes. If an attribute is missing from the output, add it to `$allowedAttributes` in that helper.

## Common Mistakes

| Symptom | Fix |
|---|---|
| `Class "Form" not found` | Provider not registered (step 3), or config is cached. Run `php artisan optimize:clear`. |
| `Method xyz does not exist` | The method isn't implemented. Add it to `FormHelper` or the `__call` `$inputTypes` list. |
| An attribute such as `aria-*` or `onfocus` is missing from the HTML | It isn't in the allowlist. Add it in `FormInputHelper` or `FormElementHelper`. |
| User data shows up as HTML in a `label`/`button` | `$value`/`$text` is raw HTML. Wrap user data with `e()` first. |
