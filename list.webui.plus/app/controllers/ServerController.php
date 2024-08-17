<?php
use Phalcon\Mvc\Controller;
class ServerController extends BaseController
{
	

	public function initialize(){
		
	}

	public function indexAction()
	{
		$this->view->disable();
		print_r($this->getServerStatus());
	}
}
