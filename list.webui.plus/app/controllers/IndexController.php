<?php

class IndexController extends BaseController
{
	public function initialize(){
		parent::initialize();
		global $_G;
		if(!empty($_G['me']['is_admin']) && $_G['me']['is_admin'] == 1){
			$this->sandbox->filter=array();
		}
		
	}

	public function indexAction()
	{
		global $_G;
		
		if(!empty($_G["current_model"])){
			$this->sandbox->addFilter(array('model'=>$_G["current_model"]));
		}
		if(!empty($_G["current_username"])){
			$this->sandbox->addFilter(array('username'=>$_G["current_username"]));
		}

		if(!empty($_G["current_page"])){
			$this->sandbox->addOptions(array('skip'=>($_G["current_page"]-1) * $_G["page_size"],'limit'=>$_G["page_size"]));
		}

		
		$photos = $this->sandbox->find();
		


		$this->view->filter = $this->sandbox->filter;
		$this->view->options = $this->sandbox->options;
		$this->view->photos = $photos;
	}
}
