<?php

namespace Aip\ProfilageBundle\Manager;

use Aip\ProfilageBundle\Entity\Etape;
use Aip\ProfilageBundle\Entity\Parcours;
use Aip\ProfilageBundle\Repository\ParcoursRepository;
use Claroline\CoreBundle\Entity\Workspace\AbstractWorkspace;
use Claroline\CoreBundle\Persistence\ObjectManager;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("aip.parcours.manager.parcours_manager")
 */
class ParcoursManager
{
    /** @var EtapeRepository */
    private $etapeRepo;
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
        $this->etapeRepo = $om->getRepository('AipProfilageBundle:Etape');
        $this->om = $om;
    }

    public function insertEtape(Etape $etape)
    {
        $this->om->persist($etape);
        $this->om->flush();
    }

    public function deleteEtape(Etape $etape)
    {
        $this->om->remove($etape);
        $this->om->flush();
    }

    public function getVisibleEtapeByWorkspace(AbstractWorkspace $workspace, array $roles)
    {
        return $this->etapeRepo->findVisibleEtapeByWorkspace($workspace, $roles);
    }
    

    public function getVisibleEtapeByWorkspaces(array $workspaces, array $roles)
    {
        return $this->etapeRepo->findVisibleEtapeByWorkspaces($workspaces, $roles);
    }

    public function getAllEtapeByAggregate(Parcours $aggregate)
    {
        return $this->etapeRepo->findAllEtapeByAggregate($aggregate);
    }

    public function getVisibleEtapeByAggregate(Parcours $aggregate)
    {
        return $this->etapeRepo->findVisibleEtapeByAggregate($aggregate);
    }
}