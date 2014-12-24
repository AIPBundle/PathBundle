<?php

namespace Aip\ProfilageBundle\Entity;
use Claroline\CoreBundle\Entity\Resource\AbstractResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="claro_preference_aggregate")
 */
class PreferenceAggregate extends AbstractResource
{
   
	/**
	 * @ORM\OneToMany(
	 *     targetEntity="Aip\ProfilageBundle\Entity\Preference",
	 *     mappedBy="aggregate"
	 * )
	 */
	
	protected $preference;
	
	public function getPreference()
	{
		return $this->preference;
	}
	
}