<?php
/**
 * This file is part of ONP.
 *
 * Copyright (c) 2013 Opensoft (http://opensoftdev.com)
 *
 * The unauthorized use of this code outside the boundaries of
 * Opensoft is prohibited.
 */

namespace Opensoft\MonkeyCodeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Opensoft\MonkeyCodeBundle\Entity\User
 *
 * @ORM\Entity
 *
 * @author Ivan Molchanov <ivan.molchanov@opensoftdev.ru>
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var integer
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $login;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    protected $password;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    protected $salt;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $email;

    /**
     * @ORM\Column(type="string", unique=true)
     *
     * @var string
     */
    protected $firstName;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $secondName;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $lastName;

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws \Exception
     */
    public function __call($name, $arguments = [])
    {
        $type = substr($name, 0, 3);
        $name = strtolower(substr($name, 3, strlen($name)));
        if($type === 'get') {
            $reflection = new \ReflectionClass($this);
            if (!$reflection->hasProperty($name)) {
                throw new Exception('Unknown property');
            }
            return $reflection->getProperty($name)->getValue($this);
        } else if($type === 'set') {
            if (count($arguments) > 1) {
                throw new Exception('Only one argument is possible for this function');
            }
            $reflection = new \ReflectionClass($this);
            if (!$reflection->hasProperty($name)) {
                throw new Exception('Unknown property');
            }
            $property = $reflection->getProperty($name);
            $property->setAccessible(true);
            $property->setValue($this, $arguments[0]);
            $property->setAccessible(false);

            return $this;
        } else {
            throw new Exception('Unknown function');
        }
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return Role[] The user roles
     */
    public function getRoles()
    {
        return 'ROLE_USER';
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->login;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        $this->password = null;
    }
}
