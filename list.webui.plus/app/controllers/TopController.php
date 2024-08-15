<?php
use Phalcon\Mvc\Controller;
class TopController extends Controller
{
	public $sandbox;
	public $filter;
	public $options;
	public $me;
	public $current_model;
	public $current_username;
	public $controller_name;
	public $action_name;

	public function initialize(){
		global $_G;

		global $coll_sandbox;

		$this->sandbox = $coll_sandbox;
		$_G["page_size"] = 48;

		$this->filter=array("is_public"=>1);
		$this->options=array("limit"=>$_G["page_size"]);

		$this->controller_name = strtolower($this->dispatcher->getControllerName());
		$this->action_name = strtolower($this->dispatcher->getActionName());

		$this->view->controller_name = $this->controller_name;
		$this->view->action_name = $this->action_name;

		$this->view->body_class = $controller_name." ".$controller_name."-".$action_name;

		$_G["current_model"] = empty($_GET["m"])?"":$_GET["m"];
		$_G["current_username"] = empty($_GET["u"])?"":$_GET["u"];
		$_G["current_page"] = empty($_GET["p"])?1:$_GET["p"];
		$_G["current_uri"] =$this->request->getURI();
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
