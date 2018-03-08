<?php

namespace LeadingSystems\Api;

$GLOBALS['TL_HOOKS']['getUserNavigation'][] = array('LeadingSystems\Api\ls_apiController', 'manipulateBackendNavigation');

$GLOBALS['FE_MOD']['ls_api'] = array(
	'ls_apiReceiver' => 'LeadingSystems\Api\mod_ls_apiReceiver'
);

$GLOBALS['BE_MOD']['ls_api']['be_mod_ls_apiReceiver'] = array(
	'callback' => 'LeadingSystems\Api\be_mod_ls_apiReceiver'
);

$GLOBALS['LS_API_HOOKS']['apiReceiver_processRequest'][] = array('LeadingSystems\Api\ls_apiResourceControllerStandard', 'processRequest');

$GLOBALS['LS_API_HOOKS']['apiReceiver_processRequest'][] = array('LeadingSystems\Api\ls_apiResourceControllerStandardBackend', 'processRequest');

$GLOBALS['LS_API_HOOKS']['apiReceiver_processRequest'][] = array('LeadingSystems\Api\ls_apiResourceControllerStandardFrontend', 'processRequest');