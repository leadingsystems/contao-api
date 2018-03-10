<?php

namespace LeadingSystems\Api;

$GLOBALS['TL_DCA']['tl_ls_api_settings'] = array(
	'config' => array(
		'dataContainer' => 'File',
		'closed' => true
	),

	'palettes' => array(
		'default' => 'ls_api_key'
	),

	'fields' => array(
		'ls_api_key' => array(
			'exclude' => true,
			'label' => &$GLOBALS['TL_LANG']['tl_ls_api_settings']['ls_api_key'],
			'inputType' => 'text',
			'eval' => array('tl_class' => 'clr', 'rgxp' => 'ls_api_key'),
			'save_callback' => array(
				array('LeadingSystems\Api\tl_ls_api_settings', 'createApiKey')
			)
		)
	)
);

class tl_ls_api_settings extends \Backend
{
	public function __construct()
	{
		parent::__construct();
	}

	public function createApiKey($str_value)
	{
		/*
		 * If an API key is already stored and the value currently entered in the field is either
		 * the same as the stored value or it is empty, we simply keep the current API key.
		 */
		if (
			$GLOBALS['TL_CONFIG']['ls_api_key']
			&&
			(
				!$str_value
				|| $str_value === $GLOBALS['TL_CONFIG']['ls_api_key']
			)
		) {
			return $GLOBALS['TL_CONFIG']['ls_api_key'];
		}

		$str_value = password_hash($str_value, PASSWORD_BCRYPT);
		$str_value = substr($str_value, 0, 10).time().substr($str_value, 10, strlen($str_value));
		return $str_value;
	}
}
