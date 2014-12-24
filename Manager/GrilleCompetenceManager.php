<?php

namespace Aip\ProfilageBundle\Manager;

use Aip\ProfilageBundle\Entity\GrilleCompetence;
use Aip\ProfilageBundle\Entity\GrilleCompetenceAggregate;
use Aip\ProfilageBundle\Repository\GrilleCompetenceRepository;
use Claroline\CoreBundle\Entity\Workspace\AbstractWorkspace;
use Claroline\CoreBundle\Persistence\ObjectManager;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("aip.grillecompetence.manager.grillecompetence_manager")
 */
class GrilleCompetenceManager
{
    /** @var GrilleCompetenceRepository */
    private $grillecompetenceRepo;
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
        $this->grillecompetenceRepo = $om->getRepository('AipProfilageBundle:GrilleCompetence');
        $this->om = $om;
    }

    public function insertGrilleCompetence(GrilleCompetence $grillecompetence)
    {
        $this->om->persist($grillecompetence);
        $this->om->flush();
    }

    public function deleteGrilleCompetence(GrilleCompetence $grillecompetence)
    {
        $this->om->remove($grillecompetence);
        $this->om->flush();
    }

    public function getVisibleGrilleCompetenceByWorkspace(AbstractWorkspace $workspace, array $roles)
    {
        return $this->grillecompetenceRepo->findVisibleGrilleCompetenceByWorkspace($workspace, $roles);
    }
    public function getEnableGrilleCompetence()
    {
    	return $this->grillecompetenceRepo->findEnableGrilleCompetence();
    }
    public function getCycleCompetence()
    {
    	return $this->grillecompetenceRepo->findCycleDeVieCompetence();
    }
    public function getDefCompetence()
    {
    	return $this->grillecompetenceRepo->findDefinitionCompetence();
    }

    public function getVisibleGrilleCompetenceByWorkspaces(array $workspaces, array $roles)
    {
        return $this->grillecompetenceRepo->findVisibleGrilleCompetenceByWorkspaces($workspaces, $roles);
    }

    public function getAllGrilleCompetenceByAggregate(GrilleCompetenceAggregate $aggregate)
    {
        return $this->grillecompetenceRepo->findAllGrilleCompetenceByAggregate($aggregate);
    }

    public function getVisibleGrilleCompetenceByAggregate(GrilleCompetenceAggregate $aggregate)
    {
        return $this->grillecompetenceRepo->findVisibleGrilleCompetenceByAggregate($aggregate);
    }
}