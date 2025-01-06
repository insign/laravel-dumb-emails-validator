# Laravel Dumb Emails Validator

[![Latest Version on Packagist](https://img.shields.io/packagist/v/insign/laravel-dumb-emails-validator.svg)](https://packagist.org/packages/insign/laravel-dumb-emails-validator)
[![Total Downloads](https://img.shields.io/packagist/dt/insign/laravel-dumb-emails-validator.svg)](https://packagist.org/packages/insign/laravel-dumb-emails-validator)
[![License](https://img.shields.io/packagist/l/insign/laravel-dumb-emails-validator.svg)](https://packagist.org/packages/insign/laravel-dumb-emails-validator)

Um pacote Laravel que adiciona validação para e-mails comumente digitados incorretamente, sugerindo a forma correta.

## Características

- Valida domínios de e-mail comumente digitados errado
- Sugere a forma correta do e-mail
- Fácil de estender com novos domínios
- Totalmente configurável

## Instalação

Você pode instalar o pacote via composer:

```bash
composer require insign/laravel-dumb-emails-validator
```

## Publicação do arquivo de configuração

Opcionalmente, você pode publicar o arquivo de configuração:

```bash
php artisan vendor:publish --provider="insign\DumbEmailsValidator\DumbEmailsValidatorServiceProvider" --tag="config"
```

## Uso

### Validação Básica

```php
$request->validate([
    'email' => ['required', 'email', 'dumb_email'],
]);
```

### Exemplo de Validação

```php
// Isso irá falhar
$email = 'usuario@gamal.com';  // Sugere: usuario@gmail.com
$email = 'usuario@hotail.com'; // Sugere: usuario@hotmail.com

// Isso irá passar
$email = 'usuario@gmail.com';
$email = 'usuario@hotmail.com';
```

### Mensagem de Erro Personalizada

Você pode personalizar a mensagem de erro em seus arquivos de tradução:

```php
// resources/lang/en/validation.php ou resources/lang/pt/validation.php
'dumb_email' => 'Você quis dizer :suggestion?',
```

## Configuração

O pacote vem com uma lista predefinida de correções, mas você pode adicionar suas próprias no arquivo `config/dumb-emails.php`:

```php
return [
    'corrections' => [
        'gamal.com' => 'gmail.com',
        'gmail.com.br' => 'gmail.com',
        'hotail.com' => 'hotmail.com',
        'hotmal.com' => 'hotmail.com',
        'hotmail.com.br' => 'hotmail.com',
        // Adicione mais correções aqui
    ],
];
```

### Adicionando Novas Correções Programaticamente

Você pode adicionar novas correções em um service provider:

```php
config(['dumb-emails.corrections' => array_merge(
    config('dumb-emails.corrections', []),
    [
        'novo-errado.com' => 'correto.com',
    ]
)]);
```

## Testing

```bash
composer test
```

## Contribuindo

Você sabe como fazer isso.

## Licença

The MIT License (MIT). Por favor, veja o [Arquivo de Licença](LICENSE.md) para mais informações.