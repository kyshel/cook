<?php
if(!defined('ABSPATH')) exit;  // Exit if accessed directly

// redirect according path_info
function router($request_method,$path_info){
	$url_map = array(
		'/^\/tricks\//' => 'process',
		'/^\/tricks$/' => 'get_valid_tricks',
		'/^\/?$/' => 'api_index',

		'/^\/deposit/' => 'api_deposit', 

		);


	foreach ($url_map as $regex_pattern => $function_name) {
		if (preg_match($regex_pattern,$path_info) === 1){
			$url_chopped = preg_replace($regex_pattern, '', $path_info);
			return call_user_func($function_name, $url_chopped);
		}
	}

	return api_404();
}

function api_deposit(){
	$input=get_input();
	
	$message = "deposit OK";

	$response=array(
		'message'=> $message ,
		'origin_name' =>  $input['origin_name'],
		'uid' =>  $input['uid'],
		'src_ext' =>  $input['src_ext'],
		'dst_ext' =>  $input['dst_ext'],
		'step' =>  $input['step'],
		);
	//pre_dump($response);

	return $response;
}


function process($url_chopped){

	$trick = trim($url_chopped, " /");
	if (!in_array($trick,get_valid_tricks())) {
		return api_404('tricks not valid');
	}
		

	//http_response_code(201);
	$input=get_input(); 
	$uid= $input['uid'];
	$is_preview=$input['is_preview']; 
	$pre_step=$input['step'] ; 
	$now_step= ( $is_preview == 0) ? $input['step'] + 1 : '_preview';
	$src_ext=$input['src_ext']; 
	$dst_ext=$input['dst_ext']; 
	$src_image=ABSPATH.'imgs/'.$uid.'/step'.$pre_step.'.'.$src_ext; 
	$dst_image=ABSPATH.'imgs/'.$uid.'/step'.$now_step.'.'.$dst_ext; 


	$argv_0 = ABSPATH.'ignite/cmake/bin/'.$trick;
	$argv_1 = $src_image;
	$argv_2 = $dst_image;
	$argv_3p = $input['argv_3p']; // need check

	$command = $argv_0.' '.$argv_1.' '.$argv_2.' '.$argv_3p;
	//echo "\n".$command . "\n";


	$command_escaped = escapeshellcmd($command);
	//$command_escaped = escapeshellcmd('whoami');
	$output = shell_exec($command_escaped);
	if ($output == 'FAIL') {
		$message = "imwrite fail, make sure \n
		1.apache has w priverlidge to plate dir\n
		2.there is no same name file that's not apache own";
	}else if($output == 'OK'){
		$message = $trick.' success';
		http_response_code(201);
	}else{
		$message = $output;
	}

	$data = array();
	$data['message'] = $message;
	$data['image'] =  array(
		'origin_name' => $input['origin_name'], 
		'uid' => $input['uid'], 
		'step' => $now_step, 
		'src_ext' => $src_ext,
		'dst_ext' => $dst_ext
		);

	return $data; 
}

function get_input(){
	$raw_input = file_get_contents('php://input');
	//echo 'input:'; pre_dump($raw_input); echo "\n";
	$array_input = json_decode($raw_input,true); 
	//pre_dump($array_input);

	if ($array_input == NULL) {
		echo 'input string cannot parse to json';
		return 'error';
	}

	$origin_name_parts = pathinfo($array_input['origin_name']);
	$origin_name_a = $origin_name_parts['filename'];
	$src_ext = $origin_name_parts['extension'];

	if (isset($array_input['uid'])) { // deposited
		$uid = $array_input['uid'];
		$step = $array_input['step'];
		$src_ext = $array_input['src_ext'];
	}else{ // deposit
		$uid = get_uid();
		$ext = $origin_name_parts['extension']; 
		$step = 0;
		$unique_path = ABSPATH.'imgs/'.$uid;
		$file_path = ABSPATH.'imgs/'.$uid.'/step'.$step.'.'.$ext;
		mkdir($unique_path , 0700);

		if(isset($array_input['remote_url'])){ 
			$remote_image = file_get_contents($array_input['remote_url'],NULL,NULL,NULL,REMOTE_IMAGE_MAXSIZE);
			file_put_contents($file_path, $remote_image);
		}
		else{ 
			base64_to_jpeg($array_input['content'], $file_path);
		}
			// (refactor) if last code write ok, then 
		http_response_code(201);

	} 



	$argv_3p=isset($array_input['argv_3p'])?$array_input['argv_3p']:'';
	$dst_ext=isset($array_input['dst_ext'])?$array_input['dst_ext']:$origin_name_parts['extension'];
	$is_preview=isset($array_input['is_preview'])?$array_input['is_preview']:0;

	$stored_input=array(
		'origin_name' => $array_input['origin_name'],  
		 
		'uid' => $uid , 
		'step' => $step , 

		'src_ext' => $src_ext ,
		'dst_ext' => $dst_ext , 	

		'argv_3p' => $argv_3p , 
		'is_preview' => $is_preview ,
		);

	//pre_dump($stored_input);

	return $stored_input; 
}

function get_uid(){
	return uniqid().'_'.rand();
}


function base64_to_jpeg($base64_string, $output_file) {
    // open the output file for writing
    $ifp = fopen( $output_file, 'wb' ); 
    // split the string on commas
    // $data[ 0 ] == "data:image/png;base64"
    // $data[ 1 ] == <actual base64 string>
    $data = explode( ',', $base64_string );
    // we could add validation here with ensuring count( $data ) > 1
    fwrite( $ifp, base64_decode( $data[ 1 ] ) );
    // clean up the file resource
    fclose( $ifp ); 

    return $output_file; 
}



function get_valid_tricks(){
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
	$valid_api =  array(
		'/tricks' => 'list all tricks', 
		'/tricks/:trick' => 'ignite image by the trick', 
		'/deposit' => 'deposit image', 
		);
	$data = array(
		'message' => 'this is cook api index', 
		'valid_api' => $valid_api,
		);
	return $data;
}

function api_404($message = 'Not found'){
	http_response_code(404);
	$data = array(
		'message' => $message,
		);
	return $data;
}

function pre_dump($var){
	echo '<pre>'.var_export($var,true).'</pre>';
}








