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
        $validator->setCustomAttribute('correct_domain', $right);
        $validator->setCustomMessages([
                                        'dumb_email' => config('dumb-emails.message', "Did you mean @{$right}?"),
                                      ]);
        return false;
      }
    }
    
    return true;
  }
}