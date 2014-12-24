<?php

namespace Aip\ProfilageBundle\Controller;

use Aip\ProfilageBundle\Entity\CycleDeVieCompetence;
use Aip\ProfilageBundle\Entity\Enablegrille;
use Aip\ProfilageBundle\Entity\CycleVie;
use Aip\ProfilageBundle\Entity\CycleDeVieCompetenceAggregate;
use Aip\ProfilageBundle\Entity\GrilleCompetenceAggregate;
use Aip\ProfilageBundle\Form\CycleDeVieCompetenceType;
use Aip\ProfilageBundle\Manager\CycleDeVieCompetenceManager;
use Claroline\CoreBundle\Entity\Resource\AbstractResource;
use Claroline\CoreBundle\Entity\Workspace\AbstractWorkspace;
use Claroline\CoreBundle\Library\Resource\ResourceCollection;
use Claroline\CoreBundle\Library\Security\Utilities;
use Claroline\CoreBundle\Manager\WorkspaceManager;
use Claroline\CoreBundle\Pager\PagerFactory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Translation\Translator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as EXT;
use JMS\DiExtraBundle\Annotation as DI;

class CycleDeVieCompetenceController extends Controller
{
    private $cycledeviecompetenceManager;
    private $formFactory;
    private $pagerFactory;
    private $securityContext;
    private $translator;
    private $utils;
    private $workspaceManager;

    /**
     * @DI\InjectParams({
     *     "cycledeviecompetenceManager" = @DI\Inject("aip.cycledeviecompetence.manager.cycledeviecompetence_manager"),
     *     "formFactory"             = @DI\Inject("form.factory"),
     *     "pagerFactory"            = @DI\Inject("claroline.pager.pager_factory"),
     *     "securityContext"         = @DI\Inject("security.context"),
     *     "translator"              = @DI\Inject("translator"),
     *     "utils"                   = @DI\Inject("claroline.security.utilities"),
     *     "workspaceManager"        = @DI\Inject("claroline.manager.workspace_manager")
     * })
     */
    public function __construct(
        CycleDeVieCompetenceManager $cycledeviecompetenceManager,
        FormFactoryInterface $formFactory,
        PagerFactory $pagerFactory,
        SecurityContextInterface $securityContext,
        Translator $translator,
        Utilities $utils,
        WorkspaceManager $workspaceManager
    )
    {
        $this->formFactory = $formFactory;
        $this->cycledeviecompetenceManager = $cycledeviecompetenceManager;
        $this->pagerFactory = $pagerFactory;
        $this->securityContext = $securityContext;
        $this->translator = $translator;
        $this->utils = $utils;
        $this->workspaceManager = $workspaceManager;
    }

    /**
     * @EXT\Route(
     *     "/cycledeviecompetence/listcycle/aggregate/{aggregateId}/pagecycle/{page}",
     *     name = "claro_cycledeviecompetence_list",
     *     defaults={"page"=1}
     * )
     * @EXT\ParamConverter(
     *      "aggregate",
     *      class="AipProfilageBundle:GrilleCompetenceAggregate",
     *      options={"id" = "aggregateId", "strictId" = true}
     * )
     * @EXT\Template("AipProfilageBundle::cycledeviecompetenceList.html.twig")
     *
     * @param GrilleCompetenceAggregate $aggregate
     * 
     * @return Response
     */
    public function cycledeviecompetenceListAction(GrilleCompetenceAggregate $aggregate, $page)
    {
    	$collection = new ResourceCollection(array($aggregate->getResourceNode()));

    
    	try {
    		$this->checkAccess('EDIT', $aggregate);
    		$cycledeviecompetence = $this->cycledeviecompetenceManager->getAllCycleDeVieCompetenceByAggregate($aggregate);
    		
    	}
    	catch(AccessDeniedException $e) {
    		$this->checkAccess('OPEN', $aggregate);
    		$cycledeviecompetence = $this->cycledeviecompetenceManager->getVisibleCycleDeVieCompetenceByAggregate($aggregate);
    	}
    	$pager = $this->pagerFactory->createPagerFromArray($cycledeviecompetence, $page, 5);
    	
    	
    
    	return array(
    			'_resource' => $aggregate,
    			'cycledeviecompetence' => $pager,
    			'resourceCollection' => $collection
    	);
    }
    
    /**
     * @EXT\Route(
     *     "/aggregate/{aggregateId}/createcycle/form",
     *     name = "claro_cycledeviecompetence_create_form"
     * )
     * @EXT\Method("GET")
     * @EXT\ParamConverter(
     *      "aggregate",
     *      class="AipProfilageBundle:GrilleCompetenceAggregate",
     *      options={"id" = "aggregateId", "strictId" = true}
     *     
     *      
     * )
     * 
     * @EXT\Template("AipProfilageBundle::createcycleForm.html.twig")
     *
     * @param GrilleCompetenceAggregate $aggregate
  
     * @return Response
     */
    public function createFormAction( GrilleCompetenceAggregate $aggregate)
    {
    	 
    	$this->checkAccess('EDIT', $aggregate);
    	$cycledeviecompetence = new CycleDeVieCompetence();
    	
    	$form = $this->formFactory->create(new CycleDeVieCompetenceType(), $cycledeviecompetence);
    
    	return array(
    			'form' => $form->createView(),
    			'type' => 'create',
    			'_resource' => $aggregate
    	);
    }
    

/**
 * @EXT\Route(
 *     "/aggregate/{aggregateId}/createcycle",
 *     name = "claro_cycledeviecompetence_create"
 * )
 * @EXT\Method("POST")
 * @EXT\ParamConverter(
 *      "aggregate",
 *      class="AipProfilageBundle:GrilleCompetenceAggregate",
 *      options={"id" = "aggregateId", "strictId" = true}
 * )
 * @EXT\Template("AipProfilageBundle::createcycleForm.html.twig")
 *
 * @param GrilleCompetenceAggregate $aggregate
 *
 * @return Response
 */
public function createAction(GrilleCompetenceAggregate $aggregate)
{
	$this->checkAccess('EDIT', $aggregate);
	$em = $this->getDoctrine()->getEntityManager();
	$id = $aggregate->getId();
	$user = $this->securityContext->getToken()->getUser();
	$cycledeviecompetence = new CycleDeVieCompetence();
	$form = $this->formFactory->create(new CycleDeVieCompetenceType(), $cycledeviecompetence);
	$request = $this->getRequest();
	$form->handleRequest($request);
	
	
		$now = new \DateTime();
	
		 
		
			
			 
		 
		$cycledeviecompetence->setAggregate($aggregate);
		$cycledeviecompetence->setCreationDate($now);
		$cycledeviecompetence->setPublicationDate($now);
		
		$cycledeviecompetence->setCreator($user);
		if (($cycledeviecompetence->isEncours())&&($cycledeviecompetence->isAcquistheorique()==false)&&($cycledeviecompetence->isAcquispratique()==false))
		{
			$cycledeviecompetence->setDefcycle("Encours");
		}
		if (($cycledeviecompetence->isEncours()==false)&&($cycledeviecompetence->isAcquistheorique())&&($cycledeviecompetence->isAcquispratique()==false))
		{
			$cycledeviecompetence->setDefcycle("Acquis theorique");
		}
		if (($cycledeviecompetence->isEncours()==false)&&($cycledeviecompetence->isAcquistheorique()==false)&&($cycledeviecompetence->isAcquispratique()))
		{
			$cycledeviecompetence->setDefcycle("Acquis pratique");
		
		}
		if (($cycledeviecompetence->isEncours())&&($cycledeviecompetence->isAcquistheorique())&&($cycledeviecompetence->isAcquispratique()==false))
		{
			$cycledeviecompetence->setDefcycle("Encours,Acquis theorique");
		}
		if (($cycledeviecompetence->isEncours()==false)&&($cycledeviecompetence->isAcquistheorique())&&($cycledeviecompetence->isAcquispratique()))
		{
			$cycledeviecompetence->setDefcycle("Acquis theorique,Acquis pratique");
		}
		if (($cycledeviecompetence->isEncours())&&($cycledeviecompetence->isAcquistheorique()==false)&&($cycledeviecompetence->isAcquispratique()))
		{
			$cycledeviecompetence->setDefcycle("Encours,Acquis pratique");
		}
		if (($cycledeviecompetence->isEncours())&&($cycledeviecompetence->isAcquistheorique())&&($cycledeviecompetence->isAcquispratique()))
		{
			$cycledeviecompetence->setDefcycle("Encours,Acquis theorique,Acquis pratique");
		}
		$this->cycledeviecompetenceManager->insertCycleDeVieCompetence($cycledeviecompetence);
		$query = $em->createQuery('SELECT u FROM Aip\ProfilageBundle\Entity\EnsembleCompetence u WHERE u.aggregate = :aggregate ');
		$query->setParameter('aggregate',$aggregate);
		$ensemble=$query->getResult();
		$query1 = $em->createQuery('SELECT u FROM Aip\ProfilageBundle\Entity\Enablegrille u WHERE u.aggregate = :id' );
        $query1->setParameter('id', $id);
		$enable=$query1->getResult();
		if(($ensemble!=null)&&($enable==null))
			
		{
			$disabledgrille = new Enablegrille();
			$disabledgrille->setEnable(true);
			$disabledgrille->setAggregate($id);
			$em->persist($disabledgrille);
			$em->flush();
		}
		
		$em=$this->getDoctrine()->getEntityManager();
		 
		$query = $em->createQuery('SELECT MAX(u.id) FROM Aip\ProfilageBundle\Entity\CycleDevieCompetence u WHERE u.aggregate = :id' );
		$query->setParameter('id',$aggregate);
		$cycleid=$query->getSingleScalarResult();

		$query1 = $em->createQuery('SELECT u FROM Aip\ProfilageBundle\Entity\CycleDevieCompetence u WHERE u.id = :id AND u.aggregate = :aggregate' );
		$query1->setParameter('id', $cycleid);
		$query1->setParameter('aggregate',$aggregate);
		$cycledevieCom=$query1->getSingleResult();
		$cycledevie = new CycleVie();
		if (($cycledeviecompetence->isEncours())&&($cycledeviecompetence->isAcquistheorique()==false)&&($cycledeviecompetence->isAcquispratique()==false))
		{
			$cycledevie->setNom('En cours');
			$cycledevie->setCycle($cycledevieCom);
			$this->cycledeviecompetenceManager->insertCycle($cycledevie);
		}
		if (($cycledeviecompetence->isEncours()==false)&&($cycledeviecompetence->isAcquistheorique())&&($cycledeviecompetence->isAcquispratique()==false))
		{
			$cycledevie->setNom('Acquis theorique');
			$cycledevie->setCycle($cycledevieCom);
			$this->cycledeviecompetenceManager->insertCycle($cycledevie);
		}
		if (($cycledeviecompetence->isEncours()==false)&&($cycledeviecompetence->isAcquistheorique()==false)&&($cycledeviecompetence->isAcquispratique()))
		{
			$cycledevie->setNom('Acquis pratique');
			$cycledevie->setCycle($cycledevieCom);
			$this->cycledeviecompetenceManager->insertCycle($cycledevie);
		
		}
		if (($cycledeviecompetence->isEncours())&&($cycledeviecompetence->isAcquistheorique())&&($cycledeviecompetence->isAcquispratique()==false))
		{
			$cycledevie->setNom('En cours');
			$cycledevie->setCycle($cycledevieCom);
			$this->cycledeviecompetenceManager->insertCycle($cycledevie);
			$cycledevie1 = new CycleVie();
			$cycledevie1->setNom('Acquis theorique');
			$cycledevie1->setCycle($cycledevieCom);
			$this->cycledeviecompetenceManager->insertCycle($cycledevie1);
		}
		if (($cycledeviecompetence->isEncours()==false)&&($cycledeviecompetence->isAcquistheorique())&&($cycledeviecompetence->isAcquispratique()))
		{
			$cycledevie->setNom('Acquis theorique');
			$cycledevie->setCycle($cycledevieCom);
			$this->cycledeviecompetenceManager->insertCycle($cycledevie);
			$cycledevie1 = new CycleVie();
			$cycledevie1->setNom('Acquis pratique');
			$cycledevie1->setCycle($cycledevieCom);
			$this->cycledeviecompetenceManager->insertCycle($cycledevie1);
		}
		if (($cycledeviecompetence->isEncours())&&($cycledeviecompetence->isAcquistheorique()==false)&&($cycledeviecompetence->isAcquispratique()))
		{
			$cycledevie->setNom('En cours');
			$cycledevie->setCycle($cycledevieCom);
			$this->cycledeviecompetenceManager->insertCycle($cycledevie);
			$cycledevie1 = new CycleVie();
			$cycledevie1->setNom('Acquis pratique');
			$cycledevie1->setCycle($cycledevieCom);
			$this->cycledeviecompetenceManager->insertCycle($cycledevie1);
		}
		if (($cycledeviecompetence->isEncours())&&($cycledeviecompetence->isAcquistheorique())&&($cycledeviecompetence->isAcquispratique()))
		{
			$cycledevie->setNom('En cours');
			$cycledevie->setCycle($cycledevieCom);
			$this->cycledeviecompetenceManager->insertCycle($cycledevie);
			$cycledevie1 = new CycleVie();
			$cycledevie1->setNom('Acquis pratique');
			$cycledevie1->setCycle($cycledevieCom);
			$this->cycledeviecompetenceManager->insertCycle($cycledevie1);
			$cycledevie2 = new CycleVie();
			$cycledevie2->setNom('Acquis theorique');
			$cycledevie2->setCycle($cycledevieCom);
			$this->cycledeviecompetenceManager->insertCycle($cycledevie2);
		}
		 
		return $this->redirect(
				$this->generateUrl(
						'claro_cycledeviecompetence_list',
						array('aggregateId' => $aggregate->getId(),
		                       'cycleid' => $cycleid)
				)
		);
	
	
	return array(
			'form' => $form->createView(),
			'type' => 'create',
			'_resource' => $aggregate
	);
}
/**
 * @EXT\Route(
 *     "/cycledeviecompetence/{cycledeviecompetenceId}/edit/form",
 *     name = "claro_cycledeviecompetence_edit_form"
 * )
 * @EXT\Method("GET")
 * @EXT\ParamConverter(
 *      "cycledeviecompetence",
 *      class="AipProfilageBundle:CycleDeVieCompetence",
 *      options={"id"="cycledeviecompetenceId","strictId"= true}
 * )
 * @EXT\Template("AipProfilageBundle::createcycleForm.html.twig")
 *
 * @param CycleDeVieCompetence $cycledeviecompetence
 *
 * @return Response
 */
public function cycledeviecompetenceEditFormAction(CycleDeVieCompetence $cycledeviecompetence)
{
	$resource = $cycledeviecompetence->getAggregate();

	$this->checkAccess('EDIT', $resource);
	$form = $this->formFactory->create(new CycleDeVieCompetenceType(), $cycledeviecompetence);

	return array(
			'form' => $form->createView(),
			'type' => 'edit',
			'cycledeviecompetence' => $cycledeviecompetence,
			'_resource' => $resource
	);
}

/**
 * @EXT\Route(
 *     "/cycledeviecompetence/{cycledeviecompetenceId}/edit",
 *     name = "claro_cycledeviecompetence_edit"
 * )
 * @EXT\Method("POST")
 * @EXT\ParamConverter(
 *      "cycledeviecompetence",
 *      class="AipProfilageBundle:CycleDeVieCompetence",
 *      options={"id"="cycledeviecompetenceId","strictId"= true}
 * )
 * @EXT\Template("AipProfilageBundle::createcycleForm.html.twig")
 *
 * @param CycleDeVieCompetence $cycledeviecompetence
 *
 * @return Response
 */
public function cycledeviecompetenceEditAction(CycleDeVieCompetence $cycledeviecompetence)
{
	
	$resource = $cycledeviecompetence->getAggregate();
	$id = $cycledeviecompetence->getId();
	 
	$this->checkAccess('EDIT', $resource);
	$form = $this->formFactory->create(new CycleDeVieCompetenceType(), $cycledeviecompetence);
	
	$request = $this->getRequest();
	$form->handleRequest($request);
	

		$now = new \DateTime();
		
		 
		
			$this->get('session')->getFlashBag()->add(
					'danger');
			 
			
			$cycledeviecompetence->setPublicationDate($now);
			if (($cycledeviecompetence->isEncours())&&($cycledeviecompetence->isAcquistheorique()==false)&&($cycledeviecompetence->isAcquispratique()==false))
			{
				$cycledeviecompetence->setDefcycle("Encours");
			}
			if (($cycledeviecompetence->isEncours()==false)&&($cycledeviecompetence->isAcquistheorique())&&($cycledeviecompetence->isAcquispratique()==false))
			{
				$cycledeviecompetence->setDefcycle("Acquis theorique");
			}
			if (($cycledeviecompetence->isEncours()==false)&&($cycledeviecompetence->isAcquistheorique()==false)&&($cycledeviecompetence->isAcquispratique()))
			{
				$cycledeviecompetence->setDefcycle("Acquis pratique");
			
			}
			if (($cycledeviecompetence->isEncours())&&($cycledeviecompetence->isAcquistheorique())&&($cycledeviecompetence->isAcquispratique()==false))
			{
				$cycledeviecompetence->setDefcycle("Encours,Acquis theorique");
			}
			if (($cycledeviecompetence->isEncours()==false)&&($cycledeviecompetence->isAcquistheorique())&&($cycledeviecompetence->isAcquispratique()))
			{
				$cycledeviecompetence->setDefcycle("Acquis theorique,Acquis pratique");
			}
			if (($cycledeviecompetence->isEncours())&&($cycledeviecompetence->isAcquistheorique()==false)&&($cycledeviecompetence->isAcquispratique()))
			{
				$cycledeviecompetence->setDefcycle("Encours,Acquis pratique");
			}
			if (($cycledeviecompetence->isEncours())&&($cycledeviecompetence->isAcquistheorique())&&($cycledeviecompetence->isAcquispratique()))
			{
				$cycledeviecompetence->setDefcycle("Encours,Acquis theorique,Acquis pratique");
			}
		
		$this->cycledeviecompetenceManager->insertCycleDeVieCompetence($cycledeviecompetence);
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery('DELETE FROM Aip\ProfilageBundle\Entity\CycleVie e WHERE e.cycle = :cycle');
		$query->setParameter('cycle', $cycledeviecompetence);
		$query->execute();
		$cycledevie = new CycleVie();
		if (($cycledeviecompetence->isEncours())&&($cycledeviecompetence->isAcquistheorique()==false)&&($cycledeviecompetence->isAcquispratique()==false))
		{
			$cycledevie->setNom('En cours');
			$cycledevie->setCycle($cycledeviecompetence);
			$this->cycledeviecompetenceManager->insertCycle($cycledevie);
		}
		if (($cycledeviecompetence->isEncours()==false)&&($cycledeviecompetence->isAcquistheorique())&&($cycledeviecompetence->isAcquispratique()==false))
		{
			$cycledevie->setNom('Acquis theorique');
			$cycledevie->setCycle($cycledeviecompetence);
			$this->cycledeviecompetenceManager->insertCycle($cycledevie);
		}
		if (($cycledeviecompetence->isEncours()==false)&&($cycledeviecompetence->isAcquistheorique()==false)&&($cycledeviecompetence->isAcquispratique()))
		{
			$cycledevie->setNom('Acquis pratique');
			$cycledevie->setCycle($cycledeviecompetence);
			$this->cycledeviecompetenceManager->insertCycle($cycledevie);
		
		}
		if (($cycledeviecompetence->isEncours())&&($cycledeviecompetence->isAcquistheorique())&&($cycledeviecompetence->isAcquispratique()==false))
		{
			$cycledevie->setNom('En cours');
			$cycledevie->setCycle($cycledeviecompetence);
			$this->cycledeviecompetenceManager->insertCycle($cycledevie);
			$cycledevie1 = new CycleVie();
			$cycledevie1->setNom('Acquis theorique');
			$cycledevie1->setCycle($cycledeviecompetence);
			$this->cycledeviecompetenceManager->insertCycle($cycledevie1);
		}
		if (($cycledeviecompetence->isEncours()==false)&&($cycledeviecompetence->isAcquistheorique())&&($cycledeviecompetence->isAcquispratique()))
		{
			$cycledevie->setNom('Acquis theorique');
			$cycledevie->setCycle($cycledeviecompetence);
			$this->cycledeviecompetenceManager->insertCycle($cycledevie);
			$cycledevie1 = new CycleVie();
			$cycledevie1->setNom('Acquis pratique');
			$cycledevie1->setCycle($cycledeviecompetence);
			$this->cycledeviecompetenceManager->insertCycle($cycledevie1);
		}
		if (($cycledeviecompetence->isEncours())&&($cycledeviecompetence->isAcquistheorique()==false)&&($cycledeviecompetence->isAcquispratique()))
		{
			$cycledevie->setNom('En cours');
			$cycledevie->setCycle($cycledeviecompetence);
			$this->cycledeviecompetenceManager->insertCycle($cycledevie);
			$cycledevie1 = new CycleVie();
			$cycledevie1->setNom('Acquis pratique');
			$cycledevie1->setCycle($cycledeviecompetence);
			$this->cycledeviecompetenceManager->insertCycle($cycledevie1);
		}
		if (($cycledeviecompetence->isEncours())&&($cycledeviecompetence->isAcquistheorique())&&($cycledeviecompetence->isAcquispratique()))
		{
			$cycledevie->setNom('En cours');
			$cycledevie->setCycle($cycledeviecompetence);
			$this->cycledeviecompetenceManager->insertCycle($cycledevie);
			$cycledevie1 = new CycleVie();
			$cycledevie1->setNom('Acquis pratique');
			$cycledevie1->setCycle($cycledeviecompetence);
			$this->cycledeviecompetenceManager->insertCycle($cycledevie1);
			$cycledevie2 = new CycleVie();
			$cycledevie2->setNom('Acquis theorique');
			$cycledevie2->setCycle($cycledeviecompetence);
			$this->cycledeviecompetenceManager->insertCycle($cycledevie2);
		}
			
		 
		return $this->redirect(
				$this->generateUrl(
						'claro_cycledeviecompetence_list',
						array('aggregateId' => $resource->getId())
				)
		);
	
	 
	return array(
			'form' => $form->createView(),
			'type' => 'edit',
			'cycledeviecompetence' => $cycledeviecompetence,
			'_resource' => $resource
	);
}
/**
 * @EXT\Route(
 *     "/cycledeviecompetence/{cycledeviecompetenceId}/delete",
 *     name = "claro_cycledeviecompetence_delete",
 *     options={"expose"=true}
 * )
 * @EXT\Method("DELETE")
 * @EXT\ParamConverter(
 *      "cycledeviecompetence",
 *      class="AipProfilageBundle:CycleDeVieCompetence",
 *      options={"id" = "cycledeviecompetenceId", "strictId" = true}
 * )
 *
 * @param CycleDeVieCompetence $cycledeviecompetence
 *
 * @return Response
 */
public function cycledeviecompetenceDeleteAction(CycleDeVieCompetence $cycledeviecompetence)
{
	$em = $this->getDoctrine()->getEntityManager();
	$resource = $cycledeviecompetence->getAggregate();
	$id=$resource->getId();
	$this->checkAccess('EDIT', $resource);
	$this->cycledeviecompetenceManager->deleteCycleDeVieCompetence($cycledeviecompetence);
	$query = $em->createQuery('SELECT u FROM Aip\ProfilageBundle\Entity\CycleDeVieCompetence u WHERE u.aggregate = :id ');
	$query->setParameter('id',$resource);
	$cycledevie=$query->getResult();
	$query1 = $em->createQuery('SELECT u FROM Aip\ProfilageBundle\Entity\EnsembleCompetence u WHERE u.aggregate = :id ');
	$query1->setParameter('id',$resource);
	$ensemble=$query1->getResult();
	if(($ensemble==null)or($cycledevie==null))
	{
		$query2 = $em->createQuery('DELETE FROM Aip\ProfilageBundle\Entity\Enablegrille e WHERE e.aggregate = :id');
		$query2->setParameter('id',$id);
		$query3 = $em->createQuery('DELETE FROM Aip\ProfilageBundle\Entity\GrilleCompetence e  WHERE e.aggregate = :id');
		$query3->setParameter('id',$resource);
		$query2->execute();
		$query3->execute();
	}

	return new Response(204);
}

/**
 * @EXT\Route(
 *     "/cycledeviecompetence/workspace/{workspaceId}/page/{page}",
 *     name="claro_workspace_cycledeviecompetence_pager",
 *     defaults={"page"=1},
 *     options={"expose"=true}
 * )
 * @EXT\Method("GET")
 * @EXT\ParamConverter(
 *      "workspace",
 *      class="ClarolineCoreBundle:Workspace\AbstractWorkspace",
 *      options={"id" = "workspaceId", "strictId" = true}
 * )
 *
 * @EXT\Template("AipProfilageBundle::cycledeviecompetenceWorkspaceWidgetPager.html.twig")
 *
 * Renders cycledeviecompetence in a pager.
 *
 * @return Response
 */
public function cycledeviecompetenceWorkspaceWidgetPagerAction(AbstractWorkspace $workspace, $page)
{
	$token = $this->securityContext->getToken();
	$roles = $this->utils->getRoles($token);
	$datas = $this->cycledeviecompetenceManager->getVisibleCycleDeVieCompetenceByWorkspace($workspace, $roles);
	$pager = $this->pagerFactory->createPagerFromArray($datas, $page, 5);

	return array(
			'datas' => $pager,
			'widgetType' => 'workspace',
			'workspaceId' => $workspace->getId()
	);
}
/**
 * @EXT\Route(
 *     "/cycledeviecompetence/page/{page}",
 *     name="claro_desktop_cycledeviecompetence_pager",
 *     defaults={"page"=1},
 *     options={"expose"=true}
 * )
 * @EXT\Method("GET")
 *
 * @EXT\Template("AipProfilageBundle::cycledeviecompetenceDesktopWidgetPager.html.twig")
 *
 * Renders cycledeviecompetence in a pager.
 *
 * @return Response
 */
public function cycledeviecompetenceDesktopWidgetPagerAction($page)
{
	$token = $this->securityContext->getToken();
	$roles = $this->utils->getRoles($token);
	$workspaces = $this->workspaceManager->getWorkspacesByRoles($roles);
	$datas = $this->cycledeviecompetenceManager->getVisibleCycleDeVieCompetenceByWorkspaces($workspaces, $roles);
	$pager = $this->pagerFactory->createPagerFromArray($datas, $page, 5);

	return array('datas' => $pager, 'widgetType' => 'desktop');
}
    /**
     * Checks if the current user has the right to perform an action on a ResourceCollection.
     * Be careful, ResourceCollection may need some aditionnal parameters.
     *
     * - for CREATE: $collection->setAttributes(array('type' => $resourceType))
     *  where $resourceType is the name of the resource type.
     * - for MOVE / COPY $collection->setAttributes(array('parent' => $parent))
     *  where $parent is the new parent entity.
     *
     * @param string             $permission
     * @param ResourceCollection $collection
     *
     * @throws AccessDeniedException
     */
    private function checkAccess($permission, AbstractResource $resource)
    {
    	$collection = new ResourceCollection(array($resource->getResourceNode()));
    
    	if (!$this->securityContext->isGranted($permission, $collection)) {
    		throw new AccessDeniedException($collection->getErrorsForDisplay());
    	}
    }
}