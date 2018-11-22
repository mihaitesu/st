<?php declare(strict_types=1);
/**
 * helpers.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



//--- genereaza un cod
function rkeysa(int $length) {
	$characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
	$exclude    = array('I','i','L','l','O','o','0','1'); // caractere ambigue pt cod captcha
	$charset    = array_values(array_diff($characters, $exclude)); // exclud caracterele in plus si refac indecsii
	$key = '';
	for($i=0;$i<$length;$i++) {
		$key .= $charset[rand(0,count($charset)-1)];
	}
	return $key;
}

//--- verifica request ajax
function is_ajax_call() {
	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		return true;
	}
	return false;
}

//--- returneaza ip vizitator
function get_ip() {
	if(getenv('HTTP_CLIENT_IP')) {
		$ip = getenv('HTTP_CLIENT_IP');
	} elseif(getenv('HTTP_X_FORWARDED_FOR')) {
		$ip = getenv('HTTP_X_FORWARDED_FOR');
	} elseif(getenv('HTTP_X_FORWARDED')) {
		$ip = getenv('HTTP_X_FORWARDED');
	} elseif(getenv('HTTP_FORWARDED_FOR')) {
		$ip = getenv('HTTP_FORWARDED_FOR');
	} elseif(getenv('HTTP_FORWARDED')) {
		$ip = getenv('HTTP_FORWARDED');
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return mb_substr($ip, 0, 255, 'UTF-8'); //maxim 255 caractere
}

//--- valideaza ip
function valid_ip(string $ip) {
	return inet_pton($ip) !== false;
}

//--- returneaza user agent sau null
function user_agent() {
	$ua = (isset($_SERVER['HTTP_USER_AGENT']) && $_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
	return mb_substr($ua, 0, 255, 'UTF-8'); //maxim 255 caractere
}

//--- detecteaza roboti
function is_bot() {
	if(!empty($_SERVER['HTTP_USER_AGENT']) and preg_match('~(bot|crawl)~i', $_SERVER['HTTP_USER_AGENT'])) {
		return true;
	}
	return false;
}

//--- valideaza format email si domeniu
function valid_format_email(string $email) {
	$isValid = true;
	$atIndex = strrpos($email, "@");
	if(is_bool($atIndex) && !$atIndex) {
		$isValid = false;
	} else {
		$domain = substr($email, $atIndex+1);
		$local = substr($email, 0, $atIndex);
		$localLen = strlen($local);
		$domainLen = strlen($domain);
		if($localLen < 1 || $localLen > 64) {
			// local part length exceeded
			$isValid = false;
		} elseif($domainLen < 1 || $domainLen > 255) {
			// domain part length exceeded
			$isValid = false;
		} elseif($local[0] == '.' || $local[$localLen-1] == '.') {
			// local part starts or ends with '.'
			$isValid = false;
		} elseif(preg_match('/\\.\\./', $local)) {
			// local part has two consecutive dots
			$isValid = false;
		} elseif(!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
			// character not valid in domain part
			$isValid = false;
		} elseif(preg_match('/\\.\\./', $domain)) {
			// domain part has two consecutive dots
			$isValid = false;
		} elseif(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\","",$local))) {
			// character not valid in local part unless local part is quoted
			if(!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\","",$local))) {
				$isValid = false;
			}
		}
		if($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A"))) {
			// domain not found in DNS
			$isValid = false;
		}
	}
	return $isValid;
}

//--- valideaza format url
function valid_format_url(string $url) {
	if(strlen($url) && preg_match('/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i', $url)) {
		return true;
	}
	return false;
}

//--- valideaza host - format corect / activ (ip check)
function valid_active_host(string $host) {
	return (filter_var(gethostbyname($host), FILTER_VALIDATE_IP)) ? true : false;
}







//--- string uppercase
function set_uppercase_string(string $str) {
	return mb_strtoupper($str, "UTF-8");
}

//--- string lowercase
function set_lowercase_string(string $str) {
	return mb_strtolower($str, "UTF-8");
}







/*
//--- escape string - HTML
function safe_display_string($str) {
	return htmlentities($str, ENT_QUOTES, 'UTF-8');
}

//--- escape string - HTML (new lines)
function nl2br_safe_display_string($str) {
	return nl2br(safe_display_string($str));
}

//--- sterge caracterele linie noua din string
function remove_newlines($str) {
	return str_replace(PHP_EOL, '', $str);
}

//--- normalizare string (spatiile albe succesive)
function normalize($str) {
	return preg_replace( '/\s+/', ' ', $str);
}

//--- caractere speciale => spatii
function specialch_space($str) {
	return preg_replace('/[^\p{L}\p{N}]/u', ' ', $str);
}

//--- caractere specificate => spatii
//--- ex: $chrs = array("~", "`", "!", "%", "[", "]", "{", "}", ":", ";", "\"", "\\", "|", "<", ">", ",", "?", "/");
function ch_space($str, $chrs) {
	return str_replace($chrs, ' ', $str);
}

//--- string optimizat pentru taguri
function set_tag_string($str) { //inlocuire caractere de legatura cu spatii, normalizare, case
	return mb_strtolower(trim(normalize(specialch_space($str))), "UTF-8");
}
*/

