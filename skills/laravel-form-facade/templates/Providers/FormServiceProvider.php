<?php

namespace App\Providers;

use App\Helpers\FormHelper;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class FormServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('form', function ($app) {
            return new FormHelper();
        });

        // Lets Blade call Form:: without touching config/app.php aliases
        AliasLoader::getInstance()->alias('Form', \App\Facades\Form::class);
    }
}
