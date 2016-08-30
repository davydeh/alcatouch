<?php

namespace Proyecto\AppBundle\Entity;

use FOS\UserBundle\Document\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Proyecto\AppBundle\Entity\Link;

/**
 * @ORM\Entity
 * @ORM\Table(name="conduits")
 */
class Conduits extends Link {

    /**
     * @ORM\Column(type="float", name="diameter", nullable=false, scale=0, precision=0)
     */
    protected $diameter;

    /**
     * @ORM\Column(type="float", name="manning", nullable=false, scale=0, precision=0)
     */
    protected $manning;

    /**
     * @ORM\OneToMany(targetEntity="ShapeConduits", mappedBy="conduits")
     */
    protected $shapec;

    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Set diameter
     *
     * @param float $diameter
     * @return Conduits
     */
    public function setDiameter($diameter) {
        $this->diameter = $diameter;

        return $this;
    }

    /**
     * Get diameter
     *
     * @return float 
     */
    public function getDiameter() {
        return $this->diameter;
    }

    /**
     * Set manning
     *
     * @param float $manning
     * @return Conduits
     */
    public function setManning($manning) {
        $this->manning = $manning;

        return $this;
    }

    /**
     * Get manning
     *
     * @return float 
     */
    public function getManning() {
        return $this->manning;
    }

    /**
     * Set length
     *
     * @param float $length
     * @return Conduits
     */
    public function setLength($length) {
        $this->length = $length;

        return $this;
    }

    /**
     * Get length
     *
     * @return float 
     */
    public function getLength() {
        return $this->length;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Conduits
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
     * @return Conduits
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
     * Add shapec
     *
     * @param \Proyecto\AppBundle\Entity\ShapeConduits $shapec
     * @return Conduits
     */
    public function addShapec(\Proyecto\AppBundle\Entity\ShapeConduits $shapec) {
        $this->shapec[] = $shapec;

        return $this;
    }

    /**
     * Remove shapec
     *
     * @param \Proyecto\AppBundle\Entity\ShapeConduits $shapec
     */
    public function removeShapec(\Proyecto\AppBundle\Entity\ShapeConduits $shapec) {
        $this->shapec->removeElement($shapec);
    }

    /**
     * Get shapec
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getShapec() {
        return $this->shapec;
    }

    /**
     * Set startNode
     *
     * @param \Proyecto\AppBundle\Entity\Node $startNode
     * @return Conduits
     */
    public function setStartNode(\Proyecto\AppBundle\Entity\Node $startNode = null) {
        $this->startNode = $startNode;

        return $this;
    }

    /**
     * Get startNode
     *
     * @return \Proyecto\AppBundle\Entity\Node 
     */
    public function getStartNode() {
        return $this->startNode;
    }

    /**
     * Set endNode
     *
     * @param \Proyecto\AppBundle\Entity\Node $endNode
     * @return Conduits
     */
    public function setEndNode(\Proyecto\AppBundle\Entity\Node $endNode = null) {
        $this->endNode = $endNode;

        return $this;
    }

    /**
     * Get endNode
     *
     * @return \Proyecto\AppBundle\Entity\Node 
     */
    public function getEndNode() {
        return $this->endNode;
    }

    /**
     * Set tipoElemento
     *
     * @param \Proyecto\AppBundle\Entity\NomTipoElemento $tipoElemento
     * @return Conduits
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
     * Set inertElvStart
     *
     * @param float $inertElvStart
     * @return Link
     */
    public function setInvertElvStart($inertElvStart) {
        $this->inertElvStart = $inertElvStart;

        return $this;
    }
    
    /**
     * Get inertElvStart
     *
     * @return float 
     */
    public function getInvertElvStart() {
        return $this->inertElvStart;
    }
    
    /**
     * Set inertElvEnd
     *
     * @param float $inertElvEnd
     * @return Link
     */
    public function setInvertElvEnd($inertElvEnd) {
        $this->inertElvEnd = $inertElvEnd;

        return $this;
    }
    
    /**
     * Get inertElvEnd
     *
     * @return float 
     */
    public function getInvertElvEnd() {
        return $this->inertElvEnd;
    }
    
     /**
     * Set sameInvertAtNodes
     *
     * @param boolean $sameInvertAtNodes
     * @return Link
     */
    public function setSameInvertAtNodes($sameInvertAtNodes) {
        $this->sameInvertAtNodes = $sameInvertAtNodes;

        return $this;
    }
    
    /**
     * Get sameInvertAtNodes
     *
     * @return boolean 
     */
    public function getSameInvertAtNodes() {
        return $this->sameInvertAtNodes;
    }
}