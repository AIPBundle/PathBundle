<?php

namespace Aip\ProfilageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Etape
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Aip\ProfilageBundle\Repository\EtapeRepository")
 */
class Etape
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
     * @ORM\Column(name="competence", type="string", length=255)
     */
    private $competence;
    /**
     * @var string
     *
     * @ORM\Column(name="conditionvisibilite", type="string", length=255)
     */
    private $conditionvisibilite;
    /**
     *
     *
     * @ORM\Column(name="etat", type="boolean", length=255,nullable=true)
     */
    private $etat;
    /**
     *
     *
     * @ORM\Column(name="initial", type="boolean", length=255,nullable=true)
     */
    private $initial;
    /**
     * @ORM\ManyToOne(
     *     targetEntity="Claroline\CoreBundle\Entity\User"
     * )
     * @ORM\JoinColumn(name="creator_id", onDelete="CASCADE", nullable=false)
     */
    protected $creator;
    /**
     * @ORM\ManyToOne(
     *     targetEntity="Aip\ProfilageBundle\Entity\Parcours",
     *     inversedBy="etape"
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
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    
    /**
     * @var string
     *
     * @ORM\Column(name="ressource", type="string", length=255)
     */
    private $ressource;
    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;
    
    /**
     * @var string
     *
     * @ORM\Column(name="nomressource", type="string", length=255)
     */
    private $nomressource;
    /**
     *
     *
     * @ORM\Column(name="node_id", type="integer", length=255)
     */
    private $nodeid;
    

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
     * Set competence
     *
     * @param string $competence
     * @return Etape
     */
    public function setCompetence($competence)
    {
        $this->competence = $competence;

        return $this;
    }

    /**
     * Get competence
     *
     * @return string 
     */
    public function getCompetence()
    {
        return $this->competence;
    }

    
    public function getRessource()
    {
    	return $this->ressource;
    }
    
    public function setRessource($ressource)
    {
    	$this->ressource= $ressource;
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
    public function getPublicationDate()
    {
    	return $this->publicationDate;
    }
    
    public function setPublicationDate($publicationDate)
    {
    	$this->publicationDate = $publicationDate;
    }
    public function getNom()
    {
    	return $this->nom;
    }
    
    public function setNom($nom)
    {
    	$this->nom = $nom;
    }
    public function getNodeid()
    {
    	return $this->nodeid;
    }
    
    public function setNodeid($nodeid)
    {
    	$this->nodeid = $nodeid;
    }
    public function getNomressource()
    {
    	return $this->nomressource;
    }
    
    public function setNomressource($nomressource)
    {
    	$this->nomressource = $nomressource;
    }
    public function getDescription()
    {
    	return $this->description;
    }
    
    public function setDescription($description)
    {
    	$this->description = $description;
    }
    public function getConditionvisibilite()
    {
    	return $this->conditionvisibilite;
    }
    
    public function setConditionvisibilite($conditionvisibilite)
    {
    	$this->conditionvisibilite = $conditionvisibilite;
    }
    public function isEtat()
    {
    	return $this->etat;
    }
    
    public function setEtat($etat)
    {
    	$this->etat = $etat;
    }
    public function isInitial()
    {
    	return $this->initial;
    }
    
    public function setInitial($initial)
    {
    	$this->initial = $initial;
    }
}
