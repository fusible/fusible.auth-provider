<?php
/**
 * Aura\Auth Provider for Aura\Di
 *
 * PHP version 5
 *
 * Copyright (C) 2016 Jake Johns
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 *
 * @category  Config
 * @package   Fusible\AuthProvider
 * @author    Jake Johns <jake@jakejohns.net>
 * @copyright 2016 Jake Johns
 * @license   http://jnj.mit-license.org/2016 MIT License
 * @link      https://github.com/fusible/fusible.auth-provider
 */

namespace Fusible\AuthProvider;

use Aura\Di\Container;
use Aura\Di\ContainerConfig;

use Aura\Auth;

/**
 * Config
 *
 * @category Config
 * @package  Fusible\AuthProvider
 * @author   Jake Johns <jake@jakejohns.net>
 * @license  http://jnj.mit-license.org/2016 MIT License
 * @link     https://github.com/fusible/fusible.auth-provider
 *
 * @see ContainerConfig
 */
class Config extends ContainerConfig
{
    const AUTH_ADAPTER = self::class . '::AUTH_ADAPTER';
    const FACTORY      = Auth\AuthFactory::class;
    const AUTH         = Auth\Auth::class;
    const LOGIN        = Auth\Service\LoginService::class;
    const LOGOUT       = Auth\Service\LogoutService::class;
    const RESUME       = Auth\Service\ResumeService::class;

    /**
     * Cookie
     *
     * @var array
     *
     * @access protected
     */
    protected $cookie;

    /**
     * __construct
     *
     * @param array $cookie Cookie array
     *
     * @access public
     */
    public function __construct(array $cookie = null)
    {
        $this->cookie = (null === $cookie) ? $_COOKIE : $cookie;
    }

    /**
     * Define Aura\Auth factories and services
     *
     * @param Container $di DI Container
     *
     * @return void
     *
     * @access public
     *
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
    public function define(Container $di)
    {
        if (! isset($di->values['_COOKIE'])) {
            $di->values['_COOKIE'] = $this->cookie;
        }

        $di->params[Auth\AuthFactory::class]['cookie'] = $di->lazyValue('_COOKIE');

        $di->set(
            self::FACTORY,
            $di->lazyNew(Auth\AuthFactory::class)
        );

        if (! $di->has(self::AUTH_ADAPTER)) {
            $di->set(
                self::AUTH_ADAPTER,
                $di->lazyNew(Auth\Adapter\NullAdapter::class)
            );
        }

        $di->set(
            self::AUTH,
            $di->lazyGetCall(Auth\AuthFactory::class, 'newInstance')
        );

        $di->set(
            self::LOGIN,
            $di->lazyGetCall(
                Auth\AuthFactory::class,
                'newLoginService',
                $di->lazyGet(self::AUTH_ADAPTER)
            )
        );

        $di->set(
            self::LOGOUT,
            $di->lazyGetCall(
                Auth\AuthFactory::class,
                'newLogoutService',
                $di->lazyGet(self::AUTH_ADAPTER)
            )
        );

        $di->set(
            self::RESUME,
            $di->lazyGetCall(
                Auth\AuthFactory::class,
                'newResumeService',
                $di->lazyGet(self::AUTH_ADAPTER)
            )
        );
    }
}
