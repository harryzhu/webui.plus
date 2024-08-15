<?php
use Phalcon\Mvc\View;

class MyController extends BaseController
{

	public function initialize(){
		parent::initialize();
		$this->userOnline();
		$this->view->setRenderLevel(
			View::LEVEL_LAYOUT
		);

		$this->controller_name = "my";
		$this->view->controller_name = $this->controller_name;

		global $_G;
		if(empty($_G["me"]['username'])){
			$this->view->disable();
			echo 'login first.';
		}
		
		$this->sandbox->addFilter(array('username'=>$_G["me"]['username']));


	}

	public function indexAction()
	{
		global $_G;
		
		if(!empty($_G["current_model"])){
			$this->sandbox->addFilter(array('model'=>$_G["current_model"]));
		}


		if(!empty($_G["current_page"])){
			$this->sandbox->addOptions(array('skip'=>($_G["current_page"]-1) * $_G["page_size"],'limit'=>$_G["page_size"]));
		}

		
		$photos = $this->sandbox->find();
		

		$this->view->filter = $this->sandbox->filter;
		$this->view->options = $this->sandbox->options;
		$this->view->photos = $photos;
	}

	public function profileAction()
	{
		global $_G;

		$user = Users::findFirst('username = "'.$_G['me']['username'].'"');
		if($user){
			$t = array(
				"username"=>$user->username,
				"apikey"=>$user->apikey,
				"email"=>$user->email,
				"is_member"=>$user->is_member,
			);
			$this->view->user = $t;
		}
	}

	public function drawAction()
	{


	}

	public function drawsaveAction()
	{
		$this->view->disable();
		if($this->request->isPost()){
			$posts = $this->request->getPost();
			global $_G;
			if(empty($posts['frmmodel']) ||empty($posts['frmprompt']) ){
				echo "model and prompt cannot be empty.";
				return false;
			}

			$arr_task = array(
				'model'=>$posts['frmmodel'],
				'prompt'=>trim($posts['frmprompt']),
				'negative_prompt'=>"{{nsfw}},".trim($posts['frmnegativeprompt']),
				'sampler'=>'DPM++ SDE Karras',
				'steps'=>30,
				'cfg_scale'=>9,
				'width'=>1024,
				'height'=>1024,
				'status'=>1,
				'username'=>$_G['me']['username'],
			);

			$tasktxt2img = new MongoWebUI("tasktxt2img");
			

			$oid = $tasktxt2img->insertOne($arr_task);

			$this->response->redirect("/my/tasks");
		}

	}


	public function tasksAction()
	{
		global $_G;
		if(empty($_G['me']['username'])){
			return false;
		}

		$tasktxt2img = new MongoWebUI("tasktxt2img");
		$tasktxt2img->filter=array('username'=>$_G['me']['username']);
		$tasks = $tasktxt2img->find();
		$this->view->tasks = $tasks;
	}
}
