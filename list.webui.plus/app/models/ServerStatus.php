<?php
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Validator\Uniqueness as UniquenessValidator;

class ServerStatus extends Model{

	public function initialize()
	{
		
	}

	function getConnections(){
		$sql = "show status like '%:item:%'";
$stmt = $this->db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
$stmt->execute(array(':item' => "Threads_connected");
$rows = $stmt->fetchAll();
return $rows;
	}


}