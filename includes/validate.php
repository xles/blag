<?php
namespace MagicPotion;

class Validate extends Object {
/**
 * Validate an email address.
 * 
 * Provide email address (raw input)
 * Returns true if the email address has the email 
 * address format and the domain exists.
 * 
 * @author	Douglas Lovell <email>
 * @copyright	Copyright (c) Douglas Lovell 2007
 */
	private function email($email)
	{
		$atIndex = strrpos($email, "@");
		if (is_bool($atIndex) &&!$atIndex) {
			return FALSE;
		} else {
			$domain = substr($email, $atIndex+1);
			$local = substr($email, 0, $atIndex);
			$localLen = strlen($local);
			$domainLen = strlen($domain);
			if ($localLen < 1 || $localLen > 64) {
				return FALSE;
			} else if ($domainLen < 1 || $domainLen > 255) {
				return FALSE;
			} else if ($local[0] == '.' ||
			 $local[$localLen-1] == '.') {
				return FALSE;
			} else if (preg_match('/\\.\\./', $local)) {
				return FALSE;
			} else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/',
			 $domain)) {
				return FALSE;
			} else if (preg_match('/\\.\\./', $domain)) {
				return FALSE;
			} else if(!preg_match(
			 '/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
			 str_replace("\\\\", "", $local))) {
				if (!preg_match('/^"(\\\\"|[^"])+"$/',
				 str_replace("\\\\", "", $local))) {
					return FALSE;
				}
			}
			if (!(checkdnsrr($domain, "MX") ||
			 checkdnsrr($domain,"A"))) {
				return FALSE;
			}
		}
		return TRUE;
	}
}