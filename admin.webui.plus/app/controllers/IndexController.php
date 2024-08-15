<?php

class IndexController extends BaseController
{
	public function initialize(){
		parent::initialize();
	}

	public function indexAction()
	{
		$this->view->disable();
		header("content-type: text/html");

		$sandbox = new MongoWebUI('sandbox');

		echo $sandbox->count();

		$cursor = $sandbox->find();

		foreach ($cursor as $doc) {
			echo $doc['_id'];
			echo '<br/>';
		}

		echo '<hr/>';

		$cursor = $sandbox->addFilter(["username"=>"harry"])->findOne();

		print_r($sandbox->filter);

		
		
		echo '<br/>';

		echo '<hr/>';

		$inId = $sandbox->insertOne(array("ddd"=>"sssfssf","ff"=>"cc"));

		print_r($inId);

		echo '<br/>';

		$row = $sandbox->findById($inId);
		print_r($row);
echo '<br/>';

		$upId = $sandbox->updateById($inId,array("ff"=>"ccss55ss"));

		print_r($upId);

		echo '<br/>';

		$deId = $sandbox->deleteById($inId,array("ff"=>"ccss55ss"));

		print_r($deId);

		echo '<br/>';
		
	}


}
