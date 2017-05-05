<?php

// redirect accorfing patj_info
function router($path_info){
	$url_map = array(
		'/^\/pattern/' => 'process',
		'/^\/list_patterns$/' => 'list_patterns',
		'/^\/?$/' => 'api_index',
		);

	foreach ($url_map as $regex_pattern => $function_name) {
		if (url_to_function($regex_pattern,$function_name,$path_info) === true) {
			return true;
		}
	}

	api_404();
}




function url_to_function($regex_pattern,$function_name,$path_info){
	if (preg_match($regex_pattern,$path_info) === 1) {
		$url_chopped = preg_replace($regex_pattern, '', $path_info);
		call_user_func($function_name, $url_chopped);
		return true;
	}else{
		return false;
	}

}

function process($pattern){
	echo $pattern;
}

function list_patterns($pattern){
	echo 'this is list_patterns';
}

function api_index(){
	$data = array('message' => 'this is cook api', );
	echo json_encode($data);
}

function api_404(){
	$data = array(
		'message' => 'Not found',
		 );
	echo json_encode($data);
}






