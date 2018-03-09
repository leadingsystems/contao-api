<?php

namespace LeadingSystems\Api;

if (TL_MODE == 'BE') {
	$GLOBALS['TL_CSS'][] = 'bundles/leadingsystemsapi/be/css/style.css';
}

array_insert($GLOBALS['BE_MOD'], 0, array(
	'ls_api' => array(
		'ls_api_user' => array(
			'tables' => array('tl_ls_api_user')
		),

		'be_mod_ls_apiReceiver' => array(
			'callback' => 'LeadingSystems\Api\be_mod_ls_apiReceiver'
		)
	)
));

$GLOBALS['FE_MOD']['ls_api'] = array(
	'ls_apiReceiver' => 'LeadingSystems\Api\mod_ls_apiReceiver'
);

$GLOBALS['LS_API_HOOKS']['apiReceiver_processRequest'][] = array('LeadingSystems\Api\ls_apiResourceControllerStandard', 'processRequest');

$GLOBALS['LS_API_HOOKS']['apiReceiver_processRequest'][] = array('LeadingSystems\Api\ls_apiResourceControllerStandardBackend', 'processRequest');

$GLOBALS['LS_API_HOOKS']['apiReceiver_processRequest'][] = array('LeadingSystems\Api\ls_apiResourceControllerStandardFrontend', 'processRequest');