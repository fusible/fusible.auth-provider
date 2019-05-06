<?php
// @codingStandardsIgnoreFile

namespace Fusible\AuthProvider;

use Aura\Auth;
use Aura\Auth\Adapter;
use Aura\Di\ContainerBuilder;
use PHPUnit\Framework\TestCase;

class AuthProviderTest extends TestCase
{
    protected $provides = [];

    protected $container;

    protected $provider;

    protected function setUp() : void
    {
        $builder = new ContainerBuilder();
        $this->container = $builder->newInstance();
        $this->provider = new AuthProvider();
    }

    protected function provide()
    {
        foreach ($this->provider->getFactories() as $name => $callable) {
            $factory = $this->container->lazy($callable, $this->container);
            $this->container->set($name, $factory);
            $this->provides[] = $name;
        }

        foreach ($this->provider->getExtensions() as $name => $modify) {
            if ($this->container->has($name)) {
                $modify($this->container, $this->container->get($name));
            }
        }
    }

    protected function assertInstances()
    {
        foreach ($this->provides as $name) {
            $this->assertInstanceOf($name, $this->container->get($name));
        }
    }

    protected function assertAdapter($expected)
    {
        $this->assertInstanceOf(
            $expected,
            $this->container->get(Adapter\AdapterInterface::class)
        );
    }

    public function testProvides()
    {
        $this->provide();
        $this->assertInstances();
    }

    public function testHtpasswd()
    {
        $this->provider->htpasswd('.htpasswd');
        $this->provide();
        $this->assertInstances();
        $this->assertAdapter(Adapter\HtpasswdAdapter::class);
    }

    public function testPdo()
    {
        $pdo = $this->createMock(\Pdo::class);
        $hash = new \Aura\Auth\Verifier\PasswordVerifier('md5');
        $cols = array('username', 'md5password');
        $from = 'accounts';
        $this->provider->pdo($pdo, $hash, $cols, $from);
        $this->provide();
        $this->assertInstances();
        $this->assertAdapter(Adapter\PdoAdapter::class);
    }

    public function testImap()
    {
        $this->provider->imap('foo');
        $this->provide();
        $this->assertInstances();
        $this->assertAdapter(Adapter\ImapAdapter::class);
    }

    public function testLdap()
    {
        $this->provider->ldap('foo', 'bar');
        $this->provide();
        $this->assertInstances();
        $this->assertAdapter(Adapter\LdapAdapter::class);
    }

}

