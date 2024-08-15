<?php
function join_url($md="",$un=""){
	global $_G;
	$arr_param=array("model"=>$md,"username"=>$un);

	if(empty($md)){
		$arr_param["model"]=$_G["current_model"];
	}
	if(empty($un)){
		$arr_param["username"]=$_G["current_username"];
	}

	$url = "/?m=".$arr_param["model"]."&u=".$arr_param["username"];
	return $url;
}

function nav_page($md="",$un=""){
	global $_G;
	$arr_param=array("model"=>$md,"username"=>$un,"page"=>1);

	if(empty($md)){
		$arr_param["model"]=$_G["current_model"];
	}
	if(empty($un)){
		$arr_param["username"]=$_G["current_username"];
	}

	$page_prev = 1;
	$page_next = 2;

	if($_G["current_page"]>1){
		$page_prev=$_G["current_page"] - 1;
	}

	$page_next=$_G["current_page"] + 1;

	$nav = array(
		"prev"=>"/?m=".$arr_param["model"]."&u=".$arr_param["username"]."&p=".$page_prev,
		"next"=>"/?m=".$arr_param["model"]."&u=".$arr_param["username"]."&p=".$page_next
	);
	return $nav;
}


function getAPIKey($user=""){
	if(empty($user)){
		return False;
	}
	$k = substr(md5(strtolower($user).'S@lt2023'),5,10);
	return $k;
}

function genLoginPassword($s=""){
	if(empty($s)){
		return False;
	}
	$k = md5(md5($s).'S@lt#]#%)%2023');
	return $k;
}

function get_attr($arr,$key,$default){
	$ret = $default;
	if(!empty($arr[$key])){
		$ret = $arr[$key];
	}
	return $ret;
}

function getPhotoList($photos=array()){
	if(empty($photos)){
		return False;
	}
	global $_G;
	
	$li = '';
	foreach ($photos as $photo) {
		if($photo->is_private == 1){
			if($_G['me']['username'] != $photo->username && $_G['me']['is_admin'] != 1){
				continue;
			}
		}
		$img_url = strtolower('https://r2.webui.plus/thumb/'.$photo['model'].'/'.$photo['username'].'/'.$photo['_id'].'.jpg');
		$img_src_url = strtolower('https://r2.webui.plus/image/'.$photo['model'].'/'.$photo['username'].'/'.$photo['_id'].'.png');
		$details_url = strtolower('/photos/detail/'.$photo['_id']);
		$photo_class = 'public-'.$photo['is_public'].' member-'.$photo['is_member'].' private-'.$photo['is_private'].' adult-'.get_attr($photo,'is_adult',0);
		$li .= '<div class="image-box '.$photo_class.'"><a href="'.$details_url.'" target="_blank"><img src="'.$img_url.'" /></a></div>';

	}
	return $li;
}

function getPhotoDetail($photo=array()){
	if(empty($photo)){
		return False;
	}

	$li = '';
	if(!empty($photo)){

		$photo_src_url = strtolower('https://r2.webui.plus/image/'.$photo['model'].'/'.$photo['username'].'/'.$photo['_id'].'.png');

		$li .= '<table><tr>';

		$li .= '<td width=220><a target="_blank" href="'.$photo_src_url.'"><img class="big-image" src="'.$photo_src_url.'" /></a>'.'</td>';
		$li .= '<td width=300><ul class="meta-list">';

		$li .= '<li>Model:<span class="runparam" contenteditable="true">'.$photo->model."</span></li>";
		$li .= '<li>Steps:<span class="runparam" contenteditable="true">'.$photo->steps."</span></li>";
		$li .= '<li>Sampler:<span class="runparam" contenteditable="true">'.$photo->sampler."</span></li>";
		$li .= '<li>Cfg_scale:<span class="runparam" contenteditable="true">'.$photo->cfg_scale."</span></li>";
		$li .= '<li>Size:<span class="runparam" contenteditable="true">'.$photo->width.' x '.$photo->height."</span></li>";
		$li .= '<li>Seed:<span class="runparam" contenteditable="true">'.$photo->seed."</span></li>";
		$li .= '<li>Author:<span class="runparam" contenteditable="true">'.$photo->username."</span></li>";
		$li .= '<li>Public:<span class="runparam" contenteditable="true">'.$photo->is_public."</span></li>";
		$li .= '<li>Member:<span class="runparam" contenteditable="true">'.$photo->is_member."</span></li>";
		$li .= '<li>Private:<span class="runparam" contenteditable="true">'.$photo->is_private."</span></li>";
		$devices = $photo->devices;
		$arr_devices = json_decode($devices, True);
		if(!empty($arr_devices['gpus'])){
		foreach($arr_devices['gpus'] as $gpu){
		$g_str = $gpu['name'].'[mem:'.$gpu['memory'].',driver:'.$gpu['driver'].']';
		$li .= '<li>GPU:<span class="runparam" contenteditable="true">'.$g_str."</span></li>";
		}
		}

		$li .= '</ul></td></tr></table>';

		$li .= '<table><tr>';
		$li .= '<td><h3>Prompt:</h3><textarea class="ta-big">'.$photo->prompt.'</textarea><br/><h3>Negative Prompt:</h3><textarea class="ta-big">'.$photo->negative_prompt.'</textarea></td>';

		$li .= '</tr></table>';
	}

	return $li;
}

function getModelList($filter=array(),$options=array()){
	global $coll_sandbox;
	return $coll_sandbox->distinct('model',$filter,$options);
}

function getUserList($filter=array()){
	global $coll_sandbox;
	if(empty($filter)){
		return $coll_sandbox->distinct('username');	
	}else{
		return $coll_sandbox->distinct('username',$filter);	
	}
	
}


?>
