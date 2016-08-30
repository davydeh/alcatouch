<?php

namespace Proyecto\UserBundle\Entity;

use FOS\UserBundle\Document\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Proyecto\AppBundle\Entity\Project;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=30, nullable=false)
     */
    protected $username;

    /**
     * Encrypted password. Must be persisted.
     *
     * @ORM\Column(type="string")
     */
    protected $password;

    /**
     * @ORM\OneToMany(targetEntity="Proyecto\AppBundle\Entity\Project", mappedBy="user")
     */
    protected $projects;


    public function __construct()
    {
        parent::__construct();
        // your own logic
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
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;
    
        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
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
     * Add project
     *
     * @param \Proyecto\AppBundle\Entity\Project $project
     * @return User
     */
    public function addProject(\Proyecto\AppBundle\Entity\Project $project)
    {
        $this->projects[] = $project;
    
        return $this;
    }

    /**
     * Remove project
     *
     * @param \Proyecto\AppBundle\Entity\Project $project
     */
    public function removeProject(\Proyecto\AppBundle\Entity\Project $project)
    {
        $this->projects->removeElement($project);
    }

    /**
     * Get project
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProjects()
    {
        return $this->projects;
    }
}