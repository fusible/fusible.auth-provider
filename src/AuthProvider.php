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

use Aura\Auth\Adapter\AdapterInterface;
use Aura\Auth\Adapter\NullAdapter;
use Aura\Auth\Auth;
use Aura\Auth\AuthFactory;
use Aura\Auth\Service\LoginService;
use Aura\Auth\Service\LogoutService;
use Aura\Auth\Service\ResumeService;
use PDO;
use Psr\Container\ContainerInterface as Container;

/**
 * Config
 *
 * @category Config
 * @package  Fusible\AuthProvider
 * @author   Jake Johns <jake@jakejohns.net>
 * @license  http://jnj.mit-license.org/2016 MIT License
 * @link     https://github.com/fusible/fusible.auth-provider
 */
class AuthProvider
{
    /**
     * Auth Factory
     *
     * @var AuthFactory
     *
     * @access protected
     */
    protected $factory;

    /**
     * Service factories
     *
     * @var callable[]
     *
     * @access protected
     */
    protected $factories = [];

    /**
     * Adapter method
     *
     * @var string
     *
     * @access protected
     */
    protected $adapterMethod;

    /**
     * Adapter args
     *
     * @var array
     *
     * @access protected
     */
    protected $adapterArgs = [];

    /**
     * Create an Auth Provider
     *
     * @param AuthFactory $factory Auth factory
     *
     * @access public
     */
    public function __construct(AuthFactory $factory = null)
    {
        $this->factory = $factory ?: new AuthFactory($_COOKIE);

        $this->factories = [
            AuthFactory::class   => [$this, 'getAuthFactory'],
            Auth::class          => [$this, 'newAuth'],
            LoginService::class  => [$this, 'newLoginService'],
            LogoutService::class => [$this, 'newLogoutService'],
            ResumeService::class => [$this, 'newResumeService'],
            AdapterInterface::class => function() {
                return new NullAdapter();
            }
        ];
    }

    /**
     * Use htpasswd adapter
     */
    public function htpasswd(string $file)
    {
        return $this->useAdapter('newHtpasswdAdapter', [$file]);
    }

    /**
     * Use PDO Adapter
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function pdo(
        PDO $pdo, $verifier_spec, array $cols, $from, $where = null
    ) {
        return $this->useAdapter('newPdoAdapter', func_get_args());
    }

    /**
     * Use IMAP adapter
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function imap(
        $mailbox, $options = 0, $retries = 1, array $params = null
    ) {
        return $this->useAdapter('newImapAdapter', func_get_args());
    }

    /**
     * Use LDAP adapter
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function ldap(
        $server, $dnformat, array $options = array()
    ) {
        return $this->useAdapter('newLdapAdapter', func_get_args());
    }

    /**
     * Configure adapter factory
     */
    protected function useAdapter(string $method, array $args)
    {
        if (! method_exists($this->factory, $method)) {
            // @codeCoverageIgnoreStart
            throw new \InvalidArgumentException("Invalid adapter $method");
            // @codeCoverageIgnoreEnd
        }
        $this->adapterMethod = $method;
        $this->adapterArgs = $args;
        $this->factories[AdapterInterface::class] = [$this, 'newAdapter'];
        return $this;
    }

    /**
     * Get auth factory
     *
     * @return AuthFactory
     *
     * @access public
     */
    public function getAuthFactory() : AuthFactory
    {
        return $this->factory;
    }

    /**
     * New Auth from container
     *
     * @param Container $container container
     *
     * @return Auth
     *
     * @access public
     */
    public function newAuth(Container $container) : Auth
    {
        return $container
            ->get(AuthFactory::class)
            ->newInstance();
    }

    /**
     * New LoginService from container
     *
     * @param Container $container container
     *
     * @return LoginService
     *
     * @access public
     */
    public function newLoginService(Container $container) : LoginService
    {
        return $container
            ->get(AuthFactory::class)
            ->newLoginService($container->get(AdapterInterface::class));
    }

    /**
     * New LogoutService from container
     *
     * @param Container $container container
     *
     * @return LogoutService
     *
     * @access public
     */
    public function newLogoutService(Container $container) : LogoutService
    {
        return $container
            ->get(AuthFactory::class)
            ->newLogoutService($container->get(AdapterInterface::class));
    }

    /**
     * New ResumeService from container
     *
     * @param Container $container container
     *
     * @return ResumeService
     *
     * @access public
     */
    public function newResumeService(Container $container) : ResumeService
    {
        return $container
            ->get(AuthFactory::class)
            ->newResumeService($container->get(AdapterInterface::class));
    }

    /**
     * New AdapterInterface from container based on config
     *
     * @param Container $container container
     *
     * @return AdapterInterface
     *
     * @access public
     */
    public function newAdapter(Container $container) : AdapterInterface
    {
        return $container
            ->get(AuthFactory::class)
            ->{$this->adapterMethod}(...$this->adapterArgs);
    }

    /**
     * Get service factories
     *
     * @return callable[]
     *
     * @access public
     */
    public function getFactories() : array
    {
        return $this->factories;
    }

    /**
     * Get service extensions
     *
     * @return array
     *
     * @access public
     */
    public function getExtensions() : array
    {
        return [];
    }
}
