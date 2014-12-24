<?php

namespace Aip\ProfilageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * GrilleCompetence
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Aip\ProfilageBundle\Repository\GrilleCompetenceRepository")
 */
class GrilleCompetence
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
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="cycledevie", type="string", length=255)
     */
    private $cycledevie;
    /**
     * @var string
     *
     * @ORM\Column(name="ensembledef", type="string", length=255)
     */
    private $ensembledef;
    
    /**
     * @ORM\Column(length=1023, nullable=false)
     * @Assert\NotBlank()
     */
    protected $description;
    
    /**
     * @ORM\ManyToOne(
     *     targetEntity="Claroline\CoreBundle\Entity\User"
     * )
     * @ORM\JoinColumn(name="creator_id", onDelete="CASCADE", nullable=false)
     */
    protected $creator;
    
    /**
     * @ORM\Column(nullable=true)
     */
    protected $createurdegrille;
    /**
     * @ORM\Column(name="publication_date", type="datetime", nullable=true)
     */
    protected $publicationDate;
    
    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $visible;
    
    
    /**
     * @ORM\Column(name="visible_from", type="datetime", nullable=true)
     */
    protected $visibleFrom;
    
    /**
     * @ORM\Column(name="visible_until", type="datetime", nullable=true)
     */
    protected $visibleUntil;
    
    /**
     * @ORM\ManyToOne(
     *     targetEntity="Aip\ProfilageBundle\Entity\GrilleCompetenceAggregate",
     *     inversedBy="grillecompetence"
     * )
     * @ORM\JoinColumn(name="aggregate_id", onDelete="CASCADE", nullable=false)
     */
    protected $aggregate;
    /**
     * @ORM\Column(name="creation_date", type="datetime", nullable=false)
     */
    protected $creationDate;


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
     * Set titre
     *
     * @param string $titre
     */
    public function setTitre($titre)
    {
    	$this->titre = $titre;
    
    	return $this;
    }
    /**
     * Get titre
     *
     * @param string $titre
     */
    public function getTitre()
    {
    	return $this->titre;
    
    
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
    	$this->aggregate = $aggregate;
    }
    public function getCreationDate()
    {
    	return $this->creationDate;
    }
    
    public function setCreationDate($creationDate)
    {
    	$this->creationDate = $creationDate;
    }
    public function getDescription()
    {
    	return $this->description;
    }
    
    public function setDescription($description)
    {
    	$this->description = $description;
    }
    public function getCreateurdegrille()
    {
    	return $this->createurdegrille;
    }
    
    public function setCreateurdegrille($createurdegrille)
    {
    	$this->createurdegrille = $createurdegrille;
    }
    public function getPublicationDate()
    {
    	return $this->publicationDate;
    }
    
    public function setPublicationDate($publicationDate)
    {
    	$this->publicationDate = $publicationDate;
    }
    
   
    
    public function isVisible()
    {
    	return $this->visible;
    }
    
    
    public function setVisible($visible)
    {
    	$this->visible = $visible;
    }
    
    public function getVisibleFrom()
    {
    	return $this->visibleFrom;
    }
    
    public function setVisibleFrom($visibleFrom)
    {
    	$this->visibleFrom = $visibleFrom;
    }
    
    public function getVisibleUntil()
    {
    	return $this->visibleUntil;
    }
    
    public function setVisibleUntil($visibleUntil)
    {
    	$this->visibleUntil = $visibleUntil;
    }
    /**
     * Set cycledevie
     *
     * @param string $cycledevie
     */
    public function setCycleDeVie( $cycledevie)
    {
    	$this->cycledevie = $cycledevie;
    
    	return $this;
    }
    /**
     * Get cycledevie
     *
     * @param string $cycledevie
     */
    public function getCycledevie()
    {
    	return $this->cycledevie;
    
    
    }
    /**
     * Set ensembledef
     *
     * @param string $ensembledef
     */
    public function setEnsembledef($ensembledef)
    {
    	$this->ensembledef = $ensembledef;
    
    	return $this;
    }
    /**
     * Get ensembledef
     *
     * @param string $ensembledef
     */
    public function getEnsembledef()
    {
    	return $this->ensembledef;
    
    
    }
   
}
