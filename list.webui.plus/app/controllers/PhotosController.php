<?php
//require BASE_PATH.'/app/library/vendor/autoload.php';

class PhotosController extends BaseController
{

	public function initialize(){
		parent::initialize();
	}

	public function detailAction($oid="")
	{
		if(empty($oid)){
			return False;
		}

		$photo = $this->sandbox->findById($oid);

		$this->view->photo = $photo;

	}
}
