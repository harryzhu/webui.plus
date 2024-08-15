<?php
use Aws\S3\S3Client;
use Aws\Exception\AwsException;



function getR2ById($model="", $username="",$oid=""){
	if(empty($model) || empty($username) || empty($oid)){
		return false;
	}

	$source_image = strtolower(join("/",array("image",$model, $username,$oid.".png")));
	$thumb_image = strtolower(join("/",array("thumb",$model, $username,$oid.".jpg")));
	return array("source_image"=>$source_image,"thumb_image"=>$thumb_image);
}

class R2WebUI
{
	public $s3_client;

	public function __construct(){
		$account_id         = R2_ACCOUNT_ID;
		$access_key_id      = R2_ACCESS_KEY_ID;
		$access_key_secret  = R2_ACCESS_KEY_SECRET;

		$credentials = new Aws\Credentials\Credentials($access_key_id, $access_key_secret);

		$options = [
			'region' => 'auto',
			'endpoint' => "https://$account_id.r2.cloudflarestorage.com",
			'version' => 'latest',
			'credentials' => $credentials
		];

		$this->s3_client = new Aws\S3\S3Client($options);
	}

	public function deleteByKey($key = ""){
		if(empty($key)){
			return false;
		}

		$query = $this->s3_client->headObject([
			'Bucket' => R2_BUCKETNAME,
			'Key' => $key
		]);

		$result = $this->s3_client->deleteObject([
			'Bucket' => R2_BUCKETNAME,
			'Key' => $key
		]);
		return $result;
	}

}

?>

