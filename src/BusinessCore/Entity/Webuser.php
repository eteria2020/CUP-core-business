<?php

namespace BusinessCore\Entity;

use Doctrine\ORM\Mapping as ORM;
use BjyAuthorize\Provider\Role\ProviderInterface;

/**
 * Webuser
 *
 * @ORM\Table(name="webuser",schema="business",uniqueConstraints={@ORM\UniqueConstraint(name="email_idx", columns={"email"})})
 * @ORM\Entity(repositoryClass="BusinessCore\Entity\Repository\WebuserRepository", readOnly=true)
 */
class Webuser implements ProviderInterface {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="display_name", type="string", length=100, nullable=false)
     */
    private $displayName;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=64, nullable=false)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=100, nullable=false)
     */
    private $role;

    /**
     * @var string
     * @ORM\OneToOne(targetEntity="Business")
     * @ORM\JoinColumn(name="business_code", referencedColumnName="code")
     */
    private $business;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set displayName
     *
     * @param string $displayName
     *
     * @return Webuser
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;

        return $this;
    }

    /**
     * Get displayName
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }
    
    /**
     * Set email
     *
     * @param string $email
     *
     * @return Webuser
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return Webuser
     */
    public function setUsername($username)
    {
        // do nothing, here only to adhere with UserInterface

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Webuser
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set role
     *
     * @param string $role
     *
     * @return Webuser
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Get role (needed by ProviderInterface)
     *
     * @return array
     */
    public function getRoles() {
        return array($this->getRole());
    }

    /**
     * @return string
     */
    public function getBusiness()
    {
        return $this->business;
    }


}
