<?php declare(strict_types=1);
/**
 * Template.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



function autoload_twig($class) {
	$prefix   = 'Twig';
	$base_dir = __DIR__ . '/Twig/';
	$len      = strlen($prefix);
	if(strncmp($prefix, $class, $len) !== 0) { return; }
	$relative_class = substr($class, $len);
	$file = $base_dir.str_replace('_', '/', $relative_class).'.php';
	if (is_readable($file)) { require_once $file; }
}
spl_autoload_register("autoload_twig");



class Template {
	
	private $Twig = null;
	
	public function __construct(string $template_path, $cache_path=false) {
		$this->Twig = new Twig_Environment(new Twig_Loader_Filesystem($template_path), array('cache'=>$cache_path));
	}
	
	
	public function render_file(string $template_file, array $template_vars=array()) {
		$template = $this->Twig->loadTemplate($template_file);
		return $template->render($template_vars);
	}
	
	
	public function render_string(string $template_string, array $template_vars=array()) {
		$template = $this->Twig->createTemplate($template_string);
		return $template->render($template_vars);
	}
	
}
