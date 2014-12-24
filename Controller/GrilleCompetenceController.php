<?php

namespace Aip\ProfilageBundle\Controller;

use Aip\ProfilageBundle\Entity\GrilleCompetence;
use Aip\ProfilageBundle\Entity\GrilleCompetenceAggregate;
use Aip\ProfilageBundle\Form\GrilleCompetenceType;
use Aip\ProfilageBundle\Manager\GrilleCompetenceManager;
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

class GrilleCompetenceController extends Controller
{
    private $grillecompetenceManager;
    private $formFactory;
    private $pagerFactory;
    private $securityContext;
    private $translator;
    private $utils;
    private $workspaceManager;

    /**
     * @DI\InjectParams({
     *     "grillecompetenceManager" = @DI\Inject("aip.grillecompetence.manager.grillecompetence_manager"),
     *     "formFactory"             = @DI\Inject("form.factory"),
     *     "pagerFactory"            = @DI\Inject("claroline.pager.pager_factory"),
     *     "securityContext"         = @DI\Inject("security.context"),
     *     "translator"              = @DI\Inject("translator"),
     *     "utils"                   = @DI\Inject("claroline.security.utilities"),
     *     "workspaceManager"        = @DI\Inject("claroline.manager.workspace_manager")
     * })
     */
    public function __construct(
        GrilleCompetenceManager $grillecompetenceManager,
        FormFactoryInterface $formFactory,
        PagerFactory $pagerFactory,
        SecurityContextInterface $securityContext,
        Translator $translator,
        Utilities $utils,
        WorkspaceManager $workspaceManager
    )
    {
        $this->formFactory = $formFactory;
        $this->grillecompetenceManager = $grillecompetenceManager;
        $this->pagerFactory = $pagerFactory;
        $this->securityContext = $securityContext;
        $this->translator = $translator;
        $this->utils = $utils;
        $this->workspaceManager = $workspaceManager;
    }

    /**
     * @EXT\Route(
     *     "/grillecompetence/list/aggregate/{aggregateId}/page/{page}",
     *     name = "claro_grillecompetence_list",
     *     defaults={"page"=1}
     * )
     * @EXT\Method("GET")
     * @EXT\ParamConverter(
     *      "aggregate",
     *      class="AipProfilageBundle:GrilleCompetenceAggregate",
     *      options={"id" = "aggregateId", "strictId" = true}
     * )
     * @EXT\Template("AipProfilageBundle::grillecompetenceList.html.twig")
     *
     * @param GrilleCompetenceAggregate $aggregate
     *
     * @return Response
     */
    public function grillecompetenceListAction(GrilleCompetenceAggregate $aggregate, $page)
    {
        $collection = new ResourceCollection(array($aggregate->getResourceNode()));
        $id = $aggregate->getId();

        try {
        	$em=$this->getDoctrine()->getEntityManager();
        	$this->checkAccess('EDIT', $aggregate);
            $grillecompetence = $this->grillecompetenceManager->getAllGrilleCompetenceByAggregate($aggregate);
            $query = $em->createQuery('SELECT u FROM Aip\ProfilageBundle\Entity\Enablegrille u WHERE u.aggregate = :id' );
            $query->setParameter('id', $id);
            $enablegrille=$query->getResult();
            
        }
        catch(AccessDeniedException $e) {
        	$this->checkAccess('OPEN', $aggregate);
            $grillecompetence = $this->grillecompetenceManager->getVisibleGrilleCompetenceByAggregate($aggregate);
            $query = $em->createQuery('SELECT u FROM Aip\ProfilageBundle\Entity\Enablegrille u WHERE u.aggregate = :id' );
            $query->setParameter('id', $id);
            $enablegrille=$query->getResult();
        }
        $pager = $this->pagerFactory->createPagerFromArray($grillecompetence, $page, 5);

        return array(
            '_resource' => $aggregate,
            'grillecompetence' => $pager,
            'resourceCollection' => $collection,
        	'enablegrille' => $enablegrille
        );
    }
    
    /**
     * @EXT\Route(
     *     "/aggregate/{aggregateId}/create/form",
     *     name = "claro_grillecompetence_create_form"
     * )
     * @EXT\Method("GET")
     * @EXT\ParamConverter(
     *      "aggregate",
     *      class="AipProfilageBundle:GrilleCompetenceAggregate",
     *      options={"id" = "aggregateId", "strictId" = true}
     * )
     * @EXT\Template("AipProfilageBundle::createForm.html.twig")
     *
     * @param GrilleCompetenceAggregate $aggregate
     *
     * @return Response
     */
    public function createFormAction( GrilleCompetenceAggregate $aggregate)
    {
    	
    	$this->checkAccess('EDIT', $aggregate);
    	$grillecompetence = new GrilleCompetence();
    	$grillecompetence->setAggregate($aggregate);
    	$grillecompetence->setVisible(true);
    	$form = $this->formFactory->create(new GrilleCompetenceType(), $grillecompetence);
    
    	return array(
    			'form' => $form->createView(),
    			'type' => 'create',
    			'_resource' => $aggregate
    	);
    }
   

    /**
     * @EXT\Route(
     *     "/aggregate/{aggregateId}/create",
     *     name = "claro_grillecompetence_create"
     * )
     * @EXT\Method("POST")
     * @EXT\ParamConverter(
     *      "aggregate",
     *      class="AipProfilageBundle:GrilleCompetenceAggregate",
     *      options={"id" = "aggregateId", "strictId" = true}
     * )
     * @EXT\Template("AipProfilBundle::createForm.html.twig")
     *
     * @param GrilleCompetenceAggregate $aggregate
     *
     * @return Response
     */
    public function createAction(GrilleCompetenceAggregate $aggregate)
    {
    	$this->checkAccess('EDIT', $aggregate);
    	
    	$user = $this->securityContext->getToken()->getUser();
    	$grillecompetence = new GrilleCompetence();
    	$grillecompetence->setAggregate($aggregate);
    	$form = $this->formFactory->create(new GrilleCompetenceType(), $grillecompetence);
    	$request = $this->getRequest();
    	$form->handleRequest($request);
    	
    	if ($form->isValid()) {
    		$now = new \DateTime();
    		$visibleFrom = $grillecompetence->getVisibleFrom();
    		$visibleUntil = $grillecompetence->getVisibleUntil();
    	
    		if (!is_null($visibleFrom) && !is_null($visibleUntil) && $visibleUntil <= $visibleFrom) {
    			$this->get('session')->getFlashBag()->add(
    					'danger');
    	
    			return array(
    					'form' => $form->createView(),
    					'type' => 'create',
    					'_resource' => $aggregate
    			);
    		}
    	
    		
    		$grillecompetence->setCreationDate($now);
    	
    		if ($grillecompetence->isVisible()) {
    			if (is_null($visibleFrom) || $visibleFrom < $now) {
    				$grillecompetence->setPublicationDate($now);
    			}
    			else {
    				$grillecompetence->setPublicationDate($visibleFrom);
    			}
    		}
    		$grillecompetence->setCreator($user);
    		$defcycle = $form->get('cycledevie')->getData()->getTitreCycle();
    		$grillecompetence->setCycledevie($defcycle);
    		$defensemble = $form->get('ensembledef')->getData()->getTitreens();
    		$grillecompetence->setEnsembledef($defensemble);
    	    $this->grillecompetenceManager->insertGrilleCompetence($grillecompetence);
    	
    		return $this->redirect(
    				$this->generateUrl(
    						'claro_grillecompetence_list',
    						array('aggregateId' => $aggregate->getId())
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
     *     "/grillecompetence/{grillecompetenceId}/edit/form",
     *     name = "claro_grillecompetence_edit_form"
     * )
     * @EXT\Method("GET")
     * @EXT\ParamConverter(
     *      "grillecompetence",
     *      class="AipProfilageBundle:GrilleCompetence",
     *      options={"id"="grillecompetenceId","strictId"= true}
     * )
     * @EXT\Template("AipProfilageBundle::createForm.html.twig")
     *
     * @param GrilleCompetence $grillecompetence
     *
     * @return Response
     */
    public function grillecompetenceEditFormAction(GrilleCompetence $grillecompetence)
    {
    	$resource = $grillecompetence->getAggregate();
    	
    	$this->checkAccess('EDIT', $resource);
    	$grillecompetence->setAggregate($resource);
    	$form = $this->formFactory->create(new GrilleCompetenceType(), $grillecompetence);
    
    	return array(
    			'form' => $form->createView(),
    			'type' => 'edit',
    			'grillecompetence' => $grillecompetence,
    			'_resource' => $resource
    	);
    }
    /**
     * @EXT\Route(
     *     "/grillecompetence/{grillecompetenceId}/edit",
     *     name = "claro_grillecompetence_edit"
     * )
     * @EXT\Method("POST")
     * @EXT\ParamConverter(
     *      "grillecompetence",
     *      class="AipProfilageBundle:GrilleCompetence",
     *      options={"id"="grillecompetenceId","strictId"= true}
     * )
     * @EXT\Template("AipProfilageBundle::createForm.html.twig")
     *
     * @param GrilleCompetence $grillecompetence
     *
     * @return Response
     */
    public function grillecompetenceEditAction(GrilleCompetence $grillecompetence)
    {
    	$resource = $grillecompetence->getAggregate();
    	
    	$this->checkAccess('EDIT', $resource);
    	$form = $this->formFactory->create(new GrilleCompetenceType(), $grillecompetence);
    
    	$request = $this->getRequest();
    	$form->handleRequest($request);
    
    	if ($form->isValid()) {
    		$now = new \DateTime();
    		$visibleFrom = $grillecompetence->getVisibleFrom();
    		$visibleUntil = $grillecompetence->getVisibleUntil();
    	
    		if (!is_null($visibleFrom) && !is_null($visibleUntil) && $visibleUntil <= $visibleFrom) {
    			$this->get('session')->getFlashBag()->add(
    					'danger');
    	
    			return array(
    					'form' => $form->createView(),
    					'type' => 'edit',
    					'grillecompetence' => $grillecompetence,
    					'_resource' => $resource
    			);
    		}
    	
    		if (!$grillecompetence->isVisible()) {
    			$grillecompetence->setPublicationDate(null);
    		}
    		else {
    			if (is_null($visibleFrom) || $visibleFrom < $now) {
    				$grillecompetence->setPublicationDate($now);
    			}
    			else {
    				$grillecompetence->setPublicationDate($visibleFrom);
    			}
    		}
    		$defcycle = $form->get('cycledevie')->getData()->getTitreCycle();
    		$grillecompetence->setCycledevie($defcycle);
    		$defensemble = $form->get('ensembledef')->getData()->getTitreens();
    		$grillecompetence->setEnsembledef($defensemble);
    		$this->grillecompetenceManager->insertGrilleCompetence($grillecompetence);
    	
    		return $this->redirect(
    				$this->generateUrl(
    						'claro_grillecompetence_list',
    						array('aggregateId' => $resource->getId())
    				)
    		);
    	}
    	
    	return array(
    			'form' => $form->createView(),
    			'type' => 'edit',
    			'grillecompetence' => $grillecompetence,
    			'_resource' => $resource
    	);
    }
    /**
     * @EXT\Route(
     *     "/grillecompetence/{grillecompetenceId}/delete",
     *     name = "claro_grillecompetence_delete",
     *     options={"expose"=true}
     * )
     * @EXT\Method("DELETE")
     * @EXT\ParamConverter(
     *      "grillecompetence",
     *      class="AipProfilageBundle:GrilleCompetence",
     *      options={"id" = "grillecompetenceId", "strictId" = true}
     * )
     *
     * @param GrilleCompetence $grillecompetence
     *
     * @return Response
     */
    public function grillecompetenceDeleteAction(GrilleCompetence $grillecompetence)
    {
    	$resource = $grillecompetence->getAggregate();
    	$this->checkAccess('EDIT', $resource);
    	$this->grillecompetenceManager->deleteGrilleCompetence($grillecompetence);
    
    	return new Response(204);
    }
    

    /**
     * @EXT\Route(
     *     "/grillecompetence/workspace/{workspaceId}/page/{page}",
     *     name="claro_workspace_grillecompetence_pager",
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
     * @EXT\Template("AipProfilageBundle::grillecompetenceWorkspaceWidgetPager.html.twig")
     *
     * Renders grillecompetence in a pager.
     *
     * @return Response
     */
    public function grillecompetenceWorkspaceWidgetPagerAction(AbstractWorkspace $workspace, $page)
    {
    	$token = $this->securityContext->getToken();
    	$roles = $this->utils->getRoles($token);
    	$datas = $this->grillecompetenceManager->getVisibleGrilleCompetenceByWorkspace($workspace, $roles);
    	$pager = $this->pagerFactory->createPagerFromArray($datas, $page, 5);
    
    	return array(
    			'datas' => $pager,
    			'widgetType' => 'workspace',
    			'workspaceId' => $workspace->getId()
    	);
    }
    
    /**
     * @EXT\Route(
     *     "/grillecompetence/page/{page}",
     *     name="claro_desktop_grillecompetence_pager",
     *     defaults={"page"=1},
     *     options={"expose"=true}
     * )
     * @EXT\Method("GET")
     *
     * @EXT\Template("AipProfilageBundle::grillecompetenceDesktopWidgetPager.html.twig")
     *
     * Renders grillecompetence in a pager.
     *
     * @return Response
     */
    public function grillecompetenceDesktopWidgetPagerAction($page)
    {
    	$token = $this->securityContext->getToken();
    	$roles = $this->utils->getRoles($token);
    	$workspaces = $this->workspaceManager->getWorkspacesByRoles($roles);
    	$datas = $this->grillecompetenceManager->getVisibleGrilleCompetenceByWorkspaces($workspaces, $roles);
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