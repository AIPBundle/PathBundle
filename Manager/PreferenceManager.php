<?php

namespace Aip\ProfilageBundle\Manager;

use Aip\ProfilageBundle\Entity\Preference;
use Aip\ProfilageBundle\Entity\PreferenceAggregate;
use Aip\ProfilageBundle\Repository\PreferenceRepository;
use Claroline\CoreBundle\Entity\Workspace\AbstractWorkspace;
use Claroline\CoreBundle\Persistence\ObjectManager;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("aip.preference.manager.preference_manager")
 */
class PreferenceManager
{
    /** @var PreferenceRepository */
    private $preferenceRepo;
    private $om;

    /**
     * Constructor.
     *
     * @DI\InjectParams({
     *     "om" = @DI\Inject("claroline.persistence.object_manager")
     * })
     */
    public function __construct(ObjectManager $om)
    {
        $this->preferenceRepo = $om->getRepository('AipProfilageBundle:Preference');
        $this->om = $om;
    }

    public function insertPreference(Preference $preference)
    {
        $this->om->persist($preference);
        $this->om->flush();
    }

    public function deletePreference(Preference $preference)
    {
        $this->om->remove($preference);
        $this->om->flush();
    }

    public function getVisiblePreferenceByWorkspace(AbstractWorkspace $workspace, array $roles)
    {
        return $this->preferenceRepo->findVisiblePreferenceByWorkspace($workspace, $roles);
    }

    public function getVisiblePreferenceByWorkspaces(array $workspaces, array $roles)
    {
        return $this->preferenceRepo->findVisiblePreferenceByWorkspaces($workspaces, $roles);
    }

    public function getAllPreferenceByAggregate(PreferenceAggregate $aggregate)
    {
        return $this->preferenceRepo->findAllPreferenceByAggregate($aggregate);
    }

    public function getVisiblePreferenceByAggregate(PreferenceAggregate $aggregate)
    {
        return $this->preferenceRepo->findVisiblePreferenceByAggregate($aggregate);
    }
}