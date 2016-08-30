<?php

namespace Proyecto\AppBundle\Entity;

use FOS\UserBundle\Document\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="shape_conduits")
 */
class ShapeConduits {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="text", name="description")
     */
    protected $description;

    /**
     * @ORM\ManyToOne(targetEntity="Conduits", inversedBy="shapec")
     * @ORM\JoinColumn(name="conduits", referencedColumnName="id")
     */
    protected $conduits;

    public function __construct() {
        //parent::__construct();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return ShapeConduits
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set conduits
     *
     * @param \Proyecto\AppBundle\Entity\Conduits $conduits
     * @return ShapeConduits
     */
    public function setConduits(\Proyecto\AppBundle\Entity\Conduits $conduits = null) {
        $this->conduits = $conduits;

        return $this;
    }

    /**
     * Get conduits
     *
     * @return \Proyecto\AppBundle\Entity\Conduits 
     */
    public function getConduits() {
        return $this->conduits;
    }

}