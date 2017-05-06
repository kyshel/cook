<?php

// redirect accorfing patj_info
function router($request_method,$path_info){
	$url_map = array(
		'/^\/tricks/' => 'process',
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

	$trick = trim($url_chopped, " /");

	//get_post(); die();
	$src_image='lena.jpg'; // hash
	$dst_image='lena_gray.jpg'; // hash

	$argv_0 = ABSPATH.'ignite/cmake/bin/'.$trick;
	$argv_1 = ABSPATH.'fridge/'.$src_image;
	$argv_2 = ABSPATH.'plate/'.$dst_image;
	$argv_3 = ' ';

	$command = $argv_0.' '.$argv_1.' '.$argv_2.' '.$argv_3;
	echo $command . "\n";


	$command_escaped = escapeshellcmd($command);
	//$command_escaped = escapeshellcmd('whoami');
	$output = shell_exec($command_escaped);
	if ($output == 'FAIL') {
		$message = "imwrite fail, make sure \n
		1.apache has w priverlidge to plate dir\n
		2.there is no same name file that's not apache own";
	}else if($output == 'OK'){
		$message = $trick.' success';
	}else{
		$message = $output;
	}




	$data = array();
	$data['message'] = $message;

	return $data; 
}

function get_post(){
	echo 'aaa';
	

	return 0; 
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






