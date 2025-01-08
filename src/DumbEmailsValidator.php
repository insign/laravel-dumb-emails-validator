<?php

namespace insign\DumbEmailsValidator;

use Illuminate\Support\Str;

class DumbEmailsValidator
{
  public function validate($attribute, $value, $parameters, $validator)
  {
    $corrections = config('dumb-emails.corrections');
    $emailParts = explode('@', $value);
    
    if (count($emailParts) != 2) {
      return false; // Invalid email format
    }
    
    $domain = $emailParts[1];
    
    foreach ($corrections as $wrong => $right) {
      if (Str::endsWith($domain, $wrong)) {
        return [false, ['correct_domain' => $right]];
      }
    }
    
    return true;
  }
}