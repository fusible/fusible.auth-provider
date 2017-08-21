<?php
// @codingStandardsIgnoreFile

namespace Fusible\AuthProvider;

use Aura\Di\AbstractContainerConfigTest;
use Aura\Auth;

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
            Config::class
        ];
    }

    public function provideGet()
    {
        return [
            [ Auth\AuthFactory::class, Auth\AuthFactory::class],
            [ Auth\Adapter::class, Auth\Adapter\NullAdapter::class ],
            [ Auth\Auth::class, Auth\Auth::class ],
            [ Auth\Service\LoginService::class, Auth\Service\LoginService::class ],
            [ Auth\Service\LogoutService::class, Auth\Service\LogoutService::class],
            [ Auth\Service\ResumeService::class, Auth\Service\ResumeService::class]
        ];
    }

    public function provideNewInstance()
    {
        return [
            [Auth\AuthFactory::class]
        ];
    }

}

