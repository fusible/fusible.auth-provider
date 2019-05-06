# fusible.auth-provider
[Service Provider] for [Aura\Auth]

[![Latest version][ico-version]][link-packagist]
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]

## Installation
```
composer require fusible/auth-provider
```

## Usage

See: [Service Provider] and [Fusible\AuraProvider].
```php
$provider = new Fusible\AuthProvider\AuthProvider();
foreach ($provider->getFactories() as $name => $factory) {
    $container->set($name, $factory);
}
```



[Service Provider]: https://github.com/container-interop/service-provider
[Fusible\AuraProvider]: https://github.com/fusible/aura-provider
[Aura\Auth]: https://github.com/auraphp/Aura.Auth
[Aura\Di (3.x)]: https://github.com/auraphp/Aura.Di/tree/3.x
[Aura\Di docs]: https://github.com/auraphp/Aura.Di/blob/3.x/docs/config.md

[ico-version]: https://img.shields.io/packagist/v/fusible/auth-provider.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/fusible/fusible.auth-provider/develop.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/fusible/fusible.auth-provider.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/fusible/fusible.auth-provider.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/fusible/auth-provider
[link-travis]: https://travis-ci.org/fusible/fusible.auth-provider
[link-scrutinizer]: https://scrutinizer-ci.com/g/fusible/fusible.auth-provider
[link-code-quality]: https://scrutinizer-ci.com/g/fusible/fusible.auth-provider
