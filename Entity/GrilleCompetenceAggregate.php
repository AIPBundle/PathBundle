<?php

namespace Aip\ProfilageBundle\Entity;
use Claroline\CoreBundle\Entity\Resource\AbstractResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="claro_grillecompetence_aggregate")
 */
class GrilleCompetenceAggregate extends AbstractResource
{
   
	/**
	 * @ORM\OneToMany(
	 *     targetEntity="Aip\ProfilageBundle\Entity\GrilleCompetence",
	 *     mappedBy="aggregate"
	 * )
	 */
	
	protected $grillecompetence;

    public function getGrillecompetence()
    {
        return $this->grillecompetence;
    }
    /**
     * @ORM\OneToMany(
     *     targetEntity="Aip\ProfilageBundle\Entity\CycleDeVieCompetence",
     *     mappedBy="aggregate"
     * )
     */
    
    protected $cycledeviecompetence;
    
    public function getCycledeviecompetence()
    {
    	return $this->cycledeviecompetence;
    }
    /**
     * @ORM\OneToMany(
     *     targetEntity="Aip\ProfilageBundle\Entity\EnsembleCompetence",
     *     mappedBy="aggregate"
     * )
     */
    
    protected $ensemblecompetence;
    
    public function getEnsemblecompetence()
    {
    	return $this->ensemblecompetence;
    }
}