<?php

namespace Aip\ProfilageBundle\Controller;

use Aip\ProfilageBundle\Entity\Preference;
use Aip\ProfilageBundle\Entity\PreferenceAggregate;
use Aip\ProfilageBundle\Form\PreferenceType;
use Aip\ProfilageBundle\Manager\PreferenceManager;
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

class PreferenceController extends Controller
{
    private $preferenceManager;
    private $formFactory;
    private $pagerFactory;
    private $securityContext;
    private $translator;
    private $utils;
    private $workspaceManager;

    /**
     * @DI\InjectParams({
     *     "preferenceManager"       = @DI\Inject("aip.preference.manager.preference_manager"),
     *     "formFactory"             = @DI\Inject("form.factory"),
     *     "pagerFactory"            = @DI\Inject("claroline.pager.pager_factory"),
     *     "securityContext"         = @DI\Inject("security.context"),
     *     "translator"              = @DI\Inject("translator"),
     *     "utils"                   = @DI\Inject("claroline.security.utilities"),
     *     "workspaceManager"        = @DI\Inject("claroline.manager.workspace_manager")
     * })
     */
    public function __construct(
        PreferenceManager $preferenceManager,
        FormFactoryInterface $formFactory,
        PagerFactory $pagerFactory,
        SecurityContextInterface $securityContext,
        Translator $translator,
        Utilities $utils,
        WorkspaceManager $workspaceManager
    )
    {
        $this->formFactory = $formFactory;
        $this->preferenceManager = $preferenceManager;
        $this->pagerFactory = $pagerFactory;
        $this->securityContext = $securityContext;
        $this->translator = $translator;
        $this->utils = $utils;
        $this->workspaceManager = $workspaceManager;
    }

    /**
     * @EXT\Route(
     *     "/preference/list/aggregate/{aggregateId}/page/{page}",
     *     name = "claro_preference_list",
     *     defaults={"page"=1}
     * )
     * @EXT\ParamConverter(
     *      "aggregate",
     *      class="AipProfilageBundle:PreferenceAggregate",
     *      options={"id" = "aggregateId", "strictId" = true}
     * )
     * @EXT\Template("AipProfilageBundle::preferenceList.html.twig")
     *
     * @param PreferenceAggregate $aggregate
     *
     * @return Response
     */
    public function preferenceListAction(PreferenceAggregate $aggregate, $page)
    {
    	$collection = new ResourceCollection(array($aggregate->getResourceNode()));
    	
    	try {
    		$this->checkAccess('EDIT', $aggregate);
    		$preference = $this->preferenceManager->getAllPreferenceByAggregate($aggregate);
    	}
    	catch(AccessDeniedException $e) {
    		$this->checkAccess('OPEN', $aggregate);
    		$preference = $this->preferenceManager->getVisiblePreferenceByAggregate($aggregate);
    	}
    	$pager = $this->pagerFactory->createPagerFromArray($preference, $page, 5);
    	
    	return array(
    			'_resource' => $aggregate,
    			'preference' => $pager,
    			'resourceCollection' => $collection
    	);
    	
    }
    /**
     * @EXT\Route(
     *     "/preferenceliste/list/aggregate/{aggregateId}/page/{page}",
     *     name = "claro_preference_voir",
     *     defaults={"page"=1}
     * )
     * @EXT\ParamConverter(
     *      "aggregate",
     *      class="AipProfilageBundle:PreferenceAggregate",
     *      options={"id" = "aggregateId", "strictId" = true}
     * )
     * @EXT\Template("AipProfilageBundle::EnsemblepreferenceList.html.twig")
     *
     * @param PreferenceAggregate $aggregate
     *
     * @return Response
     */
    public function preferencelisteListAction(PreferenceAggregate $aggregate, $page)
    {
    	$collection = new ResourceCollection(array($aggregate->getResourceNode()));
    	 
    	try {
    		$this->checkAccess('EDIT', $aggregate);
    		$preference = $this->preferenceManager->getAllPreferenceByAggregate($aggregate);
    	}
    	catch(AccessDeniedException $e) {
    		$this->checkAccess('OPEN', $aggregate);
    		$preference = $this->preferenceManager->getVisiblePreferenceByAggregate($aggregate);
    	}
    	$pager = $this->pagerFactory->createPagerFromArray($preference, $page, 5);
    	 
    	return array(
    			'_resource' => $aggregate,
    			'preference' => $pager,
    			'resourceCollection' => $collection
    	);
    	 
    }
    
    /**
     * @EXT\Route(
     *     "/aggregate/{aggregateId}/createpreference/form",
     *     name = "claro_preference_create_form"
     * )
     * @EXT\Method("GET")
     * @EXT\ParamConverter(
     *      "aggregate",
     *      class="AipProfilageBundle:PreferenceAggregate",
     *      options={"id" = "aggregateId", "strictId" = true}
     * )
     * @EXT\Template("AipProfilageBundle::createpreferenceForm.html.twig")
     *
     * @param PreferenceAggregate $aggregate
     *
     * @return Response
     */
    public function createFormAction( PreferenceAggregate $aggregate)
    {
    
    	$this->checkAccess('modify', $aggregate);
    	$preference = new Preference();
    	 
    	$form = $this->formFactory->create(new PreferenceType(), $preference);
    
    	return array(
    			'form' => $form->createView(),
    			'type' => 'create',
    			'_resource' => $aggregate
    	);
    }
    
    
    /**
     * @EXT\Route(
     *     "/aggregate/{aggregateId}/createpreference",
     *     name = "claro_preference_create"
     * )
     * @EXT\Method("POST")
     * @EXT\ParamConverter(
     *      "aggregate",
     *      class="AipProfilageBundle:PreferenceAggregate",
     *      options={"id" = "aggregateId", "strictId" = true}
     * )
     * @EXT\Template("AipProfilageBundle::createpreferenceForm.html.twig")
     *
     * @param PreferenceAggregate $aggregate
     *
     * @return Response
     */
    public function createAction(PreferenceAggregate $aggregate)
    {
    	$this->checkAccess('modify', $aggregate);
    
    	$user = $this->securityContext->getToken()->getUser();
    	$preference = new Preference();
    	$form = $this->formFactory->create(new PreferenceType(), $preference);
    	$request = $this->getRequest();
    	$form->handleRequest($request);
    	$now = new \DateTime();	
    	$preference->setAggregate($aggregate);
    	$preference->setCreationDate($now);
    	$preference->setPublicationDate($now);
    	$preference->setCreator($user);
    	if (($preference->isPratique())&&($preference->isTheorique()==false)&&($preference->isvideos()==false))
    	{
    		$preference->setType("Pratique");
    	}
        if (($preference->isPratique()==false)&&($preference->isTheorique())&&($preference->isvideos()==false))
    	{
    		$preference->setType("Théorique");
    	}
    	if (($preference->isPratique()==false)&&($preference->isTheorique()==false)&&($preference->isvideos()))
    	{
    		$preference->setType("Vidéos");
    	}
    	if (($preference->isPratique())&&($preference->isTheorique())&&($preference->isvideos()==false))
    	{
    		$preference->setType("Pratique,Théorique");
    	}
    	if (($preference->isPratique())&&($preference->isTheorique()==false)&&($preference->isvideos()))
    	{
    		$preference->setType("Pratique,Vidéos");
    	}
    	if (($preference->isPratique()==false)&&($preference->isTheorique())&&($preference->isvideos()))
    	{
    		$preference->setType("Théorique,Vidéos");
    	}
    	if (($preference->isPratique())&&($preference->isTheorique())&&($preference->isvideos()))
    	{
    		$preference->setType("Pratique,Théorique,Vidéos");
    	}
    	$this->preferenceManager->insertPreference($preference);
    		
    	return $this->redirect(
    			$this->generateUrl(
    					'claro_preference_list',
    					array('aggregateId' => $aggregate->getId())
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
     *     "/preference/{preferenceId}/edit/form",
     *     name = "claro_preference_edit_form"
     * )
     * @EXT\Method("GET")
     * @EXT\ParamConverter(
     *      "preference",
     *      class="AipProfilageBundle:Preference",
     *      options={"id"="preferenceId","strictId"= true}
     * )
     * @EXT\Template("AipProfilageBundle::createpreferenceForm.html.twig")
     *
     * @param Preference $preference
     *
     * @return Response
     */
    public function preferenceEditFormAction(Preference $preference)
    {
    	$resource = $preference->getAggregate();
    
    	
    	$this->checkAccess('modify', $resource);
    	$form = $this->formFactory->create(new PreferenceType(), $preference);
    
    	return array(
    			'form' => $form->createView(),
    			'type' =>'modify',
    			'preference' => $preference,
    			'_resource' => $resource
    	);
    }
    /**
     * @EXT\Route(
     *     "/preference/{preferenceId}/edit",
     *     name = "claro_preference_edit"
     * )
     * @EXT\Method("POST")
     * @EXT\ParamConverter(
     *      "preference",
     *      class="AipProfilageBundle:Preference",
     *      options={"id"="preferenceId","strictId"= true}
     * )
     * @EXT\Template("AipProfilageBundle::createpreferenceForm.html.twig")
     *
     * @param Preference $preference
     *
     * @return Response
     */
    public function preferenceEditAction(Preference $preference)
    {
    
    	$resource = $preference->getAggregate();
    
    	$this->checkAccess('modify', $resource);
    	$form = $this->formFactory->create(new PreferenceType(), $preference);
    
    	$request = $this->getRequest();
    	$form->handleRequest($request);
    
    
    	$now = new \DateTime();
    
    		
    
    	$this->get('session')->getFlashBag()->add(
    			'danger');
    
    		
    	$preference->setPublicationDate($now);
    	if (($preference->isPratique())&&($preference->isTheorique()==false)&&($preference->isvideos()==false))
    	{
    		$preference->setType("Pratique");
    	}
    	if (($preference->isPratique()==false)&&($preference->isTheorique())&&($preference->isvideos()==false))
    	{
    		$preference->setType("Théorique");
    	}
    	if (($preference->isPratique()==false)&&($preference->isTheorique()==false)&&($preference->isvideos()))
    	{
    		$preference->setType("Vidéos");
    	}
    	if (($preference->isPratique())&&($preference->isTheorique())&&($preference->isvideos()==false))
    	{
    		$preference->setType("Pratique,Théorique");
    	}
    	if (($preference->isPratique())&&($preference->isTheorique()==false)&&($preference->isvideos()))
    	{
    		$preference->setType("Pratique,Vidéos");
    	}
    	if (($preference->isPratique()==false)&&($preference->isTheorique())&&($preference->isvideos()))
    	{
    		$preference->setType("Théorique,Vidéos");
    	}
    	if (($preference->isPratique())&&($preference->isTheorique())&&($preference->isvideos()))
    	{
    		$preference->setType("Pratique,Théorique,Vidéos");
    	}
    
    	$this->preferenceManager->insertPreference($preference);
    		
    	return $this->redirect(
    			$this->generateUrl(
    					'claro_preference_list',
    					array('aggregateId' => $resource->getId())
    			)
    	);
    
    
    	return array(
    			'form' => $form->createView(),
    			'type' => 'modify',
    			'preference' => $preference,
    			'_resource' => $resource
    	);
    }
    /**
     * @EXT\Route(
     *     "/preference/{preferenceId}/delete",
     *     name = "claro_preference_delete",
     *     options={"expose"=true}
     * )
     * @EXT\Method("DELETE")
     * @EXT\ParamConverter(
     *      "preference",
     *      class="AipProfilageBundle:Preference",
     *      options={"id" = "preferenceId", "strictId" = true}
     * )
     *
     * @param Preference $preference
     *
     * @return Response
     */
    public function preferenceDeleteAction(Preference $preference)
    {
    	$resource = $preference->getAggregate();
    	$this->checkAccess('EDIT', $resource);
    	$this->preferenceManager->deletePreference($preference);
    
    	return new Response(204);
    }
    
    /**
     * @EXT\Route(
     *     "/preference/workspace/{workspaceId}/page/{page}",
     *     name="claro_workspace_preference_pager",
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
     * @EXT\Template("AipProfilageBundle::preferenceWorkspaceWidgetPager.html.twig")
     *
     * Renders preference in a pager.
     *
     * @return Response
     */
    public function preferenceWorkspaceWidgetPagerAction(AbstractWorkspace $workspace, $page)
    {
    	$token = $this->securityContext->getToken();
    	$roles = $this->utils->getRoles($token);
    	$datas = $this->preferenceManager->getVisiblePreferenceByWorkspace($workspace, $roles);
    	$pager = $this->pagerFactory->createPagerFromArray($datas, $page, 5);
    
    	return array(
    			'datas' => $pager,
    			'widgetType' => 'workspace',
    			'workspaceId' => $workspace->getId()
    	);
    }
    /**
     * @EXT\Route(
     *     "/preference/page/{page}",
     *     name="claro_desktop_preference_pager",
     *     defaults={"page"=1},
     *     options={"expose"=true}
     * )
     * @EXT\Method("GET")
     *
     * @EXT\Template("AipProfilageBundle::preferenceDesktopWidgetPager.html.twig")
     *
     * Renders preference in a pager.
     *
     * @return Response
     */
    public function preferenceDesktopWidgetPagerAction($page)
    {
    	$token = $this->securityContext->getToken();
    	$roles = $this->utils->getRoles($token);
    	$workspaces = $this->workspaceManager->getWorkspacesByRoles($roles);
    	$datas = $this->preferenceManager->getVisiblePreferenceByWorkspaces($workspaces, $roles);
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