<?php

use Phalcon\Mvc\Controller;

class SmokeController extends Controller
{
    /**
     * Welcome and user list
     */
    public function indexAction()
    {
        
    }

    public function ngxAction()
    {
        $this->view->disable();
    if(!$this->request->isPost()){
        echo "error: please use POST";
        return False;
    }
    $raw_post = $this->request->getPost();
    print_r($raw_post);
    if($this->request->hasFiles()){
        foreach ($this->request->getUploadedFiles() as $file) {
            echo $file->getName().":".$file->getSize();
            $file->moveTo("/home/tmp/test/1.jpg");
        }
    }
    }
}
