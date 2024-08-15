<?php
use Phalcon\Mvc\View;
use Phalcon\Mvc\Controller;

class TasksController extends Controller
{
    public function torunAction()
    {
     $this->view->disable();
     $this->response->setContentType("text/json; charset=utf-8");

     $tasktxt2img = new MongoWebUI("tasktxt2img");
     $tasktxt2img->filter=array('status'=>1);
     $tasktxt2img->options=array('sort'=>array('utc_update'=>1));
     $tasks = $tasktxt2img->find();

     $results = array();
     if(!empty($tasks)){
        foreach ($tasks as $task) {
            if(empty($task['model'])){
                continue;
            }
            $t = array(
                'oid'=>(string)$task['_id'],
                'override_settings'=>array('sd_model_checkpoint'=>$task['model']),
                'prompt'=>$task['prompt'],
                'negative_prompt'=>$task['negative_prompt'],
                'sampler_index'=>$task['sampler'],
                'cfg_scale'=>$task['cfg_scale'],
                'steps'=>$task['steps'],
                'width'=>$task['width'],
                'height'=>$task['height'],
                'seed'=>-1,
                'batch_size'=>3,
                'n_iter'=>1,
                'restore_faces'=>false,
                'tiling'=>false,

                'denoising_strength'=>0,
            );
            array_push($results,$t);
        }
    }

    echo json_encode($results);
}


public function markAction($oid="",$k="",$v="")
{
    if(strlen($oid)==0 || strlen($k)==0 || strlen($v)==0){
        return false;
    }
    $this->view->disable();

    $tasktxt2img = new MongoWebUI("tasktxt2img");
    if(is_numeric($v)){
       $res = $tasktxt2img->updateById($oid,array($k=>(int)$v));
   }else{
       $res = $tasktxt2img->updateById($oid,array($k=>$v));
   }
   
   echo $res;
}





}
