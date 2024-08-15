<?php
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Validator\Uniqueness as UniquenessValidator;

class BaseModel extends Model{

	public function initialize()
	{
		$this->setSource("wp_".strtolower(get_class($this)));
	}

	


}