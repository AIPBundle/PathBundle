<?php

namespace Aip\ProfilageBundle\Entity;
use Claroline\CoreBundle\Entity\Resource\AbstractResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="claro_cycledeviecompetence_aggregate")
 */
class CycleDeVieCompetenceAggregate extends AbstractResource
{
   
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
	
}