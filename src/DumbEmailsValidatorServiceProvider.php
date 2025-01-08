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
    
    Validator::extend('dumb_email', 'insign\DumbEmailsValidator\DumbEmailsValidator@validate');
  }
}