<?php namespace Leechael\Laraoauth;

use OAuth\ServiceFactory;
use OAuth\Common\Consumer\Credentials;
use OAuth\Common\Http\Client\CurlClient;

class Manager {

    protected $config;

    public function __construct($config, $session)
    {
        $this->config = $config;

        $this->storage = new TokenStorage($session);

        $this->factory = new ServiceFactory;
        $this->factory->setHttpClient(new CurlClient);

        $services = $this->config->get('oauth.services', []);

        foreach ($services as $name => $class) {
            $this->register($name, $class);
        }
    }

    public function register($name, $class)
    {
        $this->factory->registerService($name, $class);
        return $this;
    }

    public function make($name, $url=null, array $scope=[])
    {
        $config = $this->config->get("services.{$name}", []);
        if (!$config) {
            throw new NotYetConfigured;
        }
        $scope = $scope ?: array_get($config, 'scope', []);
        $credentials = new Credentials($config['appid'], $config['secret'], $url);
        return $this->factory->createService($name, $credentials, $this->storage,
            $scope);
    }
}