<?php
require_once(BASE_PATH.'/app/library/vendor/autoload.php');
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;

class BaseController extends Controller
{
	public $data;
	public function initialize(){
		$this->data = array();
		
	}


public function jsonOut()
    {
        $this->view->disable();
        $this->response->setContentType('application/json', 'UTF-8');
        $this->response->setHeader('Cache-Control', 'no-store');


        $this->response->setJsonContent($this->data);

       $this->response->send();
        
    }

}
