<?php

namespace insign\DumbEmailsValidator;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

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
    
    // Replace the old addReplacer with this
    Validator::replacer('dumb_email', function ($message, $attribute, $rule, $parameters) use ($validator) {
      $customMessage = str_replace(':attribute', $attribute, $message);
      
      if (isset($validator->customData['suggestion'])) {
        return str_replace(':suggestion', $validator->customData['suggestion'], $customMessage);
      }
      
      return str_replace(':suggestion', '', $customMessage);
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