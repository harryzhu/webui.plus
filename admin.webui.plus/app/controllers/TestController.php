<?php

class TestController extends BaseController
{
	public function initialize(){
		parent::initialize();
	}

	public function mongoAction()
	{
		$this->view->disable();
		header("content-type: text/html");

		$sandbox = new MongoWebUI('sandbox');

		echo 'Count:'.$sandbox->count().'<br/>';

		$cursor = $sandbox->addOptions(array('limit'=>10))->find();

		foreach ($cursor as $doc) {
			echo 'Latest 10 id:'.$doc['_id'].'<br/>';
		}

		echo '<hr/>';

		$cursor = $sandbox->addFilter(["username"=>"harry"])->findOne();

		echo "Filter:<br/>";
		print_r($sandbox->filter);
		echo "<br/>Options:<br/>";
		print_r($sandbox->options);
		echo '<br/><hr/>';


		$inId = $sandbox->insertOne(array("ddd"=>"sssfssf","ff"=>"cc"));

		echo "InsertOne: $inId<hr/>";

		$row = $sandbox->findById($inId);
		echo "findById: id: $inId, result: <br/>";
		print_r($row);
		echo '<hr/>';

		$upId = $sandbox->updateById($inId,array("ff"=>"ccss55ss"));
		echo "updateById: id: $inId, result: <br/>";
		print_r($upId);
		echo '<hr/>';

		$deId = $sandbox->deleteById($inId,array("ff"=>"ccss55ss"));
		echo "deleteById: id: $inId, result: <br/>";
		print_r($deId);
		echo '<hr/>';

		$row = $sandbox->findById($inId);
		echo "findById again: id: $inId, result(should not be found): <br/>";
		print_r($row);
		echo '<hr/>';

		echo 'Reset Filter or Options: <br/>Before:';
		echo "<br/>1) Filter:";
		print_r($sandbox->filter);
		echo "<br/>2)Options:<br/>";
		print_r($sandbox->options);
		echo '<hr/>';

		$sandbox->reset();
		echo "After: <br/>1) Filter:<br/>";
		print_r($sandbox->filter);
		echo "<br/>2)Options:<br/>";
		print_r($sandbox->options);
		echo '<hr/>';


		echo 'Paging: <br/>';	
		$cursor = $sandbox->addOptions(array('limit'=>3))->setPageNum(0)->find();
		echo "1) Filter:<br/>";
		print_r($sandbox->filter);
		echo "<br/>2)Options:<br/>";
		print_r($sandbox->options);
		echo '<br/><br/>';

		foreach ($cursor as $doc) {
			echo 'page 2, 5 per page:'.$doc['_id'].'<br/>';
		}
		echo '<hr/>';

		//deleteOneByModelUserId("guofeng","harry","643d67e815acc1cd8806047b");
		
	}


}
