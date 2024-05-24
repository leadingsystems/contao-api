<?php

namespace LeadingSystems\Api;

use Contao\BackendModule;

class be_mod_ls_apiReceiver extends BackendModule {
	public function compile()
    {
		$obj_apiController = new ls_apiController();
		$obj_apiController->run();
	}
}