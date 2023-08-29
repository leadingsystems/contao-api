<?php

namespace LeadingSystems\Api;

use Contao\System;

class mod_ls_apiReceiver extends \Module {
	public function generate() {
		if (System::getContainer()->get('merconis.routing.scope_matcher')->isBackend()) {
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