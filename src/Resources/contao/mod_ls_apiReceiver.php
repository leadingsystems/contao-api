<?php

namespace LeadingSystems\Api;

use Contao\System;

class mod_ls_apiReceiver extends \Module {
	public function generate()
    {
        $request = System::getContainer()->get('request_stack')->getCurrentRequest();

		if ($request && System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest($request))
        {
			$objTemplate = new \BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### LS API RECEIVER (FRONTEND) ###';
			return $objTemplate->parse();
		}
		return parent::generate();
	}
	
	public function compile() {
		$obj_apiController = new ls_apiController();
		$obj_apiController->run();
	}
}