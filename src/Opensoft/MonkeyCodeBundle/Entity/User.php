<?php
/**
 * Copyright (c) 2013 Molchanov Ivan
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
 * the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 * FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 * COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 * IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 * CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace Opensoft\MonkeyCodeBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Opensoft\MonkeyCodeBundle\Entity\User
 *
 * @ORM\Entity
 *
 * @author Ivan Molchanov <ivan.molchanov@opensoftdev.ru>
 */
class User extends BaseEntity implements UserInterface
{

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
     * @ORM\OneToMany(targetEntity="Opensoft\MonkeyCodeBundle\Entity\Message", mappedBy="user", cascade={"all"})
     *
     * @var ArrayCollection|Message[]
     */
    protected $messages;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->messages = new ArrayCollection();
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
        return array('ROLE_USER');
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
