<?php
//require BASE_PATH.'/app/library/vendor/autoload.php';

class AdminController extends BaseController
{

	public function initialize(){
		parent::initialize();
		global $_G;
		if(empty($_G['me']['is_admin']) || $_G['me']['is_admin']!=1){
			die('only administrator can run this.');
		}

		
	}

	public function indexAction()
	{
		
		$this->options['sort'] = array('ts_upload' => -1);
		$this->filter=array("is_public"=>0);

		$photos = $this->sandbox->find($this->filter,$this->options);

		$this->view->photos = $photos;
	}

	public function apikeyAction($user="")
	{
		$this->view->disable();
		if(empty($user)){
			echo "";
			return False;
		}
		echo getAPIKey($user);
	}

	public function deleteAction($model="",$username="",$oid="")
	{
		if(empty($model) || empty($username) || empty($oid)){
			return false;
		}
		deleteOneByModelUserId($model,$username,$oid);

		$this->response->redirect('/');

		$this->view->disable();
	}

	public function publishAction($oid="")
	{
		if(empty($oid)){
			return False;
		}

		$publishResult = $this->sandbox->updateById($oid,array('is_public'=>1));
		
		printf($publishResult);

		$detail_url = "/photos/detail/".$oid;
		print('Test: <a href="'.$detail_url.'">'.$detail_url.'</a>');

		$this->response->redirect($detail_url);

		$this->view->disable();
	}

	public function unpublishAction($oid="")
	{
		if(empty($oid)){
			return False;
		}

		$publishResult = $this->sandbox->updateById($oid,array('is_public'=>0));
		
		printf($publishResult);

		$detail_url = "/photos/detail/".$oid;
		print('Test: <a href="'.$detail_url.'">'.$detail_url.'</a>');


		$this->response->redirect($detail_url);
		$this->view->disable();
	}

	public function memberAction($oid="")
	{
		if(empty($oid)){
			return False;
		}

		$publishResult = $this->sandbox->updateById($oid,array('is_member'=>1));
		
		printf($publishResult);

		$detail_url = "/photos/detail/".$oid;
		print('Test: <a href="'.$detail_url.'">'.$detail_url.'</a>');


		$this->response->redirect($detail_url);
		$this->view->disable();
	}


	public function unmemberAction($oid="")
	{
		if(empty($oid)){
			return False;
		}

		$publishResult = $this->sandbox->updateById($oid,array('is_member'=>0));
		
		printf($publishResult);

		$detail_url = "/photos/detail/".$oid;
		print('Test: <a href="'.$detail_url.'">'.$detail_url.'</a>');


		$this->response->redirect($detail_url);
		$this->view->disable();
	}

	public function privateAction($oid="")
	{
		if(empty($oid)){
			return False;
		}

		$publishResult = $this->sandbox->updateById($oid,array('is_private'=>1));
		
		printf($publishResult);

		$detail_url = "/photos/detail/".$oid;
		print('Test: <a href="'.$detail_url.'">'.$detail_url.'</a>');


		$this->response->redirect($detail_url);
		$this->view->disable();
	}


	public function unprivateAction($oid="")
	{
		if(empty($oid)){
			return False;
		}

		$publishResult = $this->sandbox->updateById($oid,array('is_private'=>0));
		
		printf($publishResult);

		$detail_url = "/photos/detail/".$oid;
		print('Test: <a href="'.$detail_url.'">'.$detail_url.'</a>');


		$this->response->redirect($detail_url);
		$this->view->disable();
	}

	public function adultAction($oid="")
	{
		if(empty($oid)){
			return False;
		}

		$publishResult = $this->sandbox->updateById($oid,array('is_adult'=>1));
		
		printf($publishResult);

		$detail_url = "/photos/detail/".$oid;
		print('Test: <a href="'.$detail_url.'">'.$detail_url.'</a>');


		$this->response->redirect($detail_url);
		$this->view->disable();
	}

	public function unadultAction($oid="")
	{
		if(empty($oid)){
			return False;
		}

		$publishResult = $this->sandbox->updateById($oid,array('is_adult'=>0));
		
		printf($publishResult);

		$detail_url = "/photos/detail/".$oid;
		print('Test: <a href="'.$detail_url.'">'.$detail_url.'</a>');


		$this->response->redirect($detail_url);
		$this->view->disable();
	}

}
