<?php

class IndexController extends BaseController
{
	public function initialize(){
		parent::initialize();
		
		
	}

	public function indexAction()
	{
		//$this->view->disable();
		$this->jsonOut();
	}
}
