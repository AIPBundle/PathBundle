<?php

namespace Aip\ProfilageBundle\Entity;
use Claroline\CoreBundle\Entity\Resource\AbstractResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * This entity is only an AbstractResource sub-type, with no additional attributes.
 *
 * @ORM\Entity
 * @ORM\Table(name="claro_parcours")
 */
class  Parcours extends AbstractResource
{
/**
	 * @ORM\OneToMany(
	 *     targetEntity="Aip\ProfilageBundle\Entity\Etape",
	 *     mappedBy="aggregate"
	 * )
	 */
	
	protected $etape;
	
	public function getEtape()
	{
		return $this->etape;
	}
}
