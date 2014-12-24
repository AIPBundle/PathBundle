<?php

namespace Aip\ProfilageBundle\Controller;

use Aip\ProfilageBundle\Entity\Etape;
use Aip\ProfilageBundle\Entity\Modelusager;
use Aip\ProfilageBundle\Entity\EtapeConfigue;
use Aip\ProfilageBundle\Entity\UserParcours;
use Aip\ProfilageBundle\Entity\CycleDeVieCompetence ;
use Aip\ProfilageBundle\Form\EtapeType;
use Aip\ProfilageBundle\Form\EtapeConfigueType;
use Aip\ProfilageBundle\Form\CycleDeVieCompetenceType;
use Aip\ProfilageBundle\Entity\Parcours;
use Aip\ProfilageBundle\Manager\ParcoursManager;
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
use Doctrine\ORM\EntityManagerInterface;
use JMS\DiExtraBundle\Annotation as DI;

class ParcoursController extends Controller
{
    private $parcoursManager;
    private $formFactory;
    private $pagerFactory;
    private $securityContext;
    private $translator;
    private $utils;
    private $workspaceManager;

    /**
     * @DI\InjectParams({
     *     "parcoursManager"       = @DI\Inject("aip.parcours.manager.parcours_manager"),
     *     "formFactory"             = @DI\Inject("form.factory"),
     *     "pagerFactory"            = @DI\Inject("claroline.pager.pager_factory"),
     *     "securityContext"         = @DI\Inject("security.context"),
     *     "translator"              = @DI\Inject("translator"),
     *     "utils"                   = @DI\Inject("claroline.security.utilities"),
     *     "workspaceManager"        = @DI\Inject("claroline.manager.workspace_manager")
     * })
     */
    public function __construct(
        ParcoursManager $parcoursManager,
        FormFactoryInterface $formFactory,
        PagerFactory $pagerFactory,
        SecurityContextInterface $securityContext,
        Translator $translator,
        Utilities $utils,
        WorkspaceManager $workspaceManager
    )
    {
        $this->formFactory = $formFactory;
        $this->parcoursManager = $parcoursManager;
        $this->pagerFactory = $pagerFactory;
        $this->securityContext = $securityContext;
        $this->translator = $translator;
        $this->utils = $utils;
        $this->workspaceManager = $workspaceManager;
    }

    /**
     * @EXT\Route(
     *     "/etape/list/aggregate/{aggregateId}/page/{page}",
     *     name = "claro_etape_list",
     *     defaults={"page"=1}
     * )
     * @EXT\ParamConverter(
     *      "aggregate",
     *      class="AipProfilageBundle:Parcours",
     *      options={"id" = "aggregateId", "strictId" = true}
     * )
     * @EXT\Template("AipProfilageBundle::parcoursList.html.twig")
     *
     * @param ParcoursAggregate $aggregate
     *
     * @return Response
     */
    public function parcoursListAction(Parcours $aggregate, $page)
    {
    	$collection = new ResourceCollection(array($aggregate->getResourceNode()));
    	$em = $this->getDoctrine()->getEntityManager();
    	
    	
    	try {
    		$this->checkAccess('EDIT', $aggregate);
    		$etape = $this->parcoursManager->getAllEtapeByAggregate($aggregate);
    		
    	}
    	catch(AccessDeniedException $e) {
    		$this->checkAccess('OPEN', $aggregate);
    		$etape = $this->parcoursManager->getVisibleEtapeByAggregate($aggregate);
    		
    	}
    	$pager = $this->pagerFactory->createPagerFromArray($etape, $page, 5);
    	
    	return array(
    			'_resource' => $aggregate,
    			'etape' => $pager,
    			'resourceCollection' => $collection
    	);
    	
    }
    /**
     * @EXT\Route(
     *     "/etape/list/competence/{aggregateId}/page/{page}",
     *     name = "claro_etape_competence_list",
     *     defaults={"page"=1}
     * )
     * @EXT\ParamConverter(
     *      "aggregate",
     *      class="AipProfilageBundle:Parcours",
     *      options={"id" = "aggregateId", "strictId" = true}
     * )
     * @EXT\Template("AipProfilageBundle::parcourscompetenceList.html.twig")
     *
     * @param ParcoursAggregate $aggregate
     *
     * @return Response
     */
    public function parcourscompetenceListAction(Parcours $aggregate, $page)
    {
    	$collection = new ResourceCollection(array($aggregate->getResourceNode()));
    	$em = $this->getDoctrine()->getEntityManager();
    	$idaggregate = $aggregate->getAggregate();
    	$query = $em->createQuery('SELECT u FROM Aip\ProfilageBundle\Entity\Modelusager u WHERE u.aggregate = :id' );
    	$query->setParameter('id', $idaggregate->getId());
    	$listemodelusager=$query->getResult();
    	
    	$pager = $this->pagerFactory->createPagerFromArray($listemodelusager, $page, 5);
    	 
    	return array(
    			'_resource' => $aggregate,
    			'modelusager' => $pager,
    			'resourceCollection' => $collection
    	);
    	 
    }
    /**
     * @EXT\Route(
     *     "/aggregate/{aggregateId}/createetape/form",
     *     name = "claro_etape_create_form"
     * )
     * @EXT\Method("GET")
     * @EXT\ParamConverter(
     *      "aggregate",
     *      class="AipProfilageBundle:Parcours",
     *      options={"id" = "aggregateId", "strictId" = true}
     * )
     * @EXT\Template("AipProfilageBundle::createparcoursForm.html.twig")
     *
     * @param Parcours $aggregate
     *
     * @return Response
     */
    public function createFormAction( Parcours $aggregate)
    {
    	$user = $this->securityContext->getToken()->getUser();
    	$this->checkAccess('EDIT', $aggregate);
    	$etape = new Etape();
        $etape->setCreator($user);
    	$form = $this->formFactory->create(new EtapeType(), $etape);
    	$resourceTypes = $this->container->get('doctrine.orm.entity_manager')->getRepository('ClarolineCoreBundle:Resource\ResourceType')
    	->findAll();
    	return array(
    			'form' => $form->createView(),
    			'type' => 'create',
    			'_resource' => $aggregate,
    			'resourceTypes' => $resourceTypes
    			
    	);
    }
    /**
     * @EXT\Route(
     *     "/aggregate/{aggregateId}/createparcours",
     *     name = "claro_etape_create"
     * )
     * @EXT\Method("POST")
     * @EXT\ParamConverter(
     *      "aggregate",
     *      class="AipProfilageBundle:Parcours",
     *      options={"id" = "aggregateId", "strictId" = true}
     * )
     * @EXT\Template("AipProfilageBundle::createparcoursForm.html.twig")
     *
     * @param Parcours $aggregate
     *
     * @return Response
     */
    public function createAction(Parcours $aggregate)
    {
    	$this->checkAccess('EDIT', $aggregate);
    	$id = $aggregate->getId();
    	$user = $this->securityContext->getToken()->getUser();
    	$etape = new Etape();
    	$etape->setCreator($user);
    	$form = $this->formFactory->create(new EtapeType(), $etape);
    	$request = $this->getRequest();
    	$form->handleRequest($request);
    	$em=$this->getDoctrine()->getEntityManager();
    
    	if ($form->isValid()) {
    		$now = new \DateTime();
    
    		 
    
           if($etape->isInitial())
           {
           	$etape->setInitial(true);
           	$etape->setEtat(true);
           }
    		$etape->setAggregate($aggregate);
    		$etape->setCreationDate($now);
    		 
    		$etape->setPublicationDate($now);
    		 
    
    		$ressource = $form->get('ressource')->getData()->getName();
    		$etape->setRessource($ressource);
    		$query = $em->createQuery('SELECT u FROM ClarolineCoreBundle:Resource\ResourceNode u WHERE u.creator = :id AND u.name = :name ' );
    		$query->setParameter('id', $user);
    		$query->setParameter('name', $ressource);
    		$node=$query->getResult();
    		foreach ($node as $noderes)
    		{
    			$noderesource= $noderes->getId();
    			$type = $noderes->getResourceType();
    			$idtype = $type->getId();
    		}
    		$query1 = $em->createQuery('SELECT u FROM ClarolineCoreBundle:Resource\ResourceType u WHERE u.id = :id' );
    		$query1->setParameter('id', $idtype);
    		$typeres=$query1->getResult();
    		foreach ($typeres as $ty)
    		{
    			$nomtype= $ty->getName();
    			 
    		}
    		$competence = $form->get('competence')->getData()->getTitre();
    		$etape->setCompetence($competence);
    		$competencecondition = $form->get('conditionvisibilite')->getData()->getTitre();
    		$etape->setConditionvisibilite($competencecondition);
    		$etape->setNomressource($nomtype);
    		$etape->setNodeid($noderesource);
    
    		$this->parcoursManager->insertEtape($etape);
    
    		 
    		$query = $em->createQuery('SELECT MAX(u.id) FROM Aip\ProfilageBundle\Entity\Etape u WHERE u.aggregate = :id' );
    		$query->setParameter('id', $id);
    		$cycleid=$query->getSingleScalarResult();
    		$this->container->get('router')->generate('claro_resource_open', array('resourceType' => 'aip_game', 'node' => '195'));
    
    		return $this->redirect(
    				$this->generateUrl(
    						'claro_etapeconfigue_create_form',
    						array('etapeId' => $cycleid)
    				)
    		);
    	}
    
    	return array(
    			'form' => $form->createView(),
    			'type' => 'create',
    			'_resource' => $aggregate
    	);
    }
    /**
     * @EXT\Route(
     *     "/etape/{etapeId}/edit/form",
     *     name = "claro_etape_edit_form"
     * )
     * @EXT\Method("GET")
     * @EXT\ParamConverter(
     *      "etape",
     *      class="AipProfilageBundle:Etape",
     *      options={"id"="etapeId","strictId"= true}
     * )
     * @EXT\Template("AipProfilageBundle::createparcoursForm.html.twig")
     *
     * @param Etape $etape
     *
     * @return Response
     */
    public function etapeEditFormAction(Etape $etape)
    {
    	$resource = $etape->getAggregate();
    	$user = $this->securityContext->getToken()->getUser();
    	$this->checkAccess('EDIT', $resource);
    	$etape->setCreator($user);
    	$form = $this->formFactory->create(new EtapeType(), $etape);
    
    	return array(
    			'form' => $form->createView(),
    			'type' => 'edit',
    			'etape' => $etape,
    			'_resource' => $resource
    	);
    }
    /**
     * @EXT\Route(
     *     "/etape/{etapeId}/edit",
     *     name = "claro_etape_edit"
     * )
     * @EXT\Method("POST")
     * @EXT\ParamConverter(
     *      "etape",
     *      class="AipProfilageBundle:Etape",
     *      options={"id"="etapeId","strictId"= true}
     * )
     * @EXT\Template("AipProfilageBundle::createparcoursForm.html.twig")
     *
     * @param Etape $etape
     *
     * @return Response
     */
    public function etapeEditAction(Etape $etape)
    {
    	$resource = $etape->getAggregate();
    	$id=$etape->getId();
    	$user = $this->securityContext->getToken()->getUser();
    	$form = $this->formFactory->create(new EtapeType(), $etape);
    
    	$request = $this->getRequest();
    	$form->handleRequest($request);
    
    
    	$now = new \DateTime();
    
    
    
        if($etape->isInitial())
           {
           	$etape->setInitial(true);
           	$etape->setEtat(true);
           }
    
    	$etape->setPublicationDate($now);
    	 
    	$ressource = $form->get('ressource')->getData()->getName();
    	$etape->setRessource($ressource);
    	$competence = $form->get('competence')->getData()->getTitre();
    	$etape->setCompetence($competence);
    	$competencecondition = $form->get('conditionvisibilite')->getData()->getTitre();
    	$etape->setConditionvisibilite($competencecondition);
    	 
    	$this->parcoursManager->insertEtape($etape);
    	$em=$this->getDoctrine()->getEntityManager();
    	$query1 = $em->createQuery('SELECT u FROM Aip\ProfilageBundle\Entity\EtapeConfigue u WHERE u.etapeid = :idetape' );
    	$query1->setParameter('idetape',$id);
    	$etape1=$query1->getResult();
    	foreach ($etape1 as $id)
    	{
    		$etapeconfigueid= $id->getId();
    	}
    
    	return $this->redirect(
    			$this->generateUrl(
    					'claro_etapeconfigue_edit_form',
    					array('etapeId' => $etape->getId())
    			)
    	);
    
    
    	return array(
    			'form' => $form->createView(),
    			'type' => 'edit',
    			'etape' => $etape,
    			'_resource' => $resource
    	);
    	 
    }
    /**
     * @EXT\Route(
     *     "/etape/{etapeId}/competence",
     *     name = "claro_etape_edit_competence"
     * )
     * @EXT\Method("GET")
     * @EXT\ParamConverter(
     *      "etape",
     *      class="AipProfilageBundle:Etape",
     *      options={"id"="etapeId","strictId"= true}
     * )
     * 
     *
     * @param Etape $etape
     *
     * @return Response
     */
    public function etapecompetenceEditFormAction(Etape $etape)
    {
    	$resource = $etape->getAggregate();
    	$id =  $etape->getId();
    	$user = $this->securityContext->getToken()->getUser();
    	$em=$this->getDoctrine()->getEntityManager();
    	$query = $em->createQuery('SELECT u FROM Aip\ProfilageBundle\Entity\Etape u  WHERE u.id = :id');
    	$query->setParameter('id',$id);
    	$etapecompetence=$query->getResult();
    	foreach ($etapecompetence as $etapeconp) {
    		 
    		$nomcompetence =$etapeconp->getCompetence();
    	}
    	$query1 = $em->createQuery('SELECT u FROM Aip\ProfilageBundle\Entity\EtapeConfigue u  WHERE u.etapeid = :id');
    	$query1->setParameter('id',$id);
    	$etapecyclecompetence=$query1->getResult();
    	foreach ($etapecyclecompetence as $etapecycleconp) {
    		 
    		$nomcyclecompetence =$etapecycleconp->getCycledevie();
    	}
    	$query2 = $em->createQuery('SELECT u FROM Aip\ProfilageBundle\Entity\Modelusager u  WHERE u.creator = :user AND u.aggregate = :aggregate AND u.competence = :competence');
    	$query2->setParameter('user',$user);
    	$query2->setParameter('aggregate',$resource->getId());
    	$query2->setParameter('competence',$nomcompetence);
    	$modelcompetence=$query2->getResult();
    	$query3 = $em->createQuery('SELECT u FROM Aip\ProfilageBundle\Entity\Etape u  WHERE u.aggregate = :id AND u.conditionvisibilite =:condition');
    	$query3->setParameter('id',$resource->getId());
    	$query3->setParameter('condition',$nomcompetence);
    	$etapecompcondition=$query3->getResult();
    	if (($etapecompcondition != null))
    	{
    		foreach ($etapecompcondition  as $etapecomp) {
    		
    			$idetape = $etapecomp->getId();
    			$query4 = $em->createQuery('SELECT u FROM Aip\ProfilageBundle\Entity\EtapeConfigue u  WHERE u.etapeid = :id AND u.conditionusager =:condition');
    			$query4->setParameter('id',$idetape);
    			$query4->setParameter('condition',$nomcyclecompetence);
    			$etapecyclecondition=$query4->getResult();
    			if ($etapecyclecondition != null)
    			{
    				$query5 = $em->createQuery('SELECT u FROM Aip\ProfilageBundle\Entity\Etape u  WHERE u.id = :id ');
    				$query5->setParameter('id',$idetape);
    				$etapeetat=$query5->getResult();
    				foreach ($etapeetat as $etapeetat) {
    				
    					$etapeetat->setEtat(true);
    					$em->persist($etapeetat);
    					$em->flush();
    				}
    			}
    		}
    	}
    	
    	if ($modelcompetence != null)
    	{
    		foreach ($modelcompetence as $modelusager) {
    			 
    			$modelusager->setCreator($user);
    	        $modelusager->setCompetence($nomcompetence);
    	        $modelusager->setCycledevie($nomcyclecompetence);
    	        $modelusager->setAggregate($resource->getId());
    	        $em->persist($modelusager);
    	        $em->flush();
    		}
    	}
    	if ($modelcompetence == null)
    	{
    	$modelusager = new Modelusager();
    	$modelusager->setCreator($user);
    	$modelusager->setCompetence($nomcompetence);
    	$modelusager->setCycledevie($nomcyclecompetence);
    	$modelusager->setAggregate($resource->getId());
    	$em->persist($modelusager);
    	$em->flush();
    	}
    	return $this->redirect(
    			$this->generateUrl(
    					'claro_etape_list',
    					array('aggregateId' =>$resource->getId())
    			)
    	);
    	return array(
    			
    			
    			'etape' => $etape,
    			'_resource' => $resource
    	);
    }
     /**
     * @EXT\Route(
     *     "/etapeconfigure/{etapeId}/",
     *     name = "claro_etapeconfigue_create_form"
     * )
     * @EXT\Method("GET")
     * @EXT\ParamConverter(
     *      "etape",
     *      class="AipProfilageBundle:Etape",
     *      options={"id"="etapeId","strictId"= true}
     * )
     * @EXT\Template("AipProfilageBundle::createconfigueForm.html.twig")
     *
     * @param Etape $etape
     *
     * @return Response
     */
    public function createconfigFormAction( Etape $etape)
    {
    	$resource = $etape->getAggregate();
    	$user = $this->securityContext->getToken()->getUser();
    	$nomcompetence = $etape->getCompetence();
    	$nomcompetencecondition = $etape->getConditionvisibilite();
    	$em = $this->getDoctrine()->getEntityManager();
    	$query = $em->createQuery('SELECT u FROM Aip\ProfilageBundle\Entity\GrilleCompetence u  WHERE u.titre = :name AND u.creator =:user');
    	$query->setParameter('name', $nomcompetence);
    	$query->setParameter('user', $user);
    	$grille=$query->getResult();
    	$query3 = $em->createQuery('SELECT u FROM Aip\ProfilageBundle\Entity\GrilleCompetence u  WHERE u.titre = :name AND u.creator =:user');
    	$query3->setParameter('name', $nomcompetencecondition);
    	$query3->setParameter('user', $user);
    	$grillecondition=$query3->getResult();
    	foreach ($grille as $gc) {
    	
    		 $nomcycle = $gc->getCycledevie();
    	
    	}
    	foreach ($grillecondition as $gcondition) {
    		 
    		$nomcyclecondition = $gcondition->getCycledevie();
    		 
    	}
    	$query1 = $em->createQuery('SELECT u FROM Aip\ProfilageBundle\Entity\CycleDeVieCompetence u  WHERE u.titrecycle = :name AND u.creator =:user');
    	$query1->setParameter('name', $nomcycle);
    	$query1->setParameter('user', $user);
    	$cycle=$query1->getResult();
    	$query4 = $em->createQuery('SELECT u FROM Aip\ProfilageBundle\Entity\CycleDeVieCompetence u  WHERE u.titrecycle = :name AND u.creator =:user');
    	$query4->setParameter('name', $nomcyclecondition);
    	$query4->setParameter('user', $user);
    	$cyclecondition=$query4->getResult();
    	foreach ($cycle as $cy) {
    		 
    		$idcycle = $cy->getId();
    	
    		 
    	}
    	foreach ($cyclecondition as $cycondition) {
    		 
    		$idcyclecondition = $cycondition->getId();
    		 
    		 
    	}
    	
    	$etapeconfigue = new EtapeConfigue();
    	$etapeconfigue->setCycleid($idcycle);
    	$etapeconfigue->setCycleconditionid($idcyclecondition);
    	$form = $this->formFactory->create(new EtapeConfigueType(), $etapeconfigue);
    
    	return array(
    			'form' => $form->createView(),
    			'type' => 'create',
    			'etape' =>$etape,
    			'_resource' => $resource
    			
    			 
    	);
    }
    /**
     * @EXT\Route(
     *     "/aggregate/{aggregateId}/createconfigure",
     *     name = "claro_etape_configue"
     * )
     * @EXT\Method("POST")
     * @EXT\ParamConverter(
     *      "aggregate",
     *      class="AipProfilageBundle:Parcours",
     *      options={"id" = "aggregateId", "strictId" = true}
     * )
     * @EXT\Template("AipProfilageBundle::createconfigueForm.html.twig")
     *
     * @param Parcours $aggregate
     *
     * @return Response
     */
    public function createconfigueAction(Parcours $aggregate)
    {
    	$this->checkAccess('EDIT', $aggregate);
    	$user = $this->securityContext->getToken()->getUser();
    	$id = $aggregate->getId();
    	$em=$this->getDoctrine()->getEntityManager();
    	$query = $em->createQuery('SELECT MAX(u.id) FROM Aip\ProfilageBundle\Entity\Etape u WHERE u.aggregate = :id' );
    	$query->setParameter('id', $aggregate);
    	$etapeid=$query->getSingleScalarResult();
    	 
    	$query1 = $em->createQuery('SELECT u FROM Aip\ProfilageBundle\Entity\Etape u WHERE u.id = :idetape AND u.aggregate = :aggregate' );
    	$query1->setParameter('idetape', $etapeid);
    	$query1->setParameter('aggregate',$aggregate);
    	$etape1=$query1->getResult();
    	foreach ($etape1 as $nom)
    	{
    		$nomcompetence= $nom->getCompetence();
    		$nomcompetencecondition = $nom->getConditionvisibilite();
    	}
    	$query2 = $em->createQuery('SELECT u FROM Aip\ProfilageBundle\Entity\GrilleCompetence u  WHERE u.titre = :name AND u.creator =:user');
    	$query2->setParameter('name', $nomcompetence);
    	$query2->setParameter('user', $user);
    	$grille=$query2->getResult();
    	$query4 = $em->createQuery('SELECT u FROM Aip\ProfilageBundle\Entity\GrilleCompetence u  WHERE u.titre = :name AND u.creator =:user');
    	$query4->setParameter('name', $nomcompetencecondition);
    	$query4->setParameter('user', $user);
    	$grillecondition=$query4->getResult();
    	foreach ($grille as $gc) {
    		 
    		$nomcycle = $gc->getCycledevie();
    		 
    	}
    	foreach ($grillecondition as $gcondition) {
    		 
    		$nomcyclecondition = $gcondition->getCycledevie();
    		 
    	}
    	 
    	$query3 = $em->createQuery('SELECT u FROM Aip\ProfilageBundle\Entity\CycleDeVieCompetence u  WHERE u.titrecycle = :name AND u.creator =:user');
    	$query3->setParameter('name', $nomcycle );
    	$query3->setParameter('user', $user);
    	$cycle=$query3->getResult();
    	$query5 = $em->createQuery('SELECT u FROM Aip\ProfilageBundle\Entity\CycleDeVieCompetence u  WHERE u.titrecycle = :name AND u.creator =:user');
    	$query5->setParameter('name', $nomcyclecondition );
    	$query5->setParameter('user', $user);
    	$cyclecondition=$query5->getResult();
    	foreach ($cycle as $cy) {
    		 
    		$idcycle = $cy->getId();
    		 
    		 
    	}
    	foreach ($cyclecondition as $cycondition) {
    		 
    		$idcyclecondition = $cycondition->getId();
    		 
    		 
    	}
    	$etapeconfigue = new EtapeConfigue();
    	$etapeconfigue->setCycleid($idcycle);
    	$etapeconfigue->setAggregate($aggregate->getId());
    	$etapeconfigue->setCycleconditionid($idcyclecondition);
    	$form = $this->formFactory->create(new EtapeConfigueType(), $etapeconfigue);
    	$request = $this->getRequest();
    	$form->handleRequest($request);
    
    	if ($form->isValid()) {
    
    		 
    		$cycleetape = $form->get('cycledevie')->getData()->getNom();
    		$etapeconfigue->setCycledevie($cycleetape);
    		$cycleetapecondition = $form->get('conditionusager')->getData()->getNom();
    		$etapeconfigue->setConditionusager($cycleetapecondition);
    		$etapeconfigue->setEtapeid($etapeid);
    		$em->persist($etapeconfigue);
    		$em->flush();
    
    		 
    
    
    
    
    		return $this->redirect(
    				$this->generateUrl(
    						'claro_etape_list',
    						array('aggregateId' =>$aggregate->getId())
    				)
    		);
    	}
    
    	return array(
    			'form' => $form->createView(),
    
    			'_resource' => $aggregate
    	);
    }
    /**
     * @EXT\Route(
     *     "/etapeconfigure/{etapeId}/edit",
     *     name = "claro_etapeconfigue_edit_form"
     * )
     * @EXT\Method("GET")
     * @EXT\ParamConverter(
     *      "etape",
     *      class="AipProfilageBundle:Etape",
     *      options={"id"="etapeId","strictId"= true}
     * )
     * @EXT\Template("AipProfilageBundle::createconfigueForm.html.twig")
     *
     * @param Etape $etape
     *
     * @return Response
     */
    public function createconfigEditFormAction(Etape $etape)
    {
    	$resource = $etape->getAggregate();
    	$user = $this->securityContext->getToken()->getUser();
    	
    	$em = $this->getDoctrine()->getEntityManager();
    	$id=$etape->getId();
    	$query = $em->createQuery('SELECT u FROM Aip\ProfilageBundle\Entity\EtapeConfigue u  WHERE u.etapeid = :id');
    	$query->setParameter('id',$id);
    	$etapeconfigue=$query->getResult();
    	foreach ($etapeconfigue as $etapeconf) {
    		 
    		$idcycle =$etapeconf->getCycleid();
    		$etapeconf->setCycleid($idcycle);
    		
    	}
    
    	$form = $this->formFactory->create(new EtapeConfigueType(),$etapeconf);
    	
    	
        
    	return array(
    			'form' => $form->createView(),
    			'type' => 'edit',
    			'etape' =>$etape,
    			'_resource' => $resource
    			 
    
    	);
    }
    /**
     * @EXT\Route(
     *     "/etapeconfigure/{etapeId}/edit",
     *     name = "claro_etapeconfigue",
     *     defaults={"page"=1}
     * )
     * @EXT\ParamConverter(
     *      "etape",
     *      class="AipProfilageBundle:Etape",
     *      options={"id" = "etapeId", "strictId" = true}
     * )
     * @EXT\Template("AipProfilageBundle::createconfigueForm.html.twig")
     *
     * @param Etape $etape
     *
     * @return Response
     */
    public function etapeconfigureAction(Etape $etape)
    {
    	
    	$resource = $etape->getAggregate();
    	$user = $this->securityContext->getToken()->getUser();
    	
    	$em = $this->getDoctrine()->getEntityManager();
    	$id=$etape->getId();
    	$query = $em->createQuery('SELECT u FROM Aip\ProfilageBundle\Entity\EtapeConfigue u  WHERE u.etapeid = :id');
    	$query->setParameter('id',$id);
    	$etapeconfigue=$query->getResult();
    	foreach ($etapeconfigue as $etapeconf) {
    		 
    		$idcycle =$etapeconf->getCycleid();
    		$idcyclecondition =$etapeconf->getCycleconditionid();
    		$etapeconf->setCycleid($idcycle);
    		$etapeconf->setCycleconditionid($idcyclecondition);
    		
    	}
    
    	$form = $this->formFactory->create(new EtapeConfigueType(),$etapeconf);
    	$request = $this->getRequest();
    	$form->handleRequest($request);
    	$cycleetape = $form->get('cycledevie')->getData()->getNom();
    	$etapeconf->setCycledevie($cycleetape);
    	$cycleetapecondition = $form->get('conditionusager')->getData()->getNom();
    	$etapeconf->setConditionusager($cycleetapecondition);
    	$etapeconf->setEtapeid($id);
    	$etapeconf->setAggregate($resource->getId());
    	$em->persist($etapeconf);
    	$em->flush();
    		return $this->redirect(
    				$this->generateUrl(
    						'claro_etape_list',
    						array('aggregateId' =>$resource->getId())
    				)
    		);
    		return array(
    				
    				'_resource' => $resource
    		
    		
    		);
    }
   
   
   
    /**
     * @EXT\Route(
     *     "/etape/{etapeId}/delete",
     *     name = "claro_etape_delete",
     *     options={"expose"=true}
     * )
     * @EXT\Method("DELETE")
     * @EXT\ParamConverter(
     *      "etape",
     *      class="AipProfilageBundle:Etape",
     *      options={"id" = "etapeId", "strictId" = true}
     * )
     *
     * @param Etape $etape
     *
     * @return Response
     */
    public function etapeDeleteAction(Etape $etape)
    {
    	$resource = $etape->getAggregate();
    	$em=$this->getDoctrine()->getEntityManager();
    	$id= $etape->getId();
    	$query = $em->createQuery('DELETE FROM Aip\ProfilageBundle\Entity\EtapeConfigue e WHERE e.etapeid = :idetape ');
    	$query->setParameter('idetape',$id);
    	$query->execute();
    	$this->checkAccess('EDIT', $resource);
    	$this->parcoursManager->deleteEtape($etape);
    
    	return new Response(204);
    }
    
    
    /**
     * @EXT\Route(
     *     "/parcours/workspace/{workspaceId}/page/{page}",
     *     name="claro_workspace_parcours_pager",
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
     * @EXT\Template("AipProfilageBundle::parcoursWorkspaceWidgetPager.html.twig")
     *
     * Renders etape in a pager.
     *
     * @return Response
     */
    public function etapeWorkspaceWidgetPagerAction(AbstractWorkspace $workspace, $page)
    {
    	$token = $this->securityContext->getToken();
    	$roles = $this->utils->getRoles($token);
    	$datas = $this->parcoursManager->getVisibleEtapeByWorkspace($workspace, $roles);
    	$pager = $this->pagerFactory->createPagerFromArray($datas, $page, 5);
    	
    	
    	
    	return array(
    			'datas' => $pager,
    			'widgetType' => 'workspace',
    			'workspaceId' => $workspace->getId()
    	);
    }
    
    /**
     * @EXT\Route(
     *     "/etape/page/{page}",
     *     name="claro_desktop_etape_pager",
     *     defaults={"page"=1},
     *     options={"expose"=true}
     * )
     * @EXT\Method("GET")
     *
     * @EXT\Template("AipProfilageBundle::etapeDesktopWidgetPager.html.twig")
     *
     * Renders etape in a pager.
     *
     * @return Response
     */
    public function etapeDesktopWidgetPagerAction($page)
    {
    	$token = $this->securityContext->getToken();
    	$roles = $this->utils->getRoles($token);
    	$workspaces = $this->workspaceManager->getWorkspacesByRoles($roles);
    	$datas = $this->parcoursManager->getVisibleEtapeByWorkspaces($workspaces, $roles);
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