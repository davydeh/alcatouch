<?php

namespace Proyecto\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Proyecto\AppBundle\Entity\Element;

/**
 * @ORM\Entity
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"node"="Node","manhole"="Manhole","outfall"="OutFall"})
 */
abstract class Node extends Element {

    /**
     * @ORM\Column(type="float", name="x", nullable=false, scale=0, precision=0)
     */
    protected $x;

    /**
     * @ORM\Column(type="float", name="y", nullable=false, scale=0, precision=0)
     */
    protected $y;

    /**
     * @ORM\Column(type="float", name="r", nullable=false, scale=0, precision=0)
     */
    protected $r;

    /**
     * @ORM\Column(type="string", name="nodeId", unique=false, nullable=false)
     */
    protected $nodeId;

    public function __construct() {
        parent::__construct();
    }

    /**
     * Set x
     *
     * @param float $x
     * @return Node
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
     * @return Node
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
     * @return Node
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
     * Set nodeId
     *
     * @param integer nodeId
     * @return Node
     */
    public function setNodeId($nodeId) {
        $this->nodeId = $nodeId;

        return $this;
    }

    /**
     * Get nodeId
     *
     * @return integer 
     */
    public function getNodeId() {
        return $this->nodeId;
    }

}