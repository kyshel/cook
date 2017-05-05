<?php
require_once('load.php');
header('Content-Type: application/json');

echo '<h1>'.ABSPATH.'</h1>';

//echo '<meta charset="utf-8">'; 

//check $_SERVER['PATH_INFO'] not too long

echo '--- REQUEST_METHOD & PATH_INFO<br>';
echo $_SERVER['REQUEST_METHOD'].' &nbsp; '.$_SERVER['PATH_INFO'];
echo '<br>---<br>';


router($_SERVER['PATH_INFO']);









// get the HTTP method, path and body of the request
$method = $_SERVER['REQUEST_METHOD'];


// $input = json_decode(file_get_contents('php://input'),true);
// echo '--- file_get_contents <br>';
// var_dump(file_get_contents('php://input'));
// echo '<br>---<br>';
// echo '--- json_decode <br>';
// var_dump($input);
// echo '<br>---<br>';



