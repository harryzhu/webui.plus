<?php
require_once('mongo.php');
require_once('r2.php');



function deleteOneByModelUserId($model="", $username="",$oid=""){
	if(empty($model) || empty($username) || empty($oid)){
		return false;
	}

	$docid = mongoOID($oid);

	$arr_r2image = getR2ById($model, $username, $oid);
	//print_r($arr_r2image);
	if(empty($arr_r2image)){
		return false;
	}

	$sandbox = new MongoWebUI("sandbox");
	$r2 = new R2WebUI();

	$mg_res = $sandbox->deleteById($docid);
	$r2_src_res = $r2->deleteByKey($arr_r2image["source_image"]);
	$r2_thumb_res = $r2->deleteByKey($arr_r2image["thumb_image"]);
	print_r($r2_src_res);

	//print("delete meta: " .$mg_res);
	//print("delete source_image: " .$r2_src_res->get($arr_r2image["source_image"]));
	//print("delete thumb_image: " .$r2_thumb_res->get($arr_r2image["thumb_image"]));

}



?>

<?php



?>