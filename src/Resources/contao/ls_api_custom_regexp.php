<?php

namespace LeadingSystems\Api;

class ls_api_custom_regexp
{
	public function customRegexp($str_regexp, &$var_value, \Widget $obj_widget) {
		switch ($str_regexp) {
			case 'ls_api_key':
				/*
				 * FIXME: This is just a test to find out why we can't get the language value
				 */
				$obj_widget->addError(sprintf($GLOBALS['TL_LANG']['MOD']['ls_api']['rgxpErrorMessages']['ls_api_key']['colonNotAllowedInPassword'], $obj_widget->label));
				return false;
				/*
				 * If no API key is set yet, we don't have to check a password. We only make sure that the password
				 * entered now doesn't contain a colon character.
				 */
				if (!$GLOBALS['TL_CONFIG']['ls_api_key']) {
					if (strpos($var_value, ':') !== false) {
						$obj_widget->addError($GLOBALS['TL_LANG']['MOD']['ls_api']['rgxpErrorMessages']['ls_api_key']['colonNotAllowedInPassword']);
						return false;
					}
				}

				return true;
				break;
		}
		return false;
	}
}