<?php
require BASE_PATH.'/app/library/vendor/autoload.php';

use Phalcon\Mvc\Controller;

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

class UploadController extends Controller
{
    public $s3_client;
    public $mongo_client;

    public function initialize(){
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

        $this->mongo_client = new MongoDB\Client(ENDPOINT);
    }

    public function resizeImage($imgdata,$w=256,$h=0){
        if(empty($imgdata)){
            return False;
        }

        $img = new Imagick();
        $img->readImageBlob($imgdata);

        if($w > 0 && $h == 0){
            $h = floor($img->getImageHeight() * $w / $img->getImageWidth());
        }

        if($w == 0 && $h > 0){
            $w = floor($img->getImageWidth() * $h / $img->getImageHeight());
        }

        if($w == 0 && $h == 0){
            $w = 256;
            $h = floor($img->getImageHeight() * $w / $img->getImageWidth());
        }
        
        $img->thumbnailImage($w,$h);
        $img->setImageFormat('jpeg');

        $img_blob = $img->getImageBlob();
        //$img->writeImage("/home/svc/www/c.jpg");
        $img->destroy();

        //$tmpFile->close();

        return $img_blob;
    }

    public function getSortMD5($s=""){
      if(empty($s)){
        return False;
    }  
    $s = strtolower($s); 
    $s = preg_replace('!\s+!', ' ', $s);
    $s = str_replace(" ", ",", $s);
    $s = str_replace("\n", "", $s);
    $s = str_replace("\r", "", $s);
    $s = explode(",", $s);
    sort($s);
    $s2 = join(",",$s);
    return md5($s2);

}

public function getAPIKey($user=""){
    if(empty($user)){
        return False;
    }
    $k = substr(md5(strtolower($user).'S@lt2023'),5,10);
    return $k;
}

public function mongoSave($arr_data=array()){
    $sandbox = $this->mongo_client->StableDiffusion->sandbox;
    if(!is_array($arr_data)){
        return False;
    }

    $result = $sandbox->insertOne($arr_data);

    return  $result->getInsertedId();
}

public function indexAction()
{
    $this->view->disable();
    if(!$this->request->isPost()){
        echo "error: please use POST";
        return False;
    }

    $bucket_name = "webui";

    $raw_post = $this->request->getPost();
    $image_bin = empty($raw_post["image_bin"])?null:$raw_post["image_bin"];
    if(empty($image_bin)){
        echo "error: image data could not be nil";
        return False;
    }

// meta        
    $meta_post = $raw_post;
    unset($meta_post["image_bin"]);
        //print_r($meta_post);

    $username =  empty($meta_post["username"])?"":strtolower($meta_post["username"]);
    $userkey =  empty($meta_post["userkey"])?"":strtolower($meta_post["userkey"]);
    $model =  empty($meta_post["model"])?"":strtolower($meta_post["model"]);
    $prompt =  empty($meta_post["prompt"])?"":strtolower($meta_post["prompt"]);
    $md5_file =  empty($meta_post["md5_file"])?"":strtolower($meta_post["md5_file"]);   
    $content_type =  empty($meta_post["content_type"])?"":strtolower($meta_post["content_type"]);
    $is_private =  $meta_post["is_private"]?1:0; 


    if(empty($username) || empty($prompt) || empty($userkey)||empty($model)||empty($md5_file)||empty($content_type)){
        echo "error: username/prompt/userkey/model/md5_file/content_type could not be nil";
        return False;
    }

    $user_data_dir = join("/", array(rtrim(U2_DATA_DIR,"/"),"user",$username));
    if(!is_dir($user_data_dir)){
        mkdir($user_data_dir);
    }
    $user_upload_limit = 100;
    $user_upload_current = 0;
    $user_upload_current_file = join("/", array($user_data_dir,"upload_current.txt"));
    if(file_exists($user_upload_current_file)){
        $user_upload_current = (int)file_get_contents($user_upload_current);
    }else{
        file_put_contents($user_upload_current_file, "1");
    }

    if($user_upload_current >= $user_upload_limit){
        echo "error: users of free plan can upload ".$user_upload_limit." images only. you can buy more quota.";
        return False;
    }


    //
    $valid_userkey = $this->getAPIKey($username);

    if($valid_userkey != $userkey){
        echo "error: username and user-api-key are not matched";
        return False;
    }


    $meta_post["md5_prompt"] = $this->getSortMD5($prompt);
//
    $meta_post["is_public"] = 0;
    $meta_post["is_forsale"] = 0;
    $meta_post["is_member"] = 0;
    $meta_post["is_adult"] = 0;
    $meta_post["is_private"] = $is_private;

    $now_utc = new MongoDB\BSON\UTCDateTime;
    $meta_post["utc_create"] = $now_utc;
    $meta_post["utc_update"] = $now_utc;


    $mongo_id = $this->mongoSave($meta_post);

    $arr_res = array();
    $arr_res["error"] = "";

    if(!empty($mongo_id)){
     $arr_res["mongo-id"] = $mongo_id;
     $object_id = strtolower(join("/",array("image",$model, $username,$mongo_id.".png")));

     $result = $this->s3_client->putObject([
        'Bucket' => $bucket_name,
        'Key' => $object_id,
        'ContentType' => $content_type,
        'Body' => $image_bin
    ]);

     if ($result['@metadata']['statusCode'] < 400){
        $arr_res["r2-source-image"] = $object_id;
    }else{
     $arr_res["error"] .= "cannot save source-image into R2";
 }
}else{
   $arr_res["error"] .= "cannot save meta into mongo";
}

$thumb256_bin = $this->resizeImage($image_bin,256,0);

if(!empty($mongo_id) && !empty($thumb256_bin)){
    $thumb_object_id = strtolower(join("/",array("thumb",$model, $username,$mongo_id.".jpg")));

    $thhumb_result = $this->s3_client->putObject([
        'Bucket' => $bucket_name,
        'Key' => $thumb_object_id,
        'ContentType' => $content_type,
        'Body' => $thumb256_bin
    ]);

    if ($thhumb_result['@metadata']['statusCode'] < 400){
        $arr_res["r2-thumb-image"] = $thumb_object_id;
    }else{
     $arr_res["error"] .= "cannot save thumb-image into R2";
 }

}
echo json_encode($arr_res, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
}



}
