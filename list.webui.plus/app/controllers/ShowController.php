<?php

class ShowController extends BaseController
{
	public function initialize(){
		parent::initialize();
	}

	public function index2Action($model="",$username="")
	{
		$filter = array('is_public'=>1);
	
		$photos = $this->sandbox->find(
			$filter,
			array(
				'limit' => 50,
				'sort' => ['ts_upload' => -1],
			)
		);

		$this->view->photos = $photos;
	}

	public function mAction($model="",$username="")
	{
		$filter = array('is_public'=>1);
		if(!empty($model)){
			$filter["model"] = $model;
		}
		if(!empty($username)){
			$filter["username"] = $username;	
		}
		$photos = $this->sandbox->find(
			$filter,
			array(
				'limit' => 50,
				'sort' => ['ts_upload' => -1],
			)
		);

		$this->view->photos = $photos;
	}
}
