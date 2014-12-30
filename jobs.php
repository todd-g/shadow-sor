<?php

require_once('includes/opener.php');

$sql = "SELECT * FROM job_log ORDER BY date_run DESC LIMIT 0,50";
$result = mysqli_query($con,$sql);


?>
<div class="row">
  <div class="large-2 columns">
    <h2>Jobs</h2>
  </div>

</div>


<div class="row">
  <div class="large-3 columns">


  <h4>Jobs</h4>
	<a href="proc_customers.php">&raquo; Process Customers</a><br>
  <a href="untagged_orders.php">&raquo; Untagged Orders</a>


  </div>
  <div class="large-9 columns">

 	<h4>Process Log</h4>
<table>
<thead>
<tr>
<th>id</th>
<th>process</th>
<th>date run</th>
<th>last id processed</th>
<th>notes</th>
</thead>
<tbody>
<? while($row = mysqli_fetch_array($result)){ ?>

<tr>
<td><? echo $row['id']; ?></td>
<td><? echo $row['process']; ?></td>
<td><? echo $row['date_run']; ?></td>
<td><? echo $row['last_id']; ?></td>
<td><? echo $row['notes']; ?></td>
</tr>
	

<? } ?>
</tbody>
</table>
	



  </div>
</div>

    
<?php require_once('includes/closer.php'); ?>
