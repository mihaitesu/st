<?php declare(strict_types=1);
/**
 * ImgBlob.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class ImgBlob {
	
	const MAX_WIDTH_TH   = 2000;
	const MAX_HEIGHT_TH  = 2000;
	const DEFAULT_RATIO  = 'aspect';
	const DEFAULT_FORMAT = 'png';
	
	private $imgblob;
	private $width;
	private $height;
	private $ratio;
	private $format;
	private $return_blob;
	
	public function __construct(string $imgblob, int $width, int $height, string $ratio=null, string $format=null, bool $return_blob=false) {
		$this->imgblob = $imgblob;
		$this->width   = ($width>0 && $width<=self::MAX_WIDTH_TH)    ? $width  : self::MAX_WIDTH_TH;
		$this->height  = ($height>0 && $height<=self::MAX_HEIGHT_TH) ? $height : self::MAX_HEIGHT_TH;
		$this->ratio   = ($ratio==='aspect' || $ratio==='fixed')     ? $ratio  : self::DEFAULT_RATIO;
		$this->format  = ($format==='png' || $format==='jpg')        ? $format : self::DEFAULT_FORMAT;
		
		$this->return_blob = $return_blob ? true : false;
	}
	
	
	//---
	public function show() {
		if(!$this->imgblob) { return $this->show_no_image(); }
		switch($this->ratio) {
			case 'aspect': return $this->show_aspect_ratio(); break;
			case 'fixed' : return $this->show_fixed_ratio();  break;
			default: return $this->show_no_image();
		}
	}
	
	
	//--- no-image
	private function show_no_image() {
		$thumb = imagecreatetruecolor($this->width, $this->height);
		imageinterlace($thumb, true); //--- progressive
		if($this->format==='jpg') {
			$background_colour = imagecolorallocate($thumb, 255, 255, 255);
			$line_colour       = imagecolorallocate($thumb, 200, 200, 200);
			imagefill($thumb, 0, 0, $background_colour);
			imageline($thumb, 0, 0, $this->width, $this->height, $line_colour);
			imageline($thumb, $this->width, 0, 0, $this->height, $line_colour);
			imagejpeg($thumb, null, 100);
		}
		if($this->format==='png') {
			imagesavealpha($thumb, true);
			$background_colour = imagecolorallocatealpha($thumb, 255, 255, 255, 127); //127 - 100% transparent
			$line_colour       = imagecolorallocate($thumb, 200, 200, 200);
			imagefill($thumb, 0, 0, $background_colour);
			imageline($thumb, 0, 0, $this->width, $this->height, $line_colour);
			imageline($thumb, $this->width, 0, 0, $this->height, $line_colour);
			imagepng($thumb, null, 6, PNG_NO_FILTER); //1-9 nivel compresie
		}
		imagedestroy($thumb);
		return null;
	}
	
	
	//--- aspect ratio
	private function show_aspect_ratio() {
		$imblob = imagecreatefromstring($this->imgblob);
		if($imblob===false) return $this->show_no_image();
		
		$orig_x = imagesx($imblob);
		$orig_y = imagesy($imblob);
		
		$s  = $orig_x < $orig_y ? $orig_x : $orig_y;
		if(($orig_x > $this->width) || ($orig_y > $this->height)) {
			if($orig_y > $orig_x) {
				$image_y=$this->height;
				$image_x = round(($orig_x * $image_y) / $orig_y);
				if($image_x > $this->width) {
					$image_x = $this->width;
					$image_y = round(($orig_y * $image_x) / $orig_x);
				}
			} else { //w>=h
				$image_x=$this->width;
				$image_y = round(($orig_y * $image_x) / $orig_x);
				if($image_y > $this->height) {
					$image_y=$this->height;
					$image_x = round(($orig_x * $image_y) / $orig_y);
				}
			}
		} else {
			$image_y=$orig_y;
			$image_x=$orig_x;
		}
		if($image_x <1){ $image_x=1; }
		if($image_y <1){ $image_y=1; }
		
		$thumb = imagecreatetruecolor($image_x, $image_y);
		imageinterlace($thumb, true); //--- progressive
		if($this->format==='jpg') {
			$background_colour = imagecolorallocate($thumb, 255, 255, 255);
			imagefill($thumb, 0, 0, $background_colour);
			imagecopyresampled($thumb, $imblob, 0, 0, 0, 0, $image_x, $image_y, $orig_x, $orig_y);
			if($this->return_blob) { ob_start(); }
			imagejpeg($thumb, null, 100);
		}
		if($this->format==='png') {
			imagesavealpha($thumb, true);
			$background_colour = imagecolorallocatealpha($thumb, 255, 255, 255, 127); //127 - 100% transparent
			imagefill($thumb, 0, 0, $background_colour);
			imagecopyresampled($thumb, $imblob, 0, 0, 0, 0, $image_x, $image_y, $orig_x, $orig_y);
			if($this->return_blob) { ob_start(); }
			imagepng($thumb, null, 6, PNG_NO_FILTER); //1-9 nivel compresie
		}
		imagedestroy($imblob);
		imagedestroy($thumb);
		if($this->return_blob) {
			$blob_content = ob_get_contents();
			ob_end_clean();
			return $blob_content;
		}
		return null;
	}
	
	
	//--- cropped
	private function show_fixed_ratio() {
		$imblob = imagecreatefromstring($this->imgblob);
		if($imblob===false) return $this->show_no_image();
		
		$orig_x = imagesx($imblob);
		$orig_y = imagesy($imblob);
		
		$sw = $orig_x / $this->width;
		$sh = $orig_y / $this->height;
		$s  = $sw < $sh ? $sw : $sh;
		
		$x0 = round( ($orig_x-$this->width*$s)*0.5 );
		$y0 = round( ($orig_y-$this->height*$s)*0.5 );
		
		$thumb = imagecreatetruecolor($this->width, $this->height);
		imageinterlace($thumb, true); //--- progressive
		if($this->format==='jpg') {
			$background_colour = imagecolorallocate($thumb, 255, 255, 255);
			imagefill($thumb, 0, 0, $background_colour);
			imagecopyresampled($thumb, $imblob, 0, 0, $x0, $y0, $this->width, $this->height, ($this->width * $s), ($this->height * $s));
			if($this->return_blob) { ob_start(); }
			imagejpeg($thumb, null, 100);
		}
		if($this->format==='png') {
			imagesavealpha($thumb, true);
			$background_colour = imagecolorallocatealpha($thumb, 255, 255, 255, 127); //127 - 100% transparent
			imagefill($thumb, 0, 0, $background_colour);
			imagecopyresampled($thumb, $imblob, 0, 0, $x0, $y0, $this->width, $this->height, ($this->width * $s), ($this->height * $s));
			if($this->return_blob) { ob_start(); }
			imagepng($thumb, null, 6, PNG_NO_FILTER); //1-9 nivel compresie
		}
		imagedestroy($imblob);
		imagedestroy($thumb);
		if($this->return_blob) {
			$blob_content = ob_get_contents();
			ob_end_clean();
			return $blob_content;
		}
		return null;
	}
	
}
