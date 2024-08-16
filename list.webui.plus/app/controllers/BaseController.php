<?php
require_once(BASE_PATH.'/app/library/vendor/autoload.php');

use Phalcon\Mvc\Controller;

class BaseController extends Controller
{
	public $sandbox;
	public $filter;
	public $options;
	public $current_model;
	public $current_username;
	public $controller_name;
	public $action_name;

	public function initialize(){
		global $_G;
		if($this->session->has('me')){
			$_G['me']=$this->session->get('me');
		}else{
			$_G['me'] = array(
				'id'   => 0,
				'username' =>'',
				'email' => '',
				'apikey' => '',
				'is_admin' => 0,
				'is_member' => 0
			);
		}

		global $coll_sandbox;

		$this->sandbox = $coll_sandbox;
		$_G["page_size"] = 18;

		$this->filter=array("is_public"=>1);
		$this->options=array("limit"=>$_G["page_size"]);

		$this->controller_name = strtolower($this->dispatcher->getControllerName());
		$this->action_name = strtolower($this->dispatcher->getActionName());

		$this->view->controller_name = $this->controller_name;
		$this->view->action_name = $this->action_name;

		$this->view->body_class = $this->controller_name." ".$this->controller_name."-".$this->action_name;

		$_G["current_model"] = empty($_GET["m"])?"":$_GET["m"];
		$_G["current_username"] = empty($_GET["u"])?"":$_GET["u"];
		$_G["current_page"] = empty($_GET["p"])?1:$_GET["p"];
		$_G["current_uri"] =$this->request->getURI();


	}

	protected function userOnline(){

		global $_G;
		
		if($this->cookies->has("me")){
			$s_me = $this->cookies->get("me")->getValue();
			$user = unserialize($s_me);
			if(is_array($user)){
				$this->session->set(
					'me',
					[
						'id'   => $user->id,
						'username' => $user->username,
						'email' => $user->email,
						'apikey' => $user->apikey,
						'is_admin' => $user->is_admin,
						'is_member' => $user->is_member,
					]
				);
			}
		}   

		if(!$this->session->has('me')){
			$this->response->redirect('signin');
		}else{
			$_G['me']=$this->session->get('me');
		}

	}
}
