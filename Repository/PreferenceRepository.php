<?php

namespace  Aip\ProfilageBundle\Repository;


use  Aip\ProfilageBundle\Entity\PreferenceAggregate;
use Claroline\CoreBundle\Entity\Workspace\AbstractWorkspace;
use Doctrine\ORM\EntityRepository;

class PreferenceRepository extends EntityRepository
{
    public function findVisiblePreferenceByWorkspace(AbstractWorkspace $workspace, array $roles)
    {
         $now = new \DateTime();

        $dql = '
            SELECT a AS preference
            FROM Aip\ProfilageBundle\Entity\Preference a
            JOIN a.aggregate aa
            JOIN aa.resourceNode n
            JOIN n.workspace w
            JOIN n.rights r
            JOIN r.role rr
            WHERE w = :workspace
            AND rr.name in (:roles)
            ORDER BY a.publicationDate DESC
        ';
        $query = $this->_em->createQuery($dql);
        $query->setParameter('workspace', $workspace);
        $query->setParameter('roles', $roles);
       

        return $query->getResult();
    }

    public function findVisiblePreferenceeByWorkspaces(array $workspaces, array $roles)
    {
         $now = new \DateTime();

        $dql = '
            SELECT
                a AS preference,
                w.id AS workspaceId,
                w.name AS workspaceName,
                w.code AS workspaceCode
            FROM Aip\ProfilageBundle\Entity\Preference a
            JOIN a.aggregate aa
            JOIN aa.resourceNode n
            JOIN n.workspace w
            JOIN n.rights r
            JOIN r.role rr
            WHERE w IN (:workspaces)
            AND rr.name in (:roles)
            ORDER BY a.publicationDate DESC
        ';
        $query = $this->_em->createQuery($dql);
        $query->setParameter('workspaces', $workspaces);
        $query->setParameter('roles', $roles);
       

        return $query->getResult();
    }

    public function findAllPreferenceByAggregate(PreferenceAggregate $aggregate)
    {
         $dql = '
            SELECT a
            FROM Aip\ProfilageBundle\Entity\Preference a
            JOIN a.aggregate aa
            WHERE aa = :aggregate
            ORDER BY a.creationDate DESC
        ';
        $query = $this->_em->createQuery($dql);
        $query->setParameter('aggregate', $aggregate);

        return $query->getResult();
    }

    public function findVisiblePreferenceByAggregate(PreferenceAggregate $aggregate)
    {
        $now = new \DateTime();

        $dql = '
            SELECT a
            FROM Aip\ProfilageBundle\Entity\Preference a
            JOIN a.aggregate aa
            WHERE aa = :aggregate
           
        ';
        $query = $this->_em->createQuery($dql);
        $query->setParameter('aggregate', $aggregate);
      

        return $query->getResult();
    }
}