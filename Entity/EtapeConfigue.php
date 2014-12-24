<?php

namespace Aip\ProfilageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EtapeConfigue
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Aip\ProfilageBundle\Repository\EtapeConfigueRepository")
 */
class EtapeConfigue
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
     * @var string
     *
     * @ORM\Column(name="cycledevie", type="string", length=255)
     */
    private $cycledevie;

    /**
     * @var string
     *
     * @ORM\Column(name="conditionvisibilite", type="string", length=255)
     */
    private $conditionusager;
    /**
     *
     *
     * @ORM\Column(name="aggregate_id", type="integer", length=255)
     */
    protected $aggregate;

    
    /**
     *
     *
     * @ORM\Column(name="cycle_id", type="integer", length=255)
     */
    private $cycleid;

    /**
     *
     *
     * @ORM\Column(name="cyclecondition_id", type="integer", length=255)
     */
    private $cycleconditionid;
    /**
     *
     *
     * @ORM\Column(name="etape_id", type="integer", length=255)
     */
    private $etapeid;


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
     * Set cycledevie
     *
     * @param string $cycledevie
     * @return EtapeConfigue
     */
    public function setCycledevie($cycledevie)
    {
        $this->cycledevie = $cycledevie;

        return $this;
    }

    /**
     * Get cycledevie
     *
     * @return string 
     */
    public function getCycledevie()
    {
        return $this->cycledevie;
    }

    /**
     * Set conditionusager
     *
     * @param string $conditionusager
     * @return EtapeConfigue
     */
    public function setConditionusager($conditionusager)
    {
        $this->conditionusager = $conditionusager;

        return $this;
    }

    /**
     * Get conditionusager
     *
     * @return string 
     */
    public function getConditionusager()
    {
        return $this->conditionusager;
    }

   
    
    public function setCycleid($cycleid)
    {
    	$this->cycleid = $cycleid;
    
    	return $this;
    }
    
    public function getCycleid()
    {
    	return $this->cycleid;
    }
    public function setCycleconditionid($cycleconditionid)
    {
    	$this->cycleconditionid = $cycleconditionid;
    
    	return $this;
    }
    
    public function getCycleconditionid()
    {
    	return $this->cycleconditionid;
    }
    public function setEtapeid($etapeid)
    {
    	$this->etapeid = $etapeid;
    
    	return $this;
    }
    
    public function getEtapeid()
    {
    	return $this->etapeid;
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
