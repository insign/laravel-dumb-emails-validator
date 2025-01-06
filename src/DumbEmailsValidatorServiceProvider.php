<?php

namespace insign\DumbEmailsValidator;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use SeuVendor\DumbEmailsValidator\DumbEmailsValidator;

class DumbEmailsValidatorServiceProvider extends ServiceProvider
{
  public function boot()
  {
    $this->publishes([
                       __DIR__ . '/config/dumb-emails.php' => config_path('dumb-emails.php'),
                     ], 'config');
    
    Validator::extend('dumb_email', function ($attribute, $value, $parameters, $validator) {
      return (new DumbEmailsValidator())->validate($attribute, $value, $parameters, $validator);
    });
    
    Validator::replacer('dumb_email', function ($message, $attribute, $rule, $parameters) {
      return str_replace(':attribute', $attribute, $message);
    });
    
    Validator::addReplacer('dumb_email', function ($message, $attribute, $rule, $parameters) {
      return str_replace(':suggestion', '', $message);
    });
    
    $this->loadTranslationsFrom(__DIR__.'/lang', 'dumb-emails');
  }
  
  public function register()
  {
    $this->mergeConfigFrom(
      __DIR__ . '/config/dumb-emails.php',
      'dumb-emails'
    );
  }
}