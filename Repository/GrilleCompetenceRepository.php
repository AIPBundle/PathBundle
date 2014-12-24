<?php

namespace  Aip\ProfilageBundle\Repository;

use  Aip\ProfilageBundle\Entity\GrilleCompetenceAggregate;
use Claroline\CoreBundle\Entity\Workspace\AbstractWorkspace;
use Doctrine\ORM\EntityRepository;

class GrilleCompetenceRepository extends EntityRepository
{
    public function findVisibleGrilleCompetenceByWorkspace(AbstractWorkspace $workspace, array $roles)
    {
         $now = new \DateTime();

        $dql = '
            SELECT a AS grillecompetence
            FROM Aip\ProfilageBundle\Entity\GrilleCompetence a
            JOIN a.aggregate aa
            JOIN aa.resourceNode n
            JOIN n.workspace w
            JOIN n.rights r
            JOIN r.role rr
        	AND a.visible = true
            AND ( ( a.visibleFrom IS NULL ) OR ( a.visibleFrom <= :now ) )
            AND ( ( a.visibleUntil IS NULL ) OR ( a.visibleUntil >= :now ) )
            AND BIT_AND(r.mask, 1) = 1
            WHERE w = :workspace
            AND rr.name in (:roles)
            ORDER BY a.publicationDate DESC
        ';
        $query = $this->_em->createQuery($dql);
        $query->setParameter('workspace', $workspace);
        $query->setParameter('roles', $roles);
        $query->setParameter('now', $now);

        return $query->getResult();
    }
    public function findEnableGrilleCompetence()
    {
    	
    
    	$dql = '
            SELECT a AS enablegrille
            FROM Aip\ProfilageBundle\Entity\Enablegrille a
            
        ';
    	$query = $this->_em->createQuery($dql);
    	
    
    	return $query->getResult();
    }
    

    public function findVisibleGrilleCompetenceByWorkspaces(array $workspaces, array $roles)
    {
         $now = new \DateTime();

        $dql = '
            SELECT
                a AS grillecompetence,
                w.id AS workspaceId,
                w.name AS workspaceName,
                w.code AS workspaceCode
            FROM Aip\ProfilageBundle\Entity\GrilleCompetence a
            JOIN a.aggregate aa
            JOIN aa.resourceNode n
            JOIN n.workspace w
            JOIN n.rights r
            JOIN r.role rr
            WHERE w IN (:workspaces)
        	AND a.visible = true
            AND ( ( a.visibleFrom IS NULL ) OR ( a.visibleFrom <= :now ) )
            AND ( ( a.visibleUntil IS NULL ) OR ( a.visibleUntil >= :now ) )
            AND BIT_AND(r.mask, 1) = 1
            AND rr.name in (:roles)
            ORDER BY a.publicationDate DESC
        ';
        $query = $this->_em->createQuery($dql);
        $query->setParameter('workspaces', $workspaces);
        $query->setParameter('roles', $roles);
        $query->setParameter('now', $now);

        return $query->getResult();
    }

    public function findAllGrilleCompetenceByAggregate(GrilleCompetenceAggregate $aggregate)
    {
         $dql = '
            SELECT a
            FROM Aip\ProfilageBundle\Entity\GrilleCompetence a
            JOIN a.aggregate aa
            WHERE aa = :aggregate
            ORDER BY a.creationDate DESC
        ';
        $query = $this->_em->createQuery($dql);
        $query->setParameter('aggregate', $aggregate);

        return $query->getResult();
    }

    public function findVisibleGrilleCompetenceByAggregate(GrilleCompetenceAggregate $aggregate)
    {
        $now = new \DateTime();

        $dql = '
            SELECT a
            FROM Aip\ProfilageBundle\Entity\GrilleCompetence a
            JOIN a.aggregate aa
            WHERE aa = :aggregate
            AND a.visible = true
            AND ( ( a.visibleFrom IS NULL ) OR ( a.visibleFrom <= :now ) )
            AND ( ( a.visibleUntil IS NULL ) OR ( a.visibleUntil >= :now ) )
            ORDER BY a.creationDate DESC
        ';
        $query = $this->_em->createQuery($dql);
        $query->setParameter('aggregate', $aggregate);
        $query->setParameter('now', $now);

        return $query->getResult();
    }
}