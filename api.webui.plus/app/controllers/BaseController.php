<?php
require_once(BASE_PATH.'/app/library/vendor/autoload.php');

use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;

class BaseController extends Controller
{
	public function initialize(){
		
		
	}


public function afterExecuteRoute($dispatcher)
    {
        $this->view->disable();
        $this->response->setContentType('application/json', 'UTF-8');
        $this->response->setHeader('Cache-Control', 'no-store');

        /** @var array $data */
        $data = $dispatcher->getReturnedValue();
        $dispatcher->setReturnedValue([]);

        if (true !== $this->response->isSent()) {
            $this->response->setJsonContent($data);

            return $this->response->send();
        }
    }

}
