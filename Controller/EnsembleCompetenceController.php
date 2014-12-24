<?php

namespace Aip\ProfilageBundle\Controller;

use Aip\ProfilageBundle\Entity\EnsembleCompetence;
use Aip\ProfilageBundle\Entity\Enablegrille;
use Aip\ProfilageBundle\Entity\GrilleCompetenceAggregate;
use Aip\ProfilageBundle\Form\EnsembleCompetenceType;
use Aip\ProfilageBundle\Manager\EnsembleCompetenceManager;
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

class EnsembleCompetenceController extends Controller
{
    private $ensemblecompetenceManager;
    private $formFactory;
    private $pagerFactory;
    private $securityContext;
    private $translator;
    private $utils;
    private $workspaceManager;

    /**
     * @DI\InjectParams({
     *     "ensemblecompetenceManager" = @DI\Inject("aip.ensmblecompetence.manager.ensemblecompetence_manager"),
     *     "formFactory"             = @DI\Inject("form.factory"),
     *     "pagerFactory"            = @DI\Inject("claroline.pager.pager_factory"),
     *     "securityContext"         = @DI\Inject("security.context"),
     *     "translator"              = @DI\Inject("translator"),
     *     "utils"                   = @DI\Inject("claroline.security.utilities"),
     *     "workspaceManager"        = @DI\Inject("claroline.manager.workspace_manager")
     * })
     */
    public function __construct(
        EnsembleCompetenceManager $ensemblecompetenceManager,
        FormFactoryInterface $formFactory,
        PagerFactory $pagerFactory,
        SecurityContextInterface $securityContext,
        Translator $translator,
        Utilities $utils,
        WorkspaceManager $workspaceManager
    )
    {
        $this->formFactory = $formFactory;
        $this->ensemblecompetenceManager = $ensemblecompetenceManager;
        $this->pagerFactory = $pagerFactory;
        $this->securityContext = $securityContext;
        $this->translator = $translator;
        $this->utils = $utils;
        $this->workspaceManager = $workspaceManager;
    }

    /**
     * @EXT\Route(
     *     "/ensemblecompetence/listensemble/aggregate/{aggregateId}/pagecycle/{page}",
     *     name = "claro_ensemblecompetence_list",
     *     defaults={"page"=1}
     * )
     * @EXT\ParamConverter(
     *      "aggregate",
     *      class="AipProfilageBundle:GrilleCompetenceAggregate",
     *      options={"id" = "aggregateId", "strictId" = true}
     * )
     * @EXT\Template("AipProfilageBundle::ensemblecompetenceList.html.twig")
     *
     * @param GrilleCompetenceAggregate $aggregate
     *
     * @return Response
     */
    public function ensemblecompetenceListAction(GrilleCompetenceAggregate $aggregate, $page)
    {
    	$collection = new ResourceCollection(array($aggregate->getResourceNode()));
    
    	try {
    		$this->checkAccess('EDIT', $aggregate);
    		$ensemblecompetence = $this->ensemblecompetenceManager->getAllEnsembleCompetenceByAggregate($aggregate);
    	}
    	catch(AccessDeniedException $e) {
    		$this->checkAccess('OPEN', $aggregate);
    		$ensemblecompetence = $this->ensemblecompetenceManager->getVisibleEnsembleCompetenceByAggregate($aggregate);
    	}
    	$pager = $this->pagerFactory->createPagerFromArray($ensemblecompetence, $page, 5);
    
    	return array(
    			'_resource' => $aggregate,
    			'ensemblecompetence' => $pager,
    			'resourceCollection' => $collection
    	);
    }
    
    /**
     * @EXT\Route(
     *     "/aggregate/{aggregateId}/createensemble/form",
     *     name = "claro_ensemblecompetence_create_form"
     * )
     * @EXT\Method("GET")
     * @EXT\ParamConverter(
     *      "aggregate",
     *      class="AipProfilageBundle:GrilleCompetenceAggregate",
     *      options={"id" = "aggregateId", "strictId" = true}
     * )
     * @EXT\Template("AipProfilageBundle::createensembleForm.html.twig")
     *
     * @param GrilleCompetenceAggregate $aggregate
     *
     * @return Response
     */
    public function createFormAction( GrilleCompetenceAggregate $aggregate)
    {
    	 
    	$this->checkAccess('EDIT', $aggregate);
    	$ensemblecompetence = new EnsembleCompetence();
    	
    	$form = $this->formFactory->create(new EnsembleCompetenceType(), $ensemblecompetence);
    
    	return array(
    			'form' => $form->createView(),
    			'type' => 'create',
    			'_resource' => $aggregate
    	);
    }
    

/**
 * @EXT\Route(
 *     "/aggregate/{aggregateId}/createensemble",
 *     name = "claro_ensemblecompetence_create"
 * )
 * @EXT\Method("POST")
 * @EXT\ParamConverter(
 *      "aggregate",
 *      class="AipProfilageBundle:GrilleCompetenceAggregate",
 *      options={"id" = "aggregateId", "strictId" = true}
 * )
 * @EXT\Template("AipProfilageBundle::createensembleForm.html.twig")
 *
 * @param GrilleCompetenceAggregate $aggregate
 *
 * @return Response
 */
public function createAction(GrilleCompetenceAggregate $aggregate)
{
	$this->checkAccess('EDIT', $aggregate);
	$id=$aggregate->getId();
	$em = $this->getDoctrine()->getEntityManager();
	$user = $this->securityContext->getToken()->getUser();
	$ensemblecompetence = new EnsembleCompetence();
	$form = $this->formFactory->create(new EnsembleCompetenceType(), $ensemblecompetence);
	$request = $this->getRequest();
	$form->handleRequest($request);
	if ($form->isValid()) {
	
		$now = new \DateTime();
	
		 
		
			$this->get('session')->getFlashBag()->add(
					'danger');
			 
		 
		$ensemblecompetence->setAggregate($aggregate);
		$ensemblecompetence->setCreationDate($now);
		$ensemblecompetence->setPublicationDate($now);
		
		$ensemblecompetence->setCreator($user);
		$this->ensemblecompetenceManager->insertEnsembleCompetence($ensemblecompetence);
		$query = $em->createQuery('SELECT u FROM Aip\ProfilageBundle\Entity\CycleDeVieCompetence u WHERE u.aggregate = :aggregate ');
		$query->setParameter('aggregate',$aggregate);
		$cycledevie=$query->getResult();
		$query1 = $em->createQuery('SELECT u FROM Aip\ProfilageBundle\Entity\Enablegrille u WHERE u.aggregate = :id');
		$query1->setParameter('id', $id);
		$enable=$query1->getResult();
		if(($cycledevie!=null)&&($enable==null))
			
		{
			$disabledgrille = new Enablegrille();
			$disabledgrille->setEnable(true);
			$disabledgrille->setAggregate($id);
			$em->persist($disabledgrille);
			$em->flush();
		}
		 
		return $this->redirect(
				$this->generateUrl(
						'claro_ensemblecompetence_list',
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
 *     "/ensemblecompetence/{ensemblecompetenceId}/edit/form",
 *     name = "claro_ensemblecompetence_edit_form"
 * )
 * @EXT\Method("GET")
 * @EXT\ParamConverter(
 *      "ensemblecompetence",
 *      class="AipProfilageBundle:EnsembleCompetence",
 *      options={"id"="ensemblecompetenceId","strictId"= true}
 * )
 * @EXT\Template("AipProfilageBundle::createensembleForm.html.twig")
 *
 * @param EnsembleCompetence $ensemblecompetence
 *
 * @return Response
 */
public function ensemblecompetenceEditFormAction(EnsembleCompetence $ensemblecompetence)
{
	$resource = $ensemblecompetence->getAggregate();

	$this->checkAccess('EDIT', $resource);
	$form = $this->formFactory->create(new EnsembleCompetenceType(), $ensemblecompetence);

	return array(
			'form' => $form->createView(),
			'type' => 'edit',
			'ensemblecompetence' => $ensemblecompetence,
			'_resource' => $resource
	);
}
/**
 * @EXT\Route(
 *     "/ensemblecompetence/{ensemblecompetenceId}/edit",
 *     name = "claro_ensemblecompetence_edit"
 * )
 * @EXT\Method("POST")
 * @EXT\ParamConverter(
 *      "ensemblecompetence",
 *      class="AipProfilageBundle:EnsembleCompetence",
 *      options={"id"="ensemblecompetenceId","strictId"= true}
 * )
 * @EXT\Template("AipProfilageBundle::createensembleForm.html.twig")
 *
 * @param EnsembleCompetence $ensemblecompetence
 *
 * @return Response
 */
public function ensemblecompetenceEditAction(EnsembleCompetence $ensemblecompetence)
{
	
	$resource = $ensemblecompetence->getAggregate();
	 
	$this->checkAccess('EDIT', $resource);
	$form = $this->formFactory->create(new EnsembleCompetenceType(), $ensemblecompetence);
	
	$request = $this->getRequest();
	$form->handleRequest($request);
	

		$now = new \DateTime();
		
		 
		
			$this->get('session')->getFlashBag()->add(
					'danger');
			 
			
			$ensemblecompetence->setPublicationDate($now);
		 
		
		$this->ensemblecompetenceManager->insertEnsembleCompetence($ensemblecompetence);
		 
		return $this->redirect(
				$this->generateUrl(
						'claro_ensemblecompetence_list',
						array('aggregateId' => $resource->getId())
				)
		);
	
	 
	return array(
			'form' => $form->createView(),
			'type' => 'edit',
			'ensemblecompetence' => $ensemblecompetence,
			'_resource' => $resource
	);
}

/**
 * @EXT\Route(
 *     "/ensemblecompetence/{ensemblecompetenceId}/delete",
 *     name = "claro_ensemblecompetence_delete",
 *     options={"expose"=true}
 * )
 * @EXT\Method("DELETE")
 * @EXT\ParamConverter(
 *      "ensemblecompetence",
 *      class="AipProfilageBundle:EnsembleCompetence",
 *      options={"id" = "ensemblecompetenceId", "strictId" = true}
 * )
 *
 * @param EnsembleCompetence $ensemblecompetence
 *
 * @return Response
 */
public function ensemblecompetenceDeleteAction(EnsembleCompetence $ensemblecompetence)
{
	$em = $this->getDoctrine()->getEntityManager();
	$resource = $ensemblecompetence->getAggregate();
	$id=$resource->getId();
	$this->checkAccess('EDIT', $resource);
	$this->ensemblecompetenceManager->deleteEnsembleCompetence($ensemblecompetence);
	$query = $em->createQuery('SELECT u FROM Aip\ProfilageBundle\Entity\CycleDeVieCompetence u  WHERE u.aggregate = :aggregate');
	$query->setParameter('aggregate',$resource);
	$cycledevie=$query->getResult();
	$query1 = $em->createQuery('SELECT u FROM Aip\ProfilageBundle\Entity\EnsembleCompetence u WHERE u.aggregate = :aggregate');
	$query1->setParameter('aggregate',$resource);
	$ensemble=$query1->getResult();
	if(($ensemble==null)or($cycledevie==null))
	{
		$query2 = $em->createQuery('DELETE FROM Aip\ProfilageBundle\Entity\Enablegrille e WHERE e.aggregate = :id');
		$query2->setParameter('id',$id);
		$query2->execute();
		$query3 = $em->createQuery('DELETE FROM Aip\ProfilageBundle\Entity\GrilleCompetence e WHERE e.aggregate = :aggregate');
		$query3->setParameter('aggregate',$resource);
		$query3->execute();
	}
	return new Response(204);
}


/**
 * @EXT\Route(
 *     "/ensemblecompetence/workspace/{workspaceId}/page/{page}",
 *     name="claro_workspace_ensemblecompetence_pager",
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
 * @EXT\Template("AipProfilageBundle::ensemblecompetenceWorkspaceWidgetPager.html.twig")
 *
 * Renders ensemblecompetence in a pager.
 *
 * @return Response
 */
public function ensemblecompetenceWorkspaceWidgetPagerAction(AbstractWorkspace $workspace, $page)
{
	$token = $this->securityContext->getToken();
	$roles = $this->utils->getRoles($token);
	$datas = $this->ensemblecompetenceManager->getVisibleEnsembleCompetenceByWorkspace($workspace, $roles);
	$pager = $this->pagerFactory->createPagerFromArray($datas, $page, 5);

	return array(
			'datas' => $pager,
			'widgetType' => 'workspace',
			'workspaceId' => $workspace->getId()
	);
}
/**
 * @EXT\Route(
 *     "/ensemblecompetence/page/{page}",
 *     name="claro_desktop_ensemblecompetence_pager",
 *     defaults={"page"=1},
 *     options={"expose"=true}
 * )
 * @EXT\Method("GET")
 *
 * @EXT\Template("AipProfilageBundle::ensemblecompetenceDesktopWidgetPager.html.twig")
 *
 * Renders ensemblecompetence in a pager.
 *
 * @return Response
 */
public function ensemblecompetenceDesktopWidgetPagerAction($page)
{
	$token = $this->securityContext->getToken();
	$roles = $this->utils->getRoles($token);
	$workspaces = $this->workspaceManager->getWorkspacesByRoles($roles);
	$datas = $this->ensemblecompetenceManager->getVisibleCompetenceCompetenceByWorkspaces($workspaces, $roles);
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