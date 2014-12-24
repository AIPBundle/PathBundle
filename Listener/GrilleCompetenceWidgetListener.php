<?php

namespace Aip\ProfilageBundle\Listener;

use Claroline\CoreBundle\Event\DisplayWidgetEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service()
 */
class GrilleCompetenceWidgetListener
{
    private $request;
    private $httpKernel;

    /**
     * @DI\InjectParams({
     *     "requestStack"   = @DI\Inject("request_stack"),
     *     "httpKernel" = @DI\Inject("http_kernel"),
     * })
     */
    public function __construct(
        RequestStack $requestStack,
        HttpKernelInterface $httpKernel
    )
    {
       $this->request = $requestStack->getCurrentRequest();
        $this->httpKernel = $httpKernel;
    }

    /**
     * @DI\Observe("widget_aip_grillecompetence_widget")
     *
     * @param DisplayWidgetEvent $event
     */
    public function onDisplay(DisplayWidgetEvent $event)
    {
    	if (!$this->request) {
    		throw new NoHttpRequestException();
    	}
    	$widgetInstance = $event->getInstance();
        $workspace = $widgetInstance->getWorkspace();
        $params = array();
        $params['page'] = 1;

        if (is_null($workspace)) {
            $params['_controller'] = 'AipProfilageBundle:GrilleCompetence:grillecompetenceDesktopWidgetPager';
        }
        else {
            $params['_controller'] = 'AipProfilageBundle:GrilleCompetence:grillecompetenceWorkspaceWidgetPager';
            $params['workspaceId'] = $workspace->getId();
        }

        $subRequest = $this->request->duplicate(
            array(),
            null,
            $params
        );
        $response = $this->httpKernel->handle($subRequest, HttpKernelInterface::SUB_REQUEST);

        $event->setContent($response->getContent());
        $event->stopPropagation();
    }
}