<?php
namespace LeadingSystems\Api;

use Contao\System;

class ls_api_authHelper
{
	public static function authenticate($arr_allowedAuthTypes = ['apiUser'])
	{
		if (!self::checkApiKey()) {
			return false;
		}

		if (in_array('apiUser', $arr_allowedAuthTypes) && self::authenticateApiUser()) {
			return true;
		} else if (in_array('feUser', $arr_allowedAuthTypes) && self::authenticateFrontendUser()) {
			return true;
		} else if (System::getContainer()->get('merconis.routing.scope_matcher')->isBackend() && in_array('beUser', $arr_allowedAuthTypes) && self::authenticateBackendUser()) {
			return true;
		}

		return false;
	}

	protected static function authenticateFrontendUser()
	{
		if (\System::getContainer()->get('contao.security.token_checker')->hasFrontendUser()) {
			return true;
		}

		return false;
	}

	protected static function authenticateBackendUser()
	{
		/*
		 * If this code is being executed, we have a backend call and
		 * a backend user must be logged in because otherwise we already
		 * would have been redirected to the backend login screen.
		 *
		 * Therefore, unless we actually check specific access rights
		 * to specific API resources we can simply return true here.
		 */
		return true;
	}

	protected static function authenticateApiUser()
	{
		$str_username = \Input::post('ls_api_username');
		$str_password = \Input::post('ls_api_password');

		if (!$str_username || !$str_password) {
			return false;
		}

		$obj_apiUser = \Database::getInstance()
			->prepare("
					SELECT		`password`
					FROM		`tl_ls_api_user`
					WHERE		`username` = ?
				")
			->limit(1)
			->execute(
				$str_username
			);

		if (!$obj_apiUser->numRows) {
			return false;
		}

		if (
			defined('PASSWORD_ARGON2I')
			&& (false === strpos($obj_apiUser->password, '$argon2id$'))
		) {
			return password_verify($str_password, $obj_apiUser->password);
		}

		if (function_exists('sodium_crypto_pwhash_str_verify')) {
			$bln_isValid = sodium_crypto_pwhash_str_verify($obj_apiUser->password, $str_password);
			sodium_memzero($str_password);

			return $bln_isValid;
		}

		if (extension_loaded('libsodium')) {
			$bln_isValid = \Sodium\crypto_pwhash_str_verify($obj_apiUser->password, $str_password);
			\Sodium\memzero($str_password);

			return $bln_isValid;
		}

		throw new \Exception('Password could not be verified. Please install the libsodium extension.');
	}

	protected static function checkApiKey()
	{
		$str_apiKey = \Input::post('ls_api_key');
		if (!$str_apiKey) {
			$str_apiKey = \Input::get('ls_api_key');
		}

		if ($str_apiKey && $str_apiKey === self::getApiKey()) {
			return true;
		}

		return false;
	}

	/*
	 * In this function we check for a valid API key and if there is one,
	 * we deactivate the referer check. If there is none, we don't do anything.
	 */
	public static function bypassRefererCheckWithValidApiKey()
	{
		if (self::checkApiKey()) {
			\Config::set('disableRefererCheck', true);
		}
	}

	protected static function getApiKey()
	{
		return $GLOBALS['TL_CONFIG']['ls_api_key'];
	}
}
