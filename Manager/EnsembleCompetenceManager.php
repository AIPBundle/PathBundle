<?php

namespace Aip\ProfilageBundle\Manager;

use Aip\ProfilageBundle\Entity\EnsembleCompetence;
use Aip\ProfilageBundle\Entity\GrilleCompetenceAggregate;
use Aip\ProfilageBundle\Repository\EnsembleCompetenceRepository;
use Claroline\CoreBundle\Entity\Workspace\AbstractWorkspace;
use Claroline\CoreBundle\Persistence\ObjectManager;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("aip.ensmblecompetence.manager.ensemblecompetence_manager")
 */
class EnsembleCompetenceManager
{
    /** @var EnsembleCompetenceRepository */
    private $ensemblecompetenceRepo;
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
        $this->ensemblecompetenceRepo = $om->getRepository('AipProfilageBundle:EnsembleCompetence');
        $this->om = $om;
    }

    public function insertEnsembleCompetence(EnsembleCompetence $ensemblecompetence)
    {
        $this->om->persist($ensemblecompetence);
        $this->om->flush();
    }
    public function getCycleCompetence()
    {
    	return $this->ensemblecompetenceRepo>findCycleDeVieCompetence();
    }
    public function getDefCompetence()
    {
    	return $this->ensemblecompetenceRepo->findDefinitionCompetence();
    }
    public function deleteEnsembleCompetence(EnsembleCompetence $ensemblecompetence)
    {
        $this->om->remove($ensemblecompetence);
        $this->om->flush();
    }

    public function getVisibleEnsembleCompetenceByWorkspace(AbstractWorkspace $workspace, array $roles)
    {
        return $this->ensemblecompetenceRepo->findVisibleEnsembleCompetenceByWorkspace($workspace, $roles);
    }

    public function getVisibleEnsembleCompetenceByWorkspaces(array $workspaces, array $roles)
    {
        return $this->ensemblecompetenceRepo->findVisibleEnsembleCompetenceByWorkspaces($workspaces, $roles);
    }

    public function getAllEnsembleCompetenceByAggregate(GrilleCompetenceAggregate $aggregate)
    {
        return $this->ensemblecompetenceRepo->findAllEnsembleCompetenceByAggregate($aggregate);
    }

   public function getVisibleEnsembleCompetenceByAggregate(GrilleCompetenceAggregate $aggregate)
    {
        return $this->ensemblecompetenceRepo->findVisibleEnsembleCompetenceByAggregate($aggregate);
    }
}