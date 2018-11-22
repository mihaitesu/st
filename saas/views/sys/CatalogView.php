<?php declare(strict_types=1);
/**
 * CatalogView.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Mar 2018
**/



class CatalogView extends BaseView {
	
	private $Template;
	
	public function __construct(array $view_params) {
		parent::__construct($view_params);
		
		$this->Template = new Template(SYS_PATH['tpl'].'catalog/', SYS['dev_mode'] ? false : SYS_PATH['tpl'].'catalog/cache/');
	}
	
	
	//--- array account cu variabilele comune din template-uri
	private function catalog_tpl_vars() {
		return array(
			'url_js_catalog' => SYS_URL['js'].'catalog.js'
		);
	}
	
	
	
	public function index() {
		if(empty($this->sub_domain_user_data)) {
			//--- admin main domain
		} else {
			//--- admin user sub-domain
		}
	}
	
	
	public function search() {
		$text = $this->get_text(array());
		$tpl_vars = array(
			'base'             => $this->base_tpl_vars(),
			'page_title'       => '',
			'page_description' => '',
			'page_content'     => 'service search'
		);
		echo $this->Template->render_file('service_domain_catalog_search.html', $tpl_vars);
	}
	
	
	
}
