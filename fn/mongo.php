<?php
require_once('vendor/autoload.php');
require_once('r2.php');
require_once(dirname(__FILE__). "/../config/settings.global.php");

function mongoOID($oid=""){
	if(empty($oid)){
		return false;		
	}
	return new MongoDB\BSON\ObjectId($oid);
}

class MongoWebUI 
{
	public $client;
	public $collection;
	public $filter;
	public $options;
	public $pagenum;

	private $default_filter;
	private $default_options;

	public function __construct($collname=""){
		$this->client = new MongoDB\Client(ENDPOINT);		
		$this->collection = $this->client->selectCollection(DBNAME,$collname);

		$this->default_filter = array(
			"is_public"=>FILTER_IS_PUBLIC,
			"is_member"=>0,
			"is_private" => 0,
		);

		$this->default_options = array(
			'sort'=>array('ts_upload'=>-1)
		);

		$this->filter = $this->default_filter;
		$this->options=$this->default_options;
	}

	public function addFilter($cond=array()){
		$this->filter=array_merge($this->filter,$cond);
		return $this;
	}

	public function addOptions($cond=array()){
		$this->options=array_merge($this->options,$cond);
		return $this;
	}

	public function setPageNum($cond=0){
		$this->options['skip'] = OPTIONS_PAGE_SIZE * $cond;
		return $this;
	}


	public function count(){
		return $this->collection->count();
	}

	public function distinct($cond="",$filter=array())
	{
		return $this->collection->distinct($cond,$filter);
	}

	public function reset($is_reset_filter=True,$is_reset_options=True){
		if($is_reset_filter){
			$this->filter = $this->default_filter;			
		}
		if($is_reset_options){
			$this->options=$this->default_options;
		}
		return $this;
	}

	public function find(){

		//$this->collection->updateMany(array('is_private'=>'000'),array('$set'=>array('is_private'=>0)));

		return $this->collection->find($this->filter,$this->options);
	}

	public function findOne(){
		return $this->collection->findOne($this->filter,$this->options);
	}

	public function insertOne($doc=array()){
		if(empty($doc)){
			return false;
		}
		$doc['utc_update'] = new MongoDB\BSON\UTCDateTime;
		return $this->collection->insertOne($doc)->getInsertedId();
	}

	public function findById($cond=""){
		if(empty($cond)){
			return false;
		}

		$oid = mongoOID($cond);
		if(empty($oid)){
			return false;
		}

		return  $this->collection->findOne(array('_id'=>$oid));
	}

	public function updateById($cond="",$doc=array()){
		if(empty($cond) || empty($doc)){
			return false;
		}

		$oid = mongoOID($cond);
		if(empty($oid)){
			return false;
		}

		$doc['utc_update'] = new MongoDB\BSON\UTCDateTime;

		return  $this->collection->updateOne(array('_id'=>$oid),array('$set'=>$doc))->getModifiedCount();
	}

	public function deleteById($cond=""){
		if(empty($cond)){
			return false;
		}

		$oid = mongoOID($cond);
		if(empty($oid)){
			return false;
		}

		return  $this->collection->deleteOne(array('_id'=>$oid))->getDeletedCount();
	}

}




?>