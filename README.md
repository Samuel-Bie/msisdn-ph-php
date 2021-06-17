MZ MSISISDN (Números de celulares moçambicanos)
=====================

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Facilmente validando e manipulando números de celulares Moçambicanos.
Suporte para TMcel, Vodacom, e Movitel

## Índice

- [MZ MSISISDN (Números de celulares moçambicanos)](#mz-msisisdn-números-de-celulares-moçambicanos)
  - [Índice](#índice)
  - [Instalação](#instalação)
  - [Utilização](#utilização)
    - [Validate the mobile number](#validate-the-mobile-number)
    - [Instantiate an MSISDN object](#instantiate-an-msisdn-object)
    - [Formato padronizado dos números de celular](#formato-padronizado-dos-números-de-celular)
    - [Funções baseadas em operadoras](#funções-baseadas-em-operadoras)
    - [Verificando a operadora](#verificando-a-operadora)
    - [Prefixo da operadora](#prefixo-da-operadora)
    - [Integração as validações Laravel](#integração-as-validações-laravel)
  - [Credits](#credits)
  - [License](#license)

## Instalação

Execute na raiz do seu projeto (assumindo que tenhas instalado o [Composer](https://getcomposer.org/)) o seguinte comando:


```bash
composer require samuelbie/mzmsisdn
```

## Utilização

### Validate the mobile number

A maneira mais básica é executando o método estático `validate` da classe `Msisdn`, passando como parâmetro o número de celular.

```php
$mobileNumber = '823847698';

if (Msisdn::validate($mobileNumber)) {
    echo 'Valid mobile number';
} else {
    echo 'Invalid mobile number';
}
```

O método `validate` compila ou sanitiza o número dado como entrada e realiza a validação, garantindo que mesmo que o usuário tenha introduzido carateres de separação no meio da string o método retorne verdadeiro caso seja realmente válido:

```php
$validMobileNumber = '+258823847556';
$validMobileNumber = '+258-82-38-47-556';
$validMobileNumber = '847386187';
$validMobileNumber = '84.738.6187';
$validMobileNumber = '258 82 38 47 556 ';
```

### Instantiate an MSISDN object

De outro jeito podemos também criar uma instância da classe MSISDN e o contacto é padronizado ao formato Moçambicano.

```php
$mobileNumber = '823847556';

$msisdn = new Msisdn($mobileNumber);
```

O objecto MSISDN irá lançar uma  `InvalidMsisdnException` caso o construtor não seja alimentado com um número válido. Nesse contexto é uma boa ideia tratar a excepção ou validar o número antes de construir o objecto.

```php
$invalidMobileNumber = '82-38-47-55';

try {
   $msisdn = new Msisdn($invalidMobileNumber);
} catch (InvalidMsisdnException $e) {
   echo 'The number is invalid';
   return;
}
```

OR

```php
$invalidMobileNumber = '82-38-47-55';


if (Msisdn::validate($invalidMobileNumber)) {
    $msisdn = new Msisdn($invalidMobileNumber);
} else {
   echo 'Invalid mobile number';
   return;
}
```


### Formato padronizado dos números de celular

Ao instanciar um objecto `Msisdn`,  poderá retornar vários formatos de números de celular, dependendo naturalmente do que será mais útil para o seu contexto.

```php
$mobileNumber = '823847556';

$msisdn = new Msisdn($mobileNumber);

echo $msisdn->get(); // will return 258823847556

echo $msisdn->getFormatted(); // will return "+258 823 847 556"

echo $msisdn->getFullNumber(); // will return +258823847556
```

### Funções baseadas em operadoras

Pode ser que lhe convenha realizar algumas funções baseadas na operadora raiz do contacto.

Nesta área assumisse que:

1. **82 ou 83** são prefixos da TMcel
1. **84 ou 85** são prefixos da Vodacom
1. **86 ou 87** são prefixos da Movitel

```php
$mobileNumber = '823847555';

$msisdn = new Msisdn($mobileNumber);

echo $msisdn->getOperator(); // will return TMCEL
```

### Verificando a operadora

Assuma por exemplo que queiras saber se o contacto é de uma determinada operadora.


```php
$mobileNumber = '823847555';

$msisdn = new Msisdn($mobileNumber);

echo $msisdn->isVodacom(); // will return false
echo $msisdn->isTmcel(); // will return true
echo $msisdn->isMovitel(); // will return false
```

```php
$mobileNumber = '847386728';

$msisdn = new Msisdn($mobileNumber);


echo $msisdn->isVodacom(); // will return true
echo $msisdn->isTmcel(); // will return false
echo $msisdn->isMovitel(); // will return false
```
### Prefixo da operadora

De igual forma você pode coletar o prefixo da operadora apenas.

```php
$mobileNumber = '823847556';

$msisdn = new Msisdn($mobileNumber);

echo $msisdn->getPrefix(); // will return 82
```

### Integração as validações Laravel

Este pacote já traz consigo a integração com as validações laravel, onde poderá facilmente efetuar validações aos seus `HTTP requests`.


```php
"msisdn"            // This validates mozambican mobile number
"msisdn_vodacom"    // This just vodacom mobile number
"msisdn_movitel"    // This just movitel mobile number
"msisdn_tmcel"      // This just tmcel mobile number
```

Example

```php

    $validated = $request->validate([
        'telefone' => 'bail|required|msisdn',
        'contacto_vodacom' => 'bail|required|msisdn_vodacom',
        'contacto_movitel' => 'bail|required|msisdn_movitel',
        'contacto_tmcel' => 'bail|required|msisdn_tmcel',
    ]);

```

## Credits

- [Samuel Bié][link-author]
- [Pessoal de um Pacote das Filipinas][core-proc]
 
## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/coreproc/msisdn-ph.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/CoreProc/msisdn-ph-php/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/CoreProc/msisdn-ph-php.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/CoreProc/msisdn-ph-php.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/coreproc/msisdn-ph.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/coreproc/msisdn-ph
[link-travis]: https://travis-ci.org/CoreProc/msisdn-ph-php
[link-scrutinizer]: https://scrutinizer-ci.com/g/CoreProc/msisdn-ph-php/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/CoreProc/msisdn-ph-php
[link-downloads]: https://packagist.org/packages/coreproc/msisdn-ph
[link-author]: https://github.com/chrisbjr
[core-proc]: ../../contributors
