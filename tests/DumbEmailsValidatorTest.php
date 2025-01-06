<?php

namespace insign\DumbEmailsValidator\Tests;

use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\Config;

class DumbEmailsValidatorTest extends TestCase
{
  protected function getPackageProviders($app)
  {
    return ['insign\DumbEmailsValidator\DumbEmailsValidatorServiceProvider'];
  }
  
  protected function setUp(): void
  {
    parent::setUp();
    
    Config::set('dumb-emails.corrections', [
      'gamal.com' => 'gmail.com',
      'hotail.com' => 'hotmail.com',
      'hotmal.com' => 'hotmail.com',
    ]);
  }
  
  /** @test */
  public function it_validates_valid_email()
  {
    $validator = validator(['email' => 'test@gmail.com'], [
      'email' => 'dumb_email'
    ]);
    
    $this->assertTrue($validator->passes());
  }
  
  /** @test */
  public function it_fails_for_dumb_email_domain_and_provides_suggestion()
  {
    $validator = validator(
      ['email' => 'test@gamal.com'],
      ['email' => 'dumb_email'],
      ['dumb_email' => 'Did you mean :suggestion?']
    );
    
    $this->assertFalse($validator->passes());
    $this->assertEquals(
      'Did you mean test@gmail.com?',
      $validator->errors()->first('email')
    );
  }
  
  /** @test */
  public function it_fails_for_invalid_email_format()
  {
    $validator = validator(['email' => 'invalid-email'], [
      'email' => 'dumb_email'
    ]);
    
    $this->assertFalse($validator->passes());
  }
  
  /** @test */
  public function it_passes_for_empty_email()
  {
    $validator = validator(['email' => ''], [
      'email' => 'dumb_email'
    ]);
    
    $this->assertTrue($validator->passes());
  }
  
  /** @test */
  public function it_fails_for_empty_local_part()
  {
    $validator = validator(['email' => '@domain.com'], [
      'email' => 'dumb_email'
    ]);
    
    $this->assertFalse($validator->passes());
  }
  
  /** @test */
  public function it_fails_for_empty_domain_part()
  {
    $validator = validator(['email' => 'local@'], [
      'email' => 'dumb_email'
    ]);
    
    $this->assertFalse($validator->passes());
  }
  
  /** @test */
  public function it_uses_custom_message_and_replaces_attribute()
  {
    $validator = validator(
      ['custom_email' => 'test@gamal.com'],
      ['custom_email' => 'dumb_email'],
      ['dumb_email' => 'The :attribute field has a typo. Did you mean :suggestion?']
    );
    
    $this->assertFalse($validator->passes());
    $this->assertEquals(
      'The custom_email field has a typo. Did you mean test@gmail.com?',
      $validator->errors()->first('custom_email')
    );
  }
}