<?php

class IndexController extends BaseController
{
	public function initialize(){
		parent::initialize();
		
		
	}

	public function indexAction()
	{
		$this->message["error"]="errrr";
		$this->message["data"]=array();
		$this->jsonOut();
	}
}
