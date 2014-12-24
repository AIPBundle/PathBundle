<?php

namespace Aip\ProfilageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Modelusager
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Modelusager
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
     * @ORM\ManyToOne(
     *     targetEntity="Claroline\CoreBundle\Entity\User"
     * )
     * @ORM\JoinColumn(name="creator_id", onDelete="CASCADE", nullable=false)
     */
    protected $creator;

    /**
     * @var string
     *
     * @ORM\Column(name="cycledevie", type="string", length=255)
     */
    private $cycledevie;
    /**
     *
     *
     * @ORM\Column(name="aggregate_id", type="integer", length=255)
     */
    protected $aggregate;


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
     * @return Modelusager
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

    /**
     * Set cycledevie
     *
     * @param string $cycledevie
     * @return Modelusager
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
}
