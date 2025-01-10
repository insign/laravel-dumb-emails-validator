<?php

namespace insign\DumbEmailsValidator;

use Illuminate\Support\Str;

class DumbEmailsValidator
{
  public function validate($attribute, $value, $parameters, $validator) : bool
  {
    $corrections = config('dumb-emails.corrections');
    $emailParts = explode('@', $value);
    
    if (count($emailParts) != 2) {
      return false; // Formato de email inválido
    }
    
    foreach ($corrections as $wrong => $right) {
      if (Str::endsWith($value, '@' . $wrong)) {
        // Armazenar o domínio correto como parâmetro para ser usado no replacer
        $validator->setData(array_merge($validator->getData(), ['correct_domain' => $right]));
        return false;
      }
    }
    
    return true;
  }
}