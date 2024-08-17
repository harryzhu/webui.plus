<?php
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Validator\Uniqueness as UniquenessValidator;

class ServerStatus extends Model{

	public function initialize()
	{
		
	}

	function getConnections(){
		print_r(this->config['db']);
		$sql = "show status like '%:item:%'";
		$stmt = $this->config->db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$stmt->execute(array("item" => "Threads_connected"));
		$rows = $stmt->fetchAll();
		return $rows;
	}


}