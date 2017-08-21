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
use Aura\Auth\Adapter\NullAdapter;

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
            Auth\AuthFactory::class,
            $di->lazyNew(Auth\AuthFactory::class)
        );

        if (! $di->has(Auth\Adapter::class)) {
            $di->set(
                Auth\Adapter::class,
                $di->lazyNew(Auth\Adapter\NullAdapter::class)
            );
        }

        $di->set(
            Auth\Auth::class,
            $di->lazyGetCall(Auth\AuthFactory::class, 'newInstance')
        );

        $di->set(
            Auth\Service\LoginService::class,
            $di->lazyGetCall(
                Auth\AuthFactory::class,
                'newLoginService',
                $di->lazyGet(Auth\Adapter::class)
            )
        );

        $di->set(
            Auth\Service\LogoutService::class,
            $di->lazyGetCall(
                Auth\AuthFactory::class,
                'newLogoutService',
                $di->lazyGet(Auth\Adapter::class)
            )
        );

        $di->set(
            Auth\Service\ResumeService::class,
            $di->lazyGetCall(
                Auth\AuthFactory::class,
                'newResumeService',
                $di->lazyGet(Auth\Adapter::class)
            )
        );
    }
}
