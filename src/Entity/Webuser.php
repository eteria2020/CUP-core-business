<?php

namespace BusinessCore\Entity;

use Doctrine\ORM\Mapping as ORM;
use BjyAuthorize\Provider\Role\ProviderInterface;

/**
 * Webuser
 *
 * @ORM\Table(name="webuser",schema="business",uniqueConstraints={@ORM\UniqueConstraint(name="email_idx", columns={"email"})})
 * @ORM\Entity(repositoryClass="BusinessCore\Entity\Repository\WebuserRepository")
 */
class Webuser implements ProviderInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="business.webuser_id_seq", allocationSize=1, initialValue=1)
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
     * @var Business
     * @ORM\ManyToOne(targetEntity="Business")
     * @ORM\JoinColumn(name="business_code", referencedColumnName="code")
     */
    private $business;

    /**
     * Webuser constructor.
     * @param string $email
     * @param string $displayName
     * @param string $password
     * @param Business $business
     * @param $role
     */
    public function __construct($email, $displayName, $password, Business $business, $role)
    {
        $this->email = $email;
        $this->displayName = $displayName;
        $this->password = $password;
        $this->business = $business;
        $this->role = $role;
    }

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
     * Get displayName
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
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
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->email;
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
    public function getRoles()
    {
        return array($this->getRole());
    }

    /**
     * @return Business
     */
    public function getBusiness()
    {
        return $this->business;
    }
}
