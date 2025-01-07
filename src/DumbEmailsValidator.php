<?php

namespace insign\DumbEmailsValidator;

use Illuminate\Support\Str;

class DumbEmailsValidator
{
  protected array $corrections;
  
  public function __construct()
  {
    $this->corrections = config('dumb-emails.corrections');
  }
  
  public function validate($attribute, $value, $parameters, $validator) : bool
  {
    if (empty($value)) {
      return true;
    }
    
    $parts = explode('@', $value);
    
    if (count($parts) !== 2) {
      return false;
    }
    
    [$local, $domain] = $parts;
    
    if (empty($local) || empty($domain)) {
      return false;
    }
    
    foreach ($this->corrections as $wrong => $correct) {
      if (Str::lower($domain) === $wrong) {
        $suggestion = $local . '@' . $correct;
        $validator->addReplacer('dumb_email', function ($message, $attribute, $rule, $parameters) use ($suggestion) {
          return str_replace(':suggestion', $suggestion, $message);
        });
        return false;
      }
    }
    
    return true;
  }
}