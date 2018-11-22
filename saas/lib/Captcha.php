<?php declare(strict_types=1);
/**
 * Captcha.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class Captcha {
	
	private $code = null;
	
	public function __construct() {}
	
	private function set_code() {
		$this->code = rkeysa(rand(3, 5));
		$_SESSION['CAPTCHA_CODE_MD5_ASD'] = md5(strtolower($this->code));
	}
	
	public function unset_code() {
		unset($_SESSION['CAPTCHA_CODE_MD5_ASD']);
	}
	
	public function show_jpg() {
		$this->set_code();
		
		$im = imagecreate(180, 60);
		$bg = imagecolorallocate($im, 255, 255, 255);
		$textcolor = imagecolorallocate($im, 120, 120, 120);
		
		$fonts = array();
		$fonts += glob(__DIR__.'/captcha_fonts/'."*.ttf"); // toate fonturile din folder
		
		// array de caractere
		$arr_cod = str_split($this->code);
		
		$poz = 25;
		foreach($arr_cod as $key => $chr) {
			$angle = rand(-60,60);
			imagettftext($im, rand(15,25), $angle, $poz, 40, $textcolor, $fonts[rand(0,count($fonts)-1)], $chr);
			$poz += 25;
		}
		
		imagejpeg($im, null, 90);
		imagedestroy($im);
	}
	
	public function show_png() {
		$this->set_code();
		
		$im = imagecreatetruecolor(180, 60);
		imagesavealpha($im, true);
		$trans_colour = imagecolorallocatealpha($im, 255, 255, 255, 127); //127 - 100% transparent
		imagefill($im, 0, 0, $trans_colour);
		$textcolor = imagecolorallocate($im, 120, 120, 120);
		
		$fonts = array();
		// toate fonturile din folder
		$fonts += glob(__DIR__.'/captcha_fonts/'."*.ttf");
		
		// array de caractere
		$arr_cod = str_split($this->code);
		
		$poz = 25;
		foreach($arr_cod as $key => $chr) {
			$angle = rand(-60,60);
			imagettftext($im, rand(15,25), $angle, $poz, 40, $textcolor, $fonts[rand(0,count($fonts)-1)], $chr);
			$poz += 25;
		}
		
		imagepng($im, null, 6, PNG_NO_FILTER); //1-9 nivel compresie
		imagedestroy($im);
	}
	
	public function valid_code(string $captcha_code_user) {
		$captcha_code_md5 = isset($_SESSION['CAPTCHA_CODE_MD5_ASD']) ? $_SESSION['CAPTCHA_CODE_MD5_ASD'] : null;
		return ($captcha_code_md5 && $captcha_code_user && (md5(strtolower($captcha_code_user)) === $captcha_code_md5)) ? true : false;
	}
	
	
}
