<?php namespace Leechael\Laraoauth;

use OAuth\Common\Token\TokenInterface;
use OAuth\Common\Storage\Exception\TokenNotFoundException;
use OAuth\Common\Storage\Exception\AuthorizationStateNotFoundException;

class TokenStorage implements \OAuth\Common\Storage\TokenStorageInterface
{

    protected $session;

    public function __construct($session)
    {
        $this->session = $session;
    }

    /**
     * {@inheritdoc}
     */
    public function retrieveAccessToken($service)
    {
        $key = "oauth.access_token.{$service}";
        if ($this->session->has($key)) {
            return unserialize($this->session->get($key));
        }
        throw new TokenNotFoundException;
    }

    /**
     * {@inheritdoc}
     */
    public function storeAccessToken($service, TokenInterface $token)
    {
        $key = "oauth.access_token.{$service}";
        $this->session->put($key, serialize($token));
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasAccessToken($service)
    {
        return $this->session->has("oauth.access_token.{$service}");
    }

    /**
     * {@inheritdoc}
     */
    public function clearToken($service)
    {
        $key = "oauth.access_token.{$service}";
        if ($this->session->has($key)) {
            $this->session->pull($key);
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function clearAllTokens()
    {
        $this->session->pull("oauth.access_token");
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function storeAuthorizationState($service, $state)
    {
        $key = "oauth.state.{$service}";
        $this->session->put($key, $state);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasAuthorizationState($service)
    {
        $key = "oauth.state.{$service}";
        return $this->session->has($key);
    }

    /**
     * {@inheritdoc}
     */
    public function retrieveAuthorizationState($service)
    {
        $key = "oauth.state.{$service}";
        if ($this->session->has($key)) {
            return $this->session->get($key);
        }
        throw new AuthorizationStateNotFoundException;
    }

    /**
     * {@inheritdoc}
     */
    public function clearAuthorizationState($service)
    {
        $key = "oauth.state.{$service}";
        $this->session->pull($key);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function clearAllAuthorizationStates()
    {
        $this->session->pull("oauth.state");
        return $this;
    }
}