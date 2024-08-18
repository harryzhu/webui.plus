<?php
require_once(BASE_PATH.'/app/library/vendor/autoload.php');
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\View;
use Phalcon\Http\Response;

class BaseController extends Controller
{
	public $data;
	public function initialize(){
		$this->view->setRenderLevel(
            View::LEVEL_NO_RENDER
        );
        $this->response->setStatusCode(200, 'OK');
        $this->response->setContentType('application/json', 'UTF-8');
        $this->response->setHeader('Cache-Control', 'no-store');

		$this->data = array("status_code"=>"200","error"=>"");
		
	}


public function jsonOut()
    {
        
//$this->view->disable();

$response = new Response();
$response->setJsonContent($this->data, JSON_PRETTY_PRINT)->send();


        
    }

}
