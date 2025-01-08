<?php

namespace insign\DumbEmailsValidator;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class DumbEmailsValidatorServiceProvider extends ServiceProvider
{
  /**
   * Register services.
   *
   * @return void
   */
  public function register()
  {
    $this->mergeConfigFrom(
      __DIR__.'/config/dumb-emails.php', 'dumb-emails'
    );
  }
  
  /**
   * Bootstrap services.
   *
   * @return void
   */
  public function boot()
  {
    if ($this->app->runningInConsole()) {
      $this->publishes([
                         __DIR__.'/config/dumb-emails.php' => config_path('dumb-emails.php'),
                       ]);
    }
    
    // Registrar a regra de validação
    Validator::extend('dumb_email', 'insign\DumbEmailsValidator\DumbEmailsValidator@validate');
    
    // Substituir placeholder ':attribute' e ':correct_domain'
    Validator::replacer('dumb_email', function ($message, $attribute, $rule, $parameters, $validator) {
      $message = config('dumb-emails.message', $message);
      
      // Substituir :attribute pelo nome do campo
      $message = str_replace(':attribute', $attribute, $message);
      
      // Substituir :correct_domain pelo domínio correto
      if (isset($validator->getData()['correct_domain'])) {
        $correctDomain = $validator->getData()['correct_domain'];
        $message = str_replace(':correct_domain', $correctDomain, $message);
      }
      
      return $message;
    });
  }
}