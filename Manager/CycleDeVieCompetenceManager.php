<?php

namespace Aip\ProfilageBundle\Manager;

use Aip\ProfilageBundle\Entity\CycleDeVieCompetence;
use Aip\ProfilageBundle\Entity\CycleVie;
use Aip\ProfilageBundle\Entity\CycleDeVieCompetenceAggregate;
use Aip\ProfilageBundle\Entity\GrilleCompetenceAggregate;
use Aip\ProfilageBundle\Repository\CycleDeVieCompetenceRepository;
use Claroline\CoreBundle\Entity\Workspace\AbstractWorkspace;
use Claroline\CoreBundle\Persistence\ObjectManager;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("aip.cycledeviecompetence.manager.cycledeviecompetence_manager")
 */
class CycleDeVieCompetenceManager
{
    /** @var CycleDeVieCompetenceRepository */
    private $cycledeviecompetenceRepo;
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
        $this->cycledeviecompetenceRepo = $om->getRepository('AipProfilageBundle:CycleDeVieCompetence');
        $this->om = $om;
    }

    public function insertCycle(CycleVie $cycledevie)
    {
    	$this->om->persist($cycledevie);
    	$this->om->flush();
    }
    public function insertCycleDeVieCompetence(CycleDeVieCompetence $cycledeviecompetence)
    {
        $this->om->persist($cycledeviecompetence);
        $this->om->flush();
    }

    public function deleteCycleDeVieCompetence(CycleDeVieCompetence $cycledeviecompetence)
    {
        $this->om->remove($cycledeviecompetence);
        $this->om->flush();
    }

    public function getVisibleCycleDeVieCompetenceByWorkspace(AbstractWorkspace $workspace, array $roles)
    {
        return $this->cycledeviecompetenceRepo->findVisibleCycleDeVieCompetenceByWorkspace($workspace, $roles);
    }

    public function getVisibleCycleDeVieCompetenceByWorkspaces(array $workspaces, array $roles)
    {
        return $this->cycledeviecompetenceRepo->findVisibleCycleDeVieCompetenceByWorkspaces($workspaces, $roles);
    }

    public function getAllCycleDeVieCompetenceByAggregate(GrilleCompetenceAggregate $aggregate)
    {
        return $this->cycledeviecompetenceRepo->findAllCycleDeVieCompetenceByAggregate($aggregate);
    }

   public function getVisibleCycleDeVieCompetenceByAggregate(GrilleCompetenceAggregate $aggregate)
    {
        return $this->cycledeviecompetenceRepo->findVisibleCycleDeVieCompetenceByAggregate($aggregate);
    }
}