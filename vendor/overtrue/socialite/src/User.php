<?php

/*
 * This file is part of the overtrue/socialite.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Overtrue\Socialite;

use ArrayAccess;
use JsonSerializable;

/**
 * Class User.
 */
class User implements ArrayAccess, UserInterface, JsonSerializable, \Serializable
{
    use HasAttributes;

    /**
     * User constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return string
     */
    public function getId()
    {
        return $this->getAttribute('id');
    }

    /**
     * Get the username for the user.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->getAttribute('username', $this->getId());
    }

    /**
     * Get the nickname / username for the user.
     *
     * @return string
     */
    public function getNickname()
    {
        return $this->getAttribute('nickname');
    }

    /**
     * Get the full name of the user.
     *
     * @return string
     */
    public function getName()
    {
        return $this->getAttribute('name');
    }

    /**
     * Get the e-mail address of the user.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->getAttribute('email');
    }

    /**
     * Get the avatar / image URL for the user.
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->getAttribute('avatar');
    }

    /**
     * Set the token on the user.
     *
     * @param \Overtrue\Socialite\AccessTokenInterface $token
     *
     * @return $this
     */
    public function setToken(AccessTokenInterface $token)
    {
        $this->setAttribute('token', $token->getToken());
        $this->setAttribute('access_token', $token->getToken());

        if (\is_callable([$token, 'getRefreshToken'])) {
            $this->setAttribute('refresh_token', $token->getRefreshToken());
        }

        return $this;
    }

    /**
     * @param string $provider
     *
     * @return $this
     */
    public function setProviderName($provider)
    {
        $this->setAttribute('provider', $provider);

        return $this;
    }

    /**
     * @return string
     */
    public function getProviderName()
    {
        return $this->getAttribute('provider');
    }

    /**
     * Get the authorized token.
     *
     * @return \Overtrue\Socialite\AccessToken
     */
    public function getToken()
    {
        return new AccessToken([
            'access_token' => $this->getAccessToken(),
            'refresh_token' => $this->getAttribute('refresh_token')
        ]);
    }

    /**
     * Get user access token.
     *
     * @return string
     */
    public function getAccessToken()
    {
        return $this->getAttribute('token') ?: $this->getAttribute('access_token');
    }

    /**
     * Get user refresh token.
     *
     * @return string
     */
    public function getRefreshToken()
    {
        return $this->getAttribute('refresh_token');
    }

    /**
     * Get the original attributes.
     *
     * @return array
     */
    public function getOriginal()
    {
        return $this->getAttribute('original');
    }

    /**
     * {@inheritdoc}
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return $this->attributes;
    }

    /**
     * String representation of object.
     * @link https://php.net/manual/en/serializable.serialize.php
     * @return string|null The string representation of the object or null
     * @throws Exception Returning other type than string or null
     */
    #[\ReturnTypeWillChange]
    public function serialize()
    {
        return serialize($this->attributes);
    }

    /**
     * Constructs the object.
     *
     * @see  https://php.net/manual/en/serializable.unserialize.php
     *
     * @param string $serialized <p>
     *                           The string representation of the object.
     *                           </p>
     *
     * @since 5.1.0
     */
    #[\ReturnTypeWillChange]
    public function unserialize($serialized)
    {
        $this->attributes = unserialize($serialized) ?: [];
    }

    public function __serialize()
    {
        $this->serialize();
    }

    public function __unserialize($serialized)
    {
        $this->unserialize($serialized);
    }
}
