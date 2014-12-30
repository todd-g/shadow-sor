<?php 

$conn_host 		= "localhost";
$conn_db 		= "sor_import";
$conn_user		= "root";
$conn_pw 		= "root";

// Create connection
$con =mysqli_connect($conn_host,$conn_user,$conn_pw,$conn_db);

$response = array();
$response['data'] = null;
$response['success'] = 1;
$response['errors'] = array();

$qstring = $_SERVER["QUERY_STRING"];
parse_str($qstring, $params);

$action = $_GET['action'];

if($action){

	$fn_output = call_user_func($action,$params);
	$response['data'] = $fn_output;

	if($_GET['example'] == 1)
	{
		header("Content-type: text");
		print_r($response);
	} else {
		echo json_encode($response);
	}

} else {
	$response['success'] = 0;
	$response['errors'][] = "No action.";
}




function addTag($params){

	require_once('includes/connection.php');

	if($params['tag']){

		$sql = "INSERT INTO order_tag_x_order (tag, order_id) VALUES ('" . $params['tag'] . "', " . $params['order_id'] . ")";
		$result = mysqli_query($con,$sql);
		$new_id = mysqli_insert_id($con);
		$data['new_id'] = $new_id;
		$data['new_tag'] = $params['tag'];
		return $data;
	}

}

function deleteTag($params){

	require_once('includes/connection.php');

	if($params['tag_id']){

		$sql = "DELETE FROM order_tag_x_order WHERE id = " . $params['tag_id'];
		$result = mysqli_query($con,$sql);
		//$new_id = mysqli_insert_id($con);
		//$data['new_id'] = $new_id;
		$data['deleted_tag_id'] = $params['tag_id'];
		return $data;
	}

}

function dismissTag($params){

	require_once('includes/connection.php');

	if($params['order_id']){

		$sql = "UPDATE `order` SET tag_dismissed = 1 WHERE id = " . $params['order_id'];
		$result = mysqli_query($con,$sql);
		return $data;
	}

}




?>