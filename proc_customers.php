<?php

require_once('includes/connection.php');

$start = 0;
$get = 5000;

$process = 'proc_customers.php';
$last_id = 0;
$prev_last_id = 0;
$notes = '';

//process customers
echo "<br>processing customers...";

//get last id from this process
$psql = "
SELECT last_id FROM job_log
WHERE process = '" . $process . "'
AND last_id != 0
ORDER BY date_run DESC
LIMIT 0,1
";
$presult = mysqli_query($con,$psql);
$lastrow = mysqli_fetch_array($presult);
//echo "<br>" . $psql;
//print_r($lastrow);
$prev_last_id = $lastrow['last_id'];

echo "<br>Last processed id was: " . $prev_last_id;


//get all valid orders, oldest first, that we haven't processed yet
$osql = "
SELECT *, O.id AS oid, A.name, A.city, A.state FROM `order` O
INNER JOIN cart C ON C.id = O.cart_id
INNER JOIN address A ON A.id = O.billing_address_id
WHERE O.invalid = 0
AND O.status = 'approved'
AND O.type != 'reprint'
AND O.id > " . $prev_last_id . "
GROUP BY O.master_order_id
ORDER BY O.id ASC
LIMIT ". $start.", ".$get."
";
echo $osql;
$oresult = mysqli_query($con,$osql);

while($order = mysqli_fetch_assoc($oresult)){

	echo "<br>Processing order id: " . $order['oid'];
	echo "<br>Master order id: " . $order['master_order_id'];
	
	$email = $order['email'];

	$csql = "
	SELECT * FROM customer_flat C 
	WHERE C.email = '". $email ."'
	";
	$cresult = mysqli_query($con,$csql);
	$cnum = mysqli_num_rows($cresult);

	echo "<br>" . $cnum . " customers found.";

	//get the quantity
    $qsql = "SELECT sum(quantity) AS qty FROM cart_item CI WHERE CI.cart_id = " . $order['cart_id'];
    $qresult = mysqli_query($con,$qsql);
    $qrow = mysqli_fetch_array($qresult);

	//if we found a result then this is an existing customer
	if($cnum > 0){
		//existing customer
		echo "<br>" . $email . " is an existing customer";

		//get the row
		$customer = mysqli_fetch_assoc($cresult);

		//print_r($customer);

		$uc = array();
		$uc['last_order_date'] = $order['created_on'];
		$uc['last_order_id'] = $order['master_order_id'];

		$uc['total_spend'] 	= $customer['total_spend'] + $order['total_price'];
		$uc['total_orders'] = $customer['total_orders'] + 1;

		if($customer['total_orders'] == 1){
			$repeat = true;
			$do_repeat = " became_repeat = '" . $order['created_on'] . "', ";
		}

		$uc['total_items'] = $customer['total_items'] + $qrow['qty'];

        $uc['avg_spend'] = $uc['total_spend'] / $uc['total_orders'];
        $uc['avg_items'] = $uc['total_items'] / $uc['total_orders'];

        $uc['name'] 	= $order['name'];
        $uc['city'] 	= $order['city'];
        $uc['state']	= $order['state'];

        echo "<br>Update with: <br>";
        print_r($uc);

        $usql = "
        UPDATE customer_flat
        SET 
        last_order_date = '" . $uc['last_order_date'] . "',
        name = '" . $uc['name'] . "',
        city = '" . $uc['city'] . "',
        state = '" . $uc['state'] . "',
        total_spend = " . $uc['total_spend'] . ",
        total_orders = " . $uc['total_orders'] . ",
        total_items = " . $uc['total_items'] . ",
        avg_spend = " . $uc['avg_spend'] . ",
        avg_items = " . $uc['avg_items'] . ",
        last_order_id = '" . $uc['last_order_id'] . "',

        " . $do_repeat . "
        last_updated = NOW()
        WHERE email = '" . $email . "'
        ";
        $uresult = mysqli_query($con,$usql);

        echo "<br>Customer updated.";


	} else {
		//new customer
		echo "<br>" . $email . " is a new customer";

		$customer = array();

		$customer['first_order_date'] = $order['created_on'];
		$customer['last_order_date'] = $order['created_on'];

		$customer['first_order_id'] = $order['master_order_id'];
		$customer['last_order_id'] = $order['master_order_id'];

		$customer['total_spend'] = $order['total_price'];
		$customer['total_orders'] = 1;

        $customer['total_items'] = $qrow['qty'];

        $customer['avg_spend'] = $customer['total_spend'] / $customer['total_orders'];
        $customer['avg_items'] = $customer['total_items'] / $customer['total_orders'];

        $customer['name'] 	= $order['name'];
        $customer['city'] 	= $order['city'];
        $customer['state']	= $order['state'];

        echo "<br>";
        print_r($customer);

        $isql = "
        INSERT INTO customer_flat
        (email, total_spend, total_orders, total_items, avg_items, avg_spend, first_order_date, last_order_date, last_updated, last_order_id, first_order_id, name, city, state)
        VALUES 
        ('" . $email . "', " . $customer['total_spend']  . ", " . $customer['total_orders']  . ", " . $customer['total_items']  . ", " . $customer['avg_items']  . ", 
        	" . $customer['avg_spend']  . ", '" . $customer['first_order_date']  . "', '" . $customer['last_order_date']  . "', NOW(), 
        	'" . $customer['last_order_id'] . "', '" . $customer['first_order_id'] . "', '" . $customer['name'] . "', '" . $customer['city'] . "', '" . $customer['state'] . "'
        	)
        ";
        $iresult = mysqli_query($con,$isql);
        $customer_id = mysqli_insert_id($con);

        echo "<br>Inserted customer: " . $customer_id;

	}


	echo "<br>#########<br>";
	$last_id = $order['oid'];
	echo "<br>Order id was " . $last_id;

}

if($last_id > 0){

	$isql = "
	INSERT INTO job_log
	(process, date_run, last_id, notes)
	VALUES
	('". $process ."',NOW(),". $last_id .",'" . $notes . "')
	";
	$result = mysqli_query($con,$isql);
	$log_id = mysqli_insert_id($con);

	if($log_id > 0){

		echo "<br>Inserted into log: " . $log_id;
		echo "<br><a href='jobs.php'>Back to jobs page</a>";

	} else {

		echo "<br>Error inserting into log";

	}

} else {

	echo "<br>We didnt process anything.";	

}


?>