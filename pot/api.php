<?php
require_once('load.php');
header('Content-Type: application/json');

$config_data = array();
$config_data['ABSPATH'] = ABSPATH;
$config_data['REQUEST_METHOD'] = $_SERVER['REQUEST_METHOD'];
$config_data['PATH_INFO'] = $_SERVER['PATH_INFO'];


//echo '<meta charset="utf-8">'; 
//check $_SERVER['PATH_INFO'] not too long, incase regex crash
//pre_dump($_SERVER);
pre_dump($config_data);
echo "\n\n";

$data = router($_SERVER['PATH_INFO']);
echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);









// get the HTTP method, path and body of the request
//$method = $_SERVER['REQUEST_METHOD'];


// $input = json_decode(file_get_contents('php://input'),true);
// echo '--- file_get_contents <br>';
// var_dump(file_get_contents('php://input'));
// echo '<br>---<br>';
// echo '--- json_decode <br>';
// var_dump($input);
// echo '<br>---<br>';



