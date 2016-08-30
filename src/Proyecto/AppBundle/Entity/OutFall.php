<?php

namespace Proyecto\AppBundle\Entity;

use FOS\UserBundle\Document\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Proyecto\AppBundle\Entity\Node;

/**
 * @ORM\Entity
 * @ORM\Table(name="outfall")
 */
class OutFall extends Node {

    /**
     * @ORM\Column(type="float", name="invertelev", nullable=false, scale=0, precision=0)
     */
    protected $invertElev;

    /**
     * @ORM\Column(type="string", name="type", length=255, unique=false, nullable=false)
     */
    protected $type;

    public function __construct() {
        parent::__construct();
    }

    /**
     * @var float
     */
    protected $x;

    /**
     * @var float
     */
    protected $y;

    /**
     * @var float
     */
    protected $r;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $color;

    /**
     * @var \Proyecto\AppBundle\Entity\NomTipoElemento
     */
    protected $tipoElemento;

    /**
     * Set invertElev
     *
     * @param float $inertElv
     * @return OutFall
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
     * Set type
     *
     * @param string $type
     * @return OutFall
     */
    public function setType($type) {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Set x
     *
     * @param float $x
     * @return OutFall
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
     * @return OutFall
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
     * @return OutFall
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
     * @return OutFall
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
     * @return OutFall
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
     * @return OutFall
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

}