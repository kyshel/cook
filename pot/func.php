<?php

// redirect accorfing patj_info
function router($path_info){
	$url_map = array(
		'/^\/trick/' => 'process',
		'/^\/list$/' => 'api_list_valid_tricks',
		'/^\/?$/' => 'api_index',
		);

	foreach ($url_map as $regex_pattern => $function_name) {
		if (preg_match($regex_pattern,$path_info) === 1){
			$url_chopped = preg_replace($regex_pattern, '', $path_info);
			return call_user_func($function_name, $url_chopped);
		}
	}

	return api_404();
}



function process($url_chopped){
	echo $url_chopped;
	echo "\n\n";

	$trick = trim($url_chopped, " /");
	//echo $trick."\n\n";

	

	return $url_chopped; 
}

function api_list_valid_tricks(){
	$dir_bin=ABSPATH."ignite/cmake/bin/";
	$files = array_diff(scandir($dir_bin), array('..', '.'));
	//pre_dump($files);
	$tricks=array();
	foreach ($files as $key => $trick_name) {
		array_push($tricks, $trick_name);
	}
	return $tricks;
}

function api_index(){
	$data = array('message' => 'this is cook api index', );
	return $data;
}

function api_404(){
	$data = array(
		'message' => 'Not found',
		 );
	return $data;
}

function pre_dump($var){
	echo '<pre>'.var_export($var,true).'</pre>';
}






