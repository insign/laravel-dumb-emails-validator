<?php

namespace insign\DumbEmailsValidator\Tests;

use Orchestra\Testbench\TestCase;
use insign\DumbEmailsValidator\DumbEmailsValidatorServiceProvider;
use Illuminate\Support\Facades\Validator;

class DumbEmailsValidatorTest extends TestCase
{
  protected function getPackageProviders($app)
  {
    return [DumbEmailsValidatorServiceProvider::class];
  }
  
  protected function getEnvironmentSetUp($app)
  {
    // If you need to modify the config for tests, you can do it here:
    // $app['config']->set('dumb-emails.corrections', [...]);
  }
  
  /** @test */
  public function it_passes_valid_emails()
  {
    $validator = Validator::make(['email' => 'test@gmail.com'], ['email' => 'dumb_email']);
    $this->assertTrue($validator->passes());
    
    $validator = Validator::make(['email' => 'user@yahoo.com'], ['email' => 'dumb_email']);
    $this->assertTrue($validator->passes());
    
    $validator = Validator::make(['email' => 'someone@outlook.com'], ['email' => 'dumb_email']);
    $this->assertTrue($validator->passes());
  }
  
  /** @test */
  public function it_fails_invalid_emails()
  {
    $validator = Validator::make(['email' => 'test@gmal.com'], ['email' => 'dumb_email']);
    $this->assertTrue($validator->fails());
    
    $validator = Validator::make(['email' => 'user@hotail.com'], ['email' => 'dumb_email']);
    $this->assertTrue($validator->fails());
    
    $validator = Validator::make(['email' => 'test@yhoo.com'], ['email' => 'dumb_email']);
    $this->assertTrue($validator->fails());
  }
  
  /** @test */
  public function it_returns_correct_error_message()
  {
    $validator = Validator::make(['email' => 'test@gmal.com'], ['email' => 'dumb_email']);
    $this->assertEquals('Did you mean email@gmail.com?', $validator->errors()->first('email'));
    
    $validator = Validator::make(['contact' => 'user@hotail.com'], ['contact' => 'dumb_email']);
    $this->assertEquals('Did you mean contact@hotmail.com?', $validator->errors()->first('contact'));
  }
  
  /** @test */
  public function it_uses_custom_error_message_from_config()
  {
    config(['dumb-emails.message' => 'Custom error: :attribute, perhaps you meant :correct_domain?']);
    
    $validator = Validator::make(['email' => 'test@gmal.com'], ['email' => 'dumb_email']);
    $this->assertEquals('Custom error: email, perhaps you meant gmail.com?', $validator->errors()->first('email'));
  }
  
  /** @test */
  public function it_handles_emails_with_no_at_symbol()
  {
    $validator = Validator::make(['email' => 'invalidemail'], ['email' => 'dumb_email']);
    $this->assertTrue($validator->fails());
    
    // Customize the error message for no @ symbol to make sure the right error is being triggered
    Validator::replacer('dumb_email', function ($message, $attribute, $rule, $parameters) {
      return 'The email is invalid.';
    });
    $validator = Validator::make(['email' => 'invalidemail'], ['email' => 'dumb_email']);
    $this->assertEquals('The email is invalid.', $validator->errors()->first('email'));
  }
}