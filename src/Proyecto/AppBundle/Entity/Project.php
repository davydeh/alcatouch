<?php

namespace Proyecto\AppBundle\Entity;

use FOS\UserBundle\Document\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Proyecto\UserBundle\Entity\User;

/**
 * @ORM\Entity
 * @ORM\Table(name="project")
 */
class Project {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $nombre;

    /**
     * @ORM\Column(type="datetime", name="fecha", nullable=true)
     */
    protected $fecha;

    /**
     * @ORM\Column(type="float", name="dx", nullable=false, scale=0, precision=4)
     */
    protected $dx;

    /**
     * @ORM\Column(type="float", name="dy", nullable=false, scale=0, precision=4)
     */
    protected $dy;

    /**
     * @ORM\Column(type="float", name="scale", nullable=false, scale=0, precision=4)
     */
    protected $scale;

    /**
     * @ORM\Column(type="float", name="limitmaxx", nullable=false, scale=0, precision=4)
     */
    protected $limitMaxX;

    /**
     * @ORM\Column(type="float", name="limitmaxy", nullable=false, scale=0, precision=4)
     */
    protected $limitMaxY;

    /**
     * @ORM\Column(type="float", name="limitminx", nullable=false, scale=0, precision=4)
     */
    protected $limitMinX;

    /**
     * @ORM\Column(type="float", name="limitminy", nullable=false, scale=0, precision=4)
     */
    protected $limitMinY;

    /**
     * @ORM\ManyToOne(targetEntity="Proyecto\UserBundle\Entity\User", inversedBy="projects")
     * @ORM\JoinColumn(name="user", referencedColumnName="id")
     */
    protected $user;

    /**
     * @ORM\OneToMany(targetEntity="Element", mappedBy="project")
     */
    protected $elements;

    public function __construct() {
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
     * Set nombre
     *
     * @param string $nombre
     * @return Project
     */
    public function setName($nombre) {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getName() {
        return $this->nombre;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Project
     */
    public function setFecha($fecha) {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha() {
        return $this->fecha;
    }

    /**
     * Set dx
     *
     * @param float $dx
     * @return Project
     */
    public function setDx($dx) {
        $this->dx = $dx;

        return $this;
    }

    /**
     * Get dx
     *
     * @return float 
     */
    public function getDx() {
        return $this->dx;
    }

    /**
     * Set dy
     *
     * @param float $dy
     * @return Project
     */
    public function setDy($dy) {
        $this->dy = $dy;

        return $this;
    }

    /**
     * Get dy
     *
     * @return float 
     */
    public function getDy() {
        return $this->dy;
    }

    /**
     * Set scale
     *
     * @param float $scale
     * @return Project
     */
    public function setScale($scale) {
        $this->scale = $scale;

        return $this;
    }

    /**
     * Get scale
     *
     * @return float 
     */
    public function getScale() {
        return $this->scale;
    }

    /**
     * Set limitMaxX
     *
     * @param float $limitMaxX
     * @return Project
     */
    public function setLimitMaxX($limitMaxX) {
        $this->limitMaxX = $limitMaxX;

        return $this;
    }

    /**
     * Get limitMaxX
     *
     * @return float 
     */
    public function getLimitMaxX() {
        return $this->limitMaxX;
    }

    /**
     * Set limitMaxY
     *
     * @param float $limitMaxY
     * @return Project
     */
    public function setLimitMaxY($limitMaxY) {
        $this->limitMaxY = $limitMaxY;

        return $this;
    }

    /**
     * Get limitMaxY
     *
     * @return float 
     */
    public function getLimitMaxY() {
        return $this->limitMaxY;
    }

    /**
     * Set limitMinX
     *
     * @param float $limitMinX
     * @return Project
     */
    public function setLimitMinX($limitMinX) {
        $this->limitMinX = $limitMinX;

        return $this;
    }

    /**
     * Get limitMinX
     *
     * @return float 
     */
    public function getLimitMinX() {
        return $this->limitMinX;
    }

    /**
     * Set limitMinY
     *
     * @param float $limitMinY
     * @return Project
     */
    public function setLimitMinY($limitMinY) {
        $this->limitMinY = $limitMinY;

        return $this;
    }

    /**
     * Get limitMinY
     *
     * @return float 
     */
    public function getLimitMinY() {
        return $this->limitMinY;
    }

    /**
     * Set user
     *
     * @param \Proyecto\UserBundle\Entity\User $user
     * @return Project
     */
    public function setUser(\Proyecto\UserBundle\Entity\User $user = null) {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Proyecto\UserBundle\Entity\User 
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Add element
     *
     * @param \Proyecto\AppBundle\Entity\Elemento $element
     * @return Project
     */
    public function addElement(\Proyecto\AppBundle\Entity\Element $element) {
        $this->elements[] = $element;

        return $this;
    }

    /**
     * Remove element
     *
     * @param \Proyecto\AppBundle\Entity\Elemento $element
     */
    public function removeElement(\Proyecto\AppBundle\Entity\Element $element) {
        $this->elements->removeElement($element);
    }

    /**
     * Get elements
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getElements() {
        return $this->elements->toArray();
    }

}