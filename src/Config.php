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
        if (! isset($di->values['cookie'])) {
            $di->values['cookie'] = $this->cookie;
        }

        $di->params[Factory::class]['cookie'] = $di->lazyValue('cookie');

        $di->set(
            'aura/auth:factory',
            $di->lazyNew(Factory::class)
        );

        if (! $di->has('aura/auth:adapter')) {
            $di->set(
                'aura/auth:adapter',
                $di->lazyNew(NullAdapter::class)
            );
        }

        $di->set(
            'aura/auth:auth',
            $di->lazyGetCall('aura/auth:factory', 'newInstance')
        );

        $di->set(
            'aura/auth:login',
            $di->lazyGetCall(
                'aura/auth:factory',
                'newLoginService',
                $di->lazyGet('aura/auth:adapter')
            )
        );

        $di->set(
            'aura/auth:logout',
            $di->lazyGetCall(
                'aura/auth:factory',
                'newLogoutService',
                $di->lazyGet('aura/auth:adapter')
            )
        );

        $di->set(
            'aura/auth:resume',
            $di->lazyGetCall(
                'aura/auth:factory',
                'newResumeService',
                $di->lazyGet('aura/auth:adapter')
            )
        );
    }
}
