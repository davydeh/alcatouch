<?php

namespace Proyecto\AppBundle\Entity;

use FOS\UserBundle\Document\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Proyecto\AppBundle\Entity\Node;

/**
 * @ORM\Entity
 * @ORM\Table(name="manhole")
 */
class Manhole extends Node {

    /**
     * @ORM\Column(type="float", name="groundelev", nullable=false, scale=0, precision=0)
     */
    protected $groundElev;
    
    /**
     * @ORM\Column(type="float", name="invertelev", nullable=false, scale=0, precision=0)
     */
    protected $invertElev;

    /**
     * @ORM\Column(type="float", name="inflow", nullable=true, scale=0, precision=0)
     */
    protected $inflow;

    public function __construct() {
        parent::__construct();
    }

    /**
     * Set groundElev
     *
     * @param float $groundElev
     * @return Manhole
     */
    public function setGroundElev($groundElev) {
        $this->groundElev = $groundElev;

        return $this;
    }

    /**
     * Get groundElev
     *
     * @return float 
     */
    public function getGroundElev() {
        return $this->groundElev;
    }
    
    
    /**
     * Set invertElev
     *
     * @param float $invertElev
     * @return Manhole
     */
    public function setInvertElev($invertElev) {
        $this->invertElev = $invertElev;

        return $this;
    }

    /**
     * Get invertElev
     *
     * @return float 
     */
    public function getInvertElev() {
        return $this->invertElev;
    }

    
    /**
     * Set inflow
     *
     * @param float $inflow
     * @return Manhole
     */
    public function setInflow($inflow) {
        $this->inflow = $inflow;

        return $this;
    }

    /**
     * Get inflow
     *
     * @return float 
     */
    public function getInflow() {
        return $this->inflow;
    }
    
    /**
     * Set x
     *
     * @param float $x
     * @return Manhole
     */
    public function setX($x) {
        $this->x = $x;

        return $this;
    }

    /**
     * Get x
     *
     * @return float 
     */
    public function getX() {
        return $this->x;
    }

    /**
     * Set y
     *
     * @param float $y
     * @return Manhole
     */
    public function setY($y) {
        $this->y = $y;

        return $this;
    }

    /**
     * Get y
     *
     * @return float 
     */
    public function getY() {
        return $this->y;
    }

    /**
     * Set r
     *
     * @param float $r
     * @return Manhole
     */
    public function setR($r) {
        $this->r = $r;

        return $this;
    }

    /**
     * Get r
     *
     * @return float 
     */
    public function getR() {
        return $this->r;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Manhole
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
     * @return Manhole
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
     * @return Manhole
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
     * Get maxDepth
     *
     * @return float 
     */
    public function getMaxDepth() {
        return $this->groundElev - $this->invertElev; 
    }

}