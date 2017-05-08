<?php

// redirect accorfing patj_info
function router($request_method,$path_info){
	$url_map = array(
		'/^\/tricks\//' => 'process',
		'/^\/tricks$/' => 'api_list_valid_tricks',
		'/^\/?$/' => 'api_index',

		'/^\/deposit/' => 'api_deposit', // reponse a cooklie 
		'/^\/deposited/' => 'api_deposited', // reponse a cooklie 

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
	$_SESSION['cook_origin_name'] = $input['origin_name'];
	$_SESSION['cook_unique_name'] = $input['unique_name'];
	$message = "deposit OK";
	$response=array(
		'message'=> $message,
		'origin_name' => $_SESSION['cook_origin_name'],
		'unique_name' => $_SESSION['cook_unique_name'],
		);
	return $response;
}

function process($url_chopped){

	$trick = trim($url_chopped, " /");
	//http_response_code(201);
	$input=get_input(); 
	//pre_dump($input);

	$src_image=$input['unique_name']; // hash
	$dst_image=$input['unique_name_a'].'_'.$trick.'.'.$input['unique_name_b']; // hash

	$argv_0 = ABSPATH.'ignite/cmake/bin/'.$trick;
	$argv_1 = ABSPATH.'fridge/'.$src_image;
	$argv_2 = ABSPATH.'plate/'.$dst_image;
	$argv_3 = $input['argv_3'];

	$command = $argv_0.' '.$argv_1.' '.$argv_2.' '.$argv_3;
	//echo $command . "\n";


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
		'unique_name' => $src_image, 
		'tricked_name' => $dst_image, 
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
	$origin_name_b = $origin_name_parts['extension'];

	if (isset($array_input['unique_name'])) {
		$unique_name = $array_input['unique_name'];
		$unique_name_parts = pathinfo($array_input['unique_name']);
		$unique_name_a = $unique_name_parts['filename'];
		$unique_name_b = $unique_name_parts['extension'];
	}else{
		$unique_name_a = get_unique_name();
		$unique_name_b = $origin_name_parts['extension'];
		$unique_name = $unique_name_a.'.'.$unique_name_b;
		base64_to_jpeg($array_input['content'], ABSPATH.'fridge/'.$unique_name);
		// (refactor) if last code write ok, then 
		http_response_code(201);
	}

	$argv_3=isset($array_input['argv_3'])?$array_input['argv_3']:'';

	$stored_input=array(
		'origin_name' => $array_input['origin_name'] , 
		'origin_name_a' => $origin_name_a , 
		'origin_name_b' => $origin_name_b , 

		'unique_name' => $unique_name , 
		'unique_name_a' => $unique_name_a , 
		'unique_name_b' => $unique_name_b , 

		'argv_3' => $argv_3 , 
		);

	return $stored_input; 
}

function get_unique_name(){
	return 'unique_aaa';
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
	$valid_api =  array(
		'/tricks' => 'list all tricks', 
		'/tricks/:trick' => 'ignite image by the trick', 
		'/deposit' => 'deposit image to fridge & stored in SESSION, for future use', 
		);
	$data = array(
		'message' => 'this is cook api index', 
		'valid_api' => $valid_api,
		);
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






