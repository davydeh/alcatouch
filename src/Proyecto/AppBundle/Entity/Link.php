<?php

namespace Proyecto\AppBundle\Entity;

use FOS\UserBundle\Document\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Proyecto\AppBundle\Entity\Element;

/**
 * @ORM\Entity
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"link"="Link","conduits"="Conduits"})
 */
abstract class Link extends Element {

    /**
     * @ORM\ManyToOne(targetEntity="Node")
     * @ORM\JoinColumn(name="startnode", referencedColumnName="id")
     */
    protected $startNode;

    /**
     * @ORM\ManyToOne(targetEntity="Node")
     * @ORM\JoinColumn(name="endnode", referencedColumnName="id")
     */
    protected $endNode;

    /**
     * @ORM\Column(type="float", name="length", nullable=false, scale=0, precision=0)
     */
    protected $length;

    /**
     * @ORM\Column(type="float", name="inertElvStart", nullable=false, scale=0, precision=0)
     */
    protected $inertElvStart;
    
    /**
     * @ORM\Column(type="float", name="inertElvEnd", nullable=false, scale=0, precision=0)
     */
    protected $inertElvEnd;
    
     /**
     * @ORM\Column(type="boolean", name="sameInvertAtStartNode", nullable=false, scale=0, precision=0)
     */
    protected $sameInvertAtStartNode;
    
     /**
     * @ORM\Column(type="boolean", name="sameInvertAtEndNode", nullable=false, scale=0, precision=0)
     */
    protected $sameInvertAtEndNode;
    
    public function __construct() {
        parent::__construct();
    }

    /**
     * Set length
     *
     * @param float $length
     * @return Link
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
     * Set sameInvertAtStartNode
     *
     * @param boolean $sameInvertAtStartNode
     * @return Link
     */
    public function setSameInvertAtStartNode($sameInvertAtStartNode) {
        $this->sameInvertAtStartNode = $sameInvertAtStartNode;

        return $this;
    }
    
    /**
     * Get sameInvertAtStartNode
     *
     * @return boolean 
     */
    public function getSameInvertAtStartNode() {
        return $this->sameInvertAtStartNode;
    }
    
    /**
     * Set sameInvertAtEndNode
     *
     * @param boolean $sameInvertAtEndNode
     * @return Link
     */
    public function setSameInvertAtEndNode($sameInvertAtEndNode) {
        $this->sameInvertAtEndNode = $sameInvertAtEndNode;

        return $this;
    }
    
    /**
     * Get sameInvertAtEndNodes
     *
     * @return boolean 
     */
    public function getSameInvertAtEndNode() {
        return $this->sameInvertAtEndNode;
    }

    /**
     * Set startNode
     *
     * @param \Proyecto\AppBundle\Entity\Node $startNode
     * @return Link
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
     * @return Link
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

}