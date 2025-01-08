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
    
    Validator::extend('dumb_email', function ($attribute, $value, $parameters, $validator) {
      $dumbValidator = new DumbEmailsValidator();
      $result = $dumbValidator->validate($attribute, $value, $parameters, $validator);
      
      if (is_array($result)) {
        $validator->customData = $result[1];
        return $result[0];
      }
      
      return $result;
    });
    
    Validator::replacer('dumb_email', function ($message, $attribute, $rule, $parameters, $validator) {
      if (isset($validator->customData['correct_domain'])) {
        return str_replace(':correct_domain', $validator->customData['correct_domain'], $message);
      }
      return $message;
    });
  }
}