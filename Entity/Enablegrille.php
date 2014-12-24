<?php

namespace Aip\ProfilageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Enablegrille
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Enablegrille
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     *
     *
     * @ORM\Column(name="aggregate_id", type="integer", length=255)
     */
    protected $aggregate;
    

    /**
     * @var string
     *
     * @ORM\Column(name="enable", type="boolean", length=255)
     */
    private $enable;

    public function isEnable()
    {
    	return $this->enable;
    }
    
    public function setEnable($enable)
    {
    	$this->enable = $enable;
    }
    public function getAggregate()
    {
    	return $this->aggregate;
    }
    
    public function setAggregate($aggregate)
    {
    	$this->aggregate= $aggregate;
    }
}
