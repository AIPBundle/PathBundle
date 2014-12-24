<?php

namespace Aip\ProfilageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EnsembleCompetence
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Aip\ProfilageBundle\Repository\EnsembleCompetenceRepository")
 */
class EnsembleCompetence
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
     * @ORM\Column(name="titreens", type="string", length=255)
     */
    private $titreens;

    /**
     * @var string
     *
     * @ORM\Column(name="defens", type="string", length=255)
     */
    private $defens;
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
     *     inversedBy="ensemblecompetence"
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set titreens
     *
     * @param string $titreens
     * @return EnsembleCompetence
     */
    public function setTitreens($titreens)
    {
        $this->titreens = $titreens;

        return $this;
    }

    /**
     * Get titreens
     *
     * @return string 
     */
    public function getTitreens()
    {
        return $this->titreens;
    }

    /**
     * Set defens
     *
     * @param string $defens
     * @return EnsembleCompetence
     */
    public function setDefens($defens)
    {
        $this->defens = $defens;

        return $this;
    }

    /**
     * Get defens
     *
     * @return string 
     */
    public function getDefens()
    {
        return $this->defens;
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
}
