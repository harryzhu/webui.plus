<?php
require_once(BASE_PATH.'/app/library/vendor/autoload.php');
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\View;
use Phalcon\Http\Response;

class BaseController extends Controller
{
	public $message;

	public function initialize(){
		$this->view->setRenderLevel(
			View::LEVEL_NO_RENDER
		);

		

		$this->message = array("status_code"=>200,"error"=>"","data"=>array());
		
	}


	public function afterExecuteRoute(): bool {
		
		$response = new Response();

		$response->setStatusCode($this->message["status_code"], 'OK');
		$response->setContentType('application/json', 'UTF-8');
		$response->setHeader('Cache-Control', 'no-store');

		$response->setJsonContent($this->message);
		$response->send();


		return True;
	}

}
