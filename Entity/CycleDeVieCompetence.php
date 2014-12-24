<?php

namespace Aip\ProfilageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use  Aip\ProfilageBundle\Entity\Cycle;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * CycleDeVieCompetence
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Aip\ProfilageBundle\Repository\CycleDeVieCompetenceRepository")
 */
class CycleDeVieCompetence
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
     * @ORM\Column(name="titrecycle", type="string", length=255)
     */
    private $titrecycle;
    /**
     * @var string
     *
     * @ORM\Column(name="defcycle", type="string", length=255)
     */
    private $defcycle;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $encours;
    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $acquistheorique;
    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $acquispratique;
    
    /**
     * @ORM\ManyToOne(
     *     targetEntity="Claroline\CoreBundle\Entity\User"
     * )
     * @ORM\JoinColumn(name="creator_id", onDelete="CASCADE", nullable=false)
     */
    protected $creator;
    
    /**
     * @ORM\ManyToOne(
     *     targetEntity="Aip\ProfilageBundle\Entity\GrilleCompetenceAggregate",
     *     inversedBy="cycledeviecompetence"
     * )
     * @ORM\JoinColumn(name="aggregate_id", onDelete="CASCADE", nullable=false)
     */
    protected $aggregate;
    /**
     * @ORM\Column(name="creation_date", type="datetime", nullable=false)
     */
    protected $creationDate;
    /**
     * @ORM\Column(name="publication_date", type="datetime", nullable=true)
     */
    protected $publicationDate;
    
    /**
     * @ORM\OneToMany(
     *     targetEntity="Aip\ProfilageBundle\Entity\CycleVie",
     *     mappedBy="cycle"
     * )
     */
    
    protected $cycledevie;
    
    public function getCycledevie()
    {
    	return $this->cycledevie;
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
     * Set titrecycle
     *
     * @param string $titrecycle
     * @return CycleDeVieCompetence
     */
    public function setTitrecycle($titrecycle)
    {
        $this->titrecycle = $titrecycle;

        return $this;
    }

    /**
     * Get titrecycle
     *
     * @return string 
     */
    public function getTitrecycle()
    {
        return $this->titrecycle;
    }

    public function getCreator()
    {
    	return $this->creator;
    }
    
    public function setCreator($creator)
    {
    	$this->creator = $creator;
    }
    public function getAggregate()
    {
    	return $this->aggregate;
    }
    
    public function setAggregate($aggregate)
    {
    	$this->aggregate= $aggregate;
    }
    public function getCreationDate()
    {
    	return $this->creationDate;
    }
    
    public function setCreationDate($creationDate)
    {
    	$this->creationDate = $creationDate;
    }
    public function getDefcycle()
    {
    	return $this->defcycle;
    }
    
    public function setDefcycle($defcycle)
    {
    	$this->defcycle = $defcycle;
    }
    public function getPublicationDate()
    {
    	return $this->publicationDate;
    }
    
    public function setPublicationDate($publicationDate)
    {
    	$this->publicationDate = $publicationDate;
    }
    public function isEncours()
    {
    	return $this->encours;
    }
    
    public function setEncours($encours)
    {
    	$this->encours = $encours;
    }
    public function isAcquistheorique()
    {
    	return $this->acquistheorique;
    }
    
    public function setAcquistheorique($acquistheorique)
    {
    	$this->acquistheorique = $acquistheorique;
    }
    public function isAcquispratique()
    {
    	return $this->acquispratique;
    }
    
    public function setAcquispratique($acquispratique)
    {
    	$this->acquispratique = $acquispratique;
    }
    
}
