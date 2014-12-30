<?php

session_start();

require_once('config.php');

// Create connection
$con =mysqli_connect($conn_host,$conn_user,$conn_pw,$conn_db);

// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

function getAllTags($con){

    $sql = "SELECT DISTINCT tag FROM order_tag_x_order ORDER BY tag ASC";
    $alltags_results = mysqli_query($con,$sql);
    return $alltags_results;

}

function orderDetailsById($con,$id){

	if($id > 0 && $con){

		//get order details
		$sql = "
		SELECT *, O.type AS order_type, DATE_FORMAT(O.created_on,'%c-%e-%y %h:%i %p') AS order_date, DATE_FORMAT(O.created_on,'%c-%e-%y') AS order_date2, DATE_FORMAT(O.ship_email_sent_on,'%c-%e-%y') AS ship_date FROM `order` O
		WHERE O.master_order_id = " . $id . "
		ORDER BY O.id DESC
		LIMIT 0,1
		";

		$result = mysqli_query($con,$sql);
		$order = mysqli_fetch_array($result);

		if($order['batch_id'] > 0){
		//get batch info
		$sql = "
		SELECT B.created_on AS batch_date FROM batch B
		WHERE B.id = ". $order['batch_id'] ."
		";

		$result = mysqli_query($con,$sql);
		$batch = mysqli_fetch_array($result);
		$order['batch_date'] = $batch['batch_date'];		
		} else {
			$order['batch_date'] = "";
		}

		//get shipping address
		$sql = "
		SELECT * FROM address A
		WHERE A.id = ". $order['shipping_address_id'] ."
		";

		$result = mysqli_query($con,$sql);
		$shipping_address = mysqli_fetch_array($result);
		$order['shipping_address'] = $shipping_address;


		//get billing address
		$sql = "
		SELECT * FROM address A
		WHERE A.id = ". $order['billing_address_id'] ."
		";

		$result = mysqli_query($con,$sql);
		$billing_address = mysqli_fetch_array($result);
		$order['billing_address'] = $billing_address;

		//get cart details
		$sql = "
		SELECT * FROM `cart` C
		WHERE C.id = " . $order['cart_id'] . "
		";

		$result = mysqli_query($con,$sql);
		$cart = mysqli_fetch_array($result);		
		$order['cart'] = $cart;
		$order['total'] = $cart['total_price'];

		//get item details
		$sql = "
		SELECT * FROM `cart_item` CI
		INNER JOIN maker4_item M ON M.id = CI.maker4_item_id
		WHERE CI.cart_id = " . $order['cart_id'] . "
		AND in_trash = 0
		";

		$qty = 0;
		$result = mysqli_query($con,$sql);
		$num_designs = mysqli_num_rows($result);
		while($cart_item = mysqli_fetch_array($result)){

			$order['cart_items'][] = $cart_item;
			$qty = $qty + $cart_item['quantity'];
		}
		
		$order['total_designs'] = $num_designs;
		$order['total_items'] = $qty;
					

		return $order;

	} else {
		return 0;
	}
}

function getCustomerInfoByEmail($con,$email){

	$customer = array();

	if($email != '' && $con){

		$lifetime_spend = 0;

		//get item details
		$sql = "
		SELECT * FROM `order` O
		INNER JOIN cart C ON C.id = O.cart_id
		WHERE O.email = '" . $email . "'
		GROUP BY O.master_order_id
		ORDER BY O.created_on DESC
		";

		$result = mysqli_query($con,$sql);
		$customer['num_orders'] = mysqli_num_rows($result);
		while($customer_order = mysqli_fetch_array($result)){

			$customer['orders'][] = $customer_order;

			$lifetime_spend = $lifetime_spend + $customer_order['total_price'];

		}		

		$customer['lifetime_spend'] = $lifetime_spend;

		return $customer;

	} else {
		return 0;
	}	


}

function getNextOrderId($con,$id){

		//get next order, HIGHER number
		$sql = "
		SELECT master_order_id FROM `order` O
		WHERE O.master_order_id > '" . $id . "'
		AND status = 'approved'
		AND invalid = 0
		ORDER BY O.master_order_id ASC
		LIMIT 0,1
		";

		$result = mysqli_query($con,$sql);
		$row = mysqli_fetch_array($result);
		
		return $row['master_order_id'];

}

function getPreviousOrderId($con,$id){

		//get next order, HIGHER number
		$sql = "
		SELECT master_order_id FROM `order` O
		WHERE O.master_order_id < '" . $id . "'
		AND status = 'approved'
		AND invalid = 0
		ORDER BY O.master_order_id DESC
		LIMIT 0,1
		";

		$result = mysqli_query($con,$sql);
		$row = mysqli_fetch_array($result);
		
		return $row['master_order_id'];

}

function getItemOrderDetails($con,$id){

	if($con && $id > 0){

		


	} else {
		return 0;
	}

}


?>