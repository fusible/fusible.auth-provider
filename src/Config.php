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

use Aura\Auth\AuthFactory as Factory;
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
    const COOKIE = 'cookie';

    const FACTORY = 'aura/auth:factory';
    const ADAPTER = 'aura/auth:adapter';

    const AUTH   = 'aura/auth:auth';

    const LOGIN  = 'aura/auth:login';
    const LOGOUT = 'aura/auth:logout';
    const RESUME = 'aura/auth:resume';


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
        if (! isset($di->values[static::COOKIE])) {
            $di->values[static::COOKIE] = $this->cookie;
        }

        $di->params[Factory::class]['cookie'] = $di->lazyValue(static::COOKIE);

        $di->set(
            static::FACTORY,
            $di->lazyNew(Factory::class)
        );

        if (! $di->has(static::ADAPTER)) {
            $di->set(
                static::ADAPTER,
                $di->lazyNew(NullAdapter::class)
            );
        }

        $di->set(
            static::AUTH,
            $di->lazyGetCall(static::FACTORY, 'newInstance')
        );

        $di->set(
            static::LOGIN,
            $di->lazyGetCall(
                static::FACTORY,
                'newLoginService',
                $di->lazyGet(static::ADAPTER)
            )
        );

        $di->set(
            static::LOGOUT,
            $di->lazyGetCall(
                static::FACTORY,
                'newLogoutService',
                $di->lazyGet(static::ADAPTER)
            )
        );

        $di->set(
            static::RESUME,
            $di->lazyGetCall(
                static::FACTORY,
                'newResumeService',
                $di->lazyGet(static::ADAPTER)
            )
        );
    }
}
