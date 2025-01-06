<?php

namespace SeuVendor\DumbEmailsValidator;

use Illuminate\Support\Str;

class DumbEmailsValidator
{
  protected array $corrections;
  
  public function __construct()
  {
    $this->corrections = config('dumb-emails.corrections');
  }
  
  public function validate($attribute, $value, $parameters, $validator)
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
        // Store the suggestion in the validator's custom data
        $validator->customData['suggestion'] = $suggestion;
        return false;
      }
    }
    
    return true;
  }
}