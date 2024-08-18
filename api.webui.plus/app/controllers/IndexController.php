<?php

class IndexController extends BaseController
{
	public function initialize(){
		parent::initialize();
		
		
	}

	public function indexAction()
	{
		$this->data["error"]="errrr";
		$this->data["data"]=array();
		$this->jsonOut();
	}
}
