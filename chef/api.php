<?php
require_once('load.php');
header('Content-Type: application/json');

$config_data = array();
$config_data['ABSPATH'] = ABSPATH;
$config_data['REQUEST_METHOD'] = $_SERVER['REQUEST_METHOD'];
$config_data['PATH_INFO'] = $_SERVER['PATH_INFO'];

if (isset($_GET['d']) && ($_GET['d'] == '1')) {
	pre_dump($config_data);
	echo "\n\n";
}


$data = router($_SERVER['REQUEST_METHOD'],$_SERVER['PATH_INFO']);
echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);








