<?php

namespace Proyecto\AppBundle\Entity;

use FOS\UserBundle\Document\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="nom_elemento")
 */

class NomTipoElemento
{

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer", name="id")
	 *  @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", name="description", length=255, unique=false, nullable=false)
	 */
	protected $description ;

	public function __construct()
	{
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
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

     /**
     * Set description
     *
     * @param string $description
     * @return NomTipoElemento
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }
	
	
}
