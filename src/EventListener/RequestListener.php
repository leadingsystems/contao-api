<?php

namespace LeadingSystems\ApiBundle\EventListener;

use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\CoreBundle\Routing\ScopeMatcher;
use LeadingSystems\Api\ls_api_authHelper;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class RequestListener
{
    private ContaoFramework $framework;
    private ScopeMatcher $scopeMatcher;

    public function __construct(ContaoFramework $framework, ScopeMatcher $scopeMatcher)
    {

        $this->framework = $framework;
        $this->scopeMatcher = $scopeMatcher;
    }

    public function __invoke(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();

        if ($this->scopeMatcher->isContaoRequest($request)) {
            $this->framework->initialize();

            if (ls_api_authHelper::checkApiKey()) {
                $request->attributes->set('_token_check', false);
            }
        }
    }

}