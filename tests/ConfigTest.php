<?php
// @codingStandardsIgnoreFile

namespace Fusible\AuthProvider;

use Aura\Di\AbstractContainerConfigTest;

class ConfigTest extends AbstractContainerConfigTest
{
    protected function setUp()
    {
        @session_start();
        parent::setUp();
    }

    protected function getConfigClasses()
    {
        return [
            'Fusible\AuthProvider\Config'
        ];
    }

    public function provideGet()
    {
        return [
            [ 'aura/auth:factory', 'Aura\Auth\AuthFactory' ],
            [ 'aura/auth:adapter', 'Aura\Auth\Adapter\NullAdapter' ],
            [ 'aura/auth:auth', 'Aura\Auth\Auth' ],
            [ 'aura/auth:login', 'Aura\Auth\Service\LoginService' ],
            [ 'aura/auth:logout', 'Aura\Auth\Service\LogoutService'],
            [ 'aura/auth:resume', 'Aura\Auth\Service\ResumeService']
        ];
    }

    public function provideNewInstance()
    {
        return [
            ['Aura\Auth\AuthFactory']
        ];
    }

}

