<?php

namespace Proyecto\AppBundle\Entity;

use FOS\UserBundle\Document\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"node"="Node","link"="Link","manhole"="Manhole","outfall"="OutFall","conduits"="Conduits"})
 */
class Element {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", name="name", length=255, unique=false, nullable=false)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", name="color", length=255, unique=false, nullable=false)
     */
    protected $color;

    /**
     * @ORM\ManyToOne(targetEntity="NomTipoElemento")
     * @ORM\JoinColumn(name="tipoElemento", referencedColumnName="id")
     */
    protected $tipoElemento;

    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="elements")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    protected $project;
    
    
    protected $discr;

    public function __construct() {
        
    }

    public function getId() {
        return $this->id;
    }
    
    /**
     * Set discr
     *
     * @param string $discr
     */
    public function setDiscr($discr)
    {
        $this->discr = $discr;
    }

    /**
     * Get discr
     *
     * @return string
     */
    public function getDiscr()
    {
        return $this->discr;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Element
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set color
     *
     * @param string $color
     * @return Element
     */
    public function setColor($color) {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string 
     */
    public function getColor() {
        return $this->color;
    }

    /**
     * Set tipoElemento
     *
     * @param \Proyecto\AppBundle\Entity\NomTipoElemento $tipoElemento
     * @return Element
     */
    public function setTipoElemento(\Proyecto\AppBundle\Entity\NomTipoElemento $tipoElemento = null) {
        $this->tipoElemento = $tipoElemento;

        return $this;
    }

    /**
     * Get tipoElemento
     *
     * @return \Proyecto\AppBundle\Entity\NomTipoElemento 
     */
    public function getTipoElemento() {
        return $this->tipoElemento;
    }

    /**
     * Set project
     *
     * @return \Proyecto\AppBundle\Entity\Element
     */
    public function setProject(\Proyecto\AppBundle\Entity\Project $project) {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return \Proyecto\AppBundle\Entity\Project 
     */
    public function getProject() {
        return $this->project;
    }

}