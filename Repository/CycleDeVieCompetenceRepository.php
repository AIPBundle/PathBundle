<?php

namespace  Aip\ProfilageBundle\Repository;

use  Aip\ProfilageBundle\Entity\CycleDeVieCompetenceAggregate;
use  Aip\ProfilageBundle\Entity\GrilleCompetenceAggregate;
use Claroline\CoreBundle\Entity\Workspace\AbstractWorkspace;
use Doctrine\ORM\EntityRepository;

class CycleDeVieCompetenceRepository extends EntityRepository
{
    public function findVisibleCycleDeVieCompetenceByWorkspace(AbstractWorkspace $workspace, array $roles)
    {
         $now = new \DateTime();

        $dql = '
            SELECT a AS cycledeviecompetence
            FROM Aip\ProfilageBundle\Entity\CycleDeVieCompetence a
            JOIN a.aggregate aa
            JOIN aa.resourceNode n
            JOIN n.workspace w
            JOIN n.rights r
            JOIN r.role rr
            AND BIT_AND(r.mask, 1) = 1
            WHERE w = :workspace
            AND rr.name in (:roles)
            ORDER BY a.publicationDate DESC
        ';
        $query = $this->_em->createQuery($dql);
        $query->setParameter('workspace', $workspace);
        $query->setParameter('roles', $roles);
       

        return $query->getResult();
    }

    public function findVisibleCycleDeVieCompetenceByWorkspaces(array $workspaces, array $roles)
    {
         $now = new \DateTime();

        $dql = '
            SELECT
                a AS cycledeviecompetence,
                w.id AS workspaceId,
                w.name AS workspaceName,
                w.code AS workspaceCode
            FROM Aip\ProfilageBundle\Entity\CycleDeVieCompetence a
            JOIN a.aggregate aa
            JOIN aa.resourceNode n
            JOIN n.workspace w
            JOIN n.rights r
            JOIN r.role rr
            WHERE w IN (:workspaces)
            AND BIT_AND(r.mask, 1) = 1
            AND rr.name in (:roles)
            ORDER BY a.publicationDate DESC
        ';
        $query = $this->_em->createQuery($dql);
        $query->setParameter('workspaces', $workspaces);
        $query->setParameter('roles', $roles);
       

        return $query->getResult();
    }

    public function findAllCycleDeVieCompetenceByAggregate(GrilleCompetenceAggregate $aggregate)
    {
         $dql = '
            SELECT a
            FROM Aip\ProfilageBundle\Entity\CycleDeVieCompetence a
            JOIN a.aggregate aa
            WHERE aa = :aggregate
            ORDER BY a.creationDate DESC
        ';
        $query = $this->_em->createQuery($dql);
        $query->setParameter('aggregate', $aggregate);

        return $query->getResult();
    }

    public function findVisibleCycleDeVieCompetenceByAggregate(GrilleCompetenceAggregate $aggregate)
    {
        $now = new \DateTime();

        $dql = '
            SELECT a
            FROM Aip\ProfilageBundle\Entity\CycleDeVieCompetence a
            JOIN a.aggregate aa
            WHERE aa = :aggregate
           
        ';
        $query = $this->_em->createQuery($dql);
        $query->setParameter('aggregate', $aggregate);
      

        return $query->getResult();
    }
}