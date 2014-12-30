<?php

require_once('includes/opener.php');

$qstring = $_SERVER['QUERY_STRING'];
$direction = "asc";
$opposite_dir = "desc";
$num_customers = 0;

if(isset($_GET['images']))
{
  $images = $_GET['images'];
} else {
  $images = 0;
}

if(isset($_GET['dir'])){
  if($_GET['dir'] == "desc")
  {
    $direction = $_GET['dir'];
    $opposite_dir = "asc";
  } else {
    $direction = $_GET['dir'];
    $opposite_dir = "desc";
  }
}
if(isset($_GET['orderby']))
{
  $orderby = $_GET['orderby'];
} else {
  $orderby = "last_updated";
  $direction = "desc";
  $opposite_dir = "asc";
}


$where = '';


if(isset($_GET['last_order_date_start']) && $_GET['last_order_date_start'] != '')
{
  $last_order_date_start = $_GET['last_order_date_start'];

  $date_parts = explode("/", $last_order_date_start);
  $check_date = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];

  $where = $where . " AND last_order_date > '" . $check_date . "'";
} else {
  $last_order_date_start = '';
}

if(isset($_GET['last_order_date_end']) && $_GET['last_order_date_end'] != '')
{
  $last_order_date_end = $_GET['last_order_date_end'];

  $date_parts = explode("/", $last_order_date_end);
  $check_date = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];

  $where = $where . " AND last_order_date < '" . $check_date . "'";
} else {
  $last_order_date_end = '';
}

if(isset($_GET['total_spend']))
{
  $total_spend = intval($_GET['total_spend']);
  $where = $where . " AND C.total_spend > " . $total_spend * 100;
} else {
  $total_spend = '';
}

if(isset($_GET['avg_spend']))
{
  $avg_spend = intval($_GET['avg_spend']);
  $where = $where . " AND C.avg_spend > " . $avg_spend * 100;
} else {
  $avg_spend = '';
}

if(isset($_GET['total_orders']))
{
  $total_orders = intval($_GET['total_orders']);
  $where = $where . " AND C.total_orders > " . $total_orders;
} else {
  $total_orders = '';
}

if(isset($_GET['total_items']))
{
  $total_items = intval($_GET['total_items']);
  $where = $where . " AND C.total_items > " . $total_items;
} else {
  $total_items = '';
}

if(isset($_GET['avg_items']))
{
  $avg_items = intval($_GET['avg_items']);
  $where = $where . " AND C.avg_items > " . $avg_items;
} else {
  $avg_items = '';
}

if(isset($_GET['repeat_customer']))
{
  $repeat_customer = $_GET['repeat_customer'];
  $where = $where . " AND C.became_repeat IS NOT NULL";
} else {
  $repeat_customer = 0;
}


$query = "
SELECT * FROM
`customer_flat` C
WHERE C.id > 0 
" . $where . "
"; 
$result_total = mysqli_query($con,$query);
$total_rows = mysqli_num_rows($result_total);
$num_customers = $total_rows;

$base_url = 'index.php';    //Provide location of you index file  
$per_page = 50;             //number of results to shown per page 
$num_links = 7;             // how many links you want to show
$total_rows = $total_rows; 
$cur_page = 1;              // set default current page to 1

    if(isset($_GET['page']))
    {
      $cur_page = $_GET['page'];
      $cur_page = ($cur_page < 1)? 1 : $cur_page;            //if page no. in url is less then 1 or -ve
    }

  $offset = ($cur_page-1)*$per_page;                //setting offset
   
    $pages = ceil($total_rows/$per_page);              // no of page to be created

    $start = (($cur_page - $num_links) > 0) ? ($cur_page - ($num_links - 1)) : 1;
    $end   = (($cur_page + $num_links) < $pages) ? ($cur_page + $num_links) : $pages;


$sql = "
SELECT * FROM customer_flat C
WHERE C.id > 0
" . $where . "
ORDER BY " . $orderby . " " . $direction . "
LIMIT " . $offset . ", " . $per_page . "
";

$display_sql = $sql;

$result = mysqli_query($con,$sql);



?>
<div class="row">
  <div class="large-5 columns" style="padding-top:10px;">
    Found <? echo $num_customers; ?> customers.
  </div>
  <div class="large-1 columns right">
    <a href="#" data-reveal-id="myFilter" class="button round tiny" style="margin-top:5px;">Filters</a>
  </div>
  <div class="large-4 columns right" style="padding-top:10px;">
    <?php require_once('includes/paging.php'); ?>
  </div>

</div>


<div class="row">
  <div class="large-12 columns">
<? if($num_customers > 0){ ?>
  <table>
    <thead>
      <th><a href="customers.php?<? echo $qstring; ?>&x=x&orderby=id&dir=<? echo $opposite_dir; ?>">#</a></th>
      <th><a href="customers.php?<? echo $qstring; ?>&x=x&orderby=email&dir=<? echo $opposite_dir; ?>">email</a></th>
      <th><a href="customers.php?<? echo $qstring; ?>&x=x&orderby=total_spend&dir=<? echo $opposite_dir; ?>">spend $</a></th>
      <th><a href="customers.php?<? echo $qstring; ?>&x=x&orderby=total_orders&dir=<? echo $opposite_dir; ?>"># orders</a></th>
      <th><a href="customers.php?<? echo $qstring; ?>&x=x&orderby=total_items&dir=<? echo $opposite_dir; ?>"># items</a></th>
      <th><a href="customers.php?<? echo $qstring; ?>&x=x&orderby=avg_spend&dir=<? echo $opposite_dir; ?>">avg $</a></th>
      <th><a href="customers.php?<? echo $qstring; ?>&x=x&orderby=avg_items&dir=<? echo $opposite_dir; ?>">avg items</a></th>
      <th><a href="customers.php?<? echo $qstring; ?>&x=x&orderby=first_order_date&dir=<? echo $opposite_dir; ?>">first order</a></th>
      <th><a href="customers.php?<? echo $qstring; ?>&x=x&orderby=last_order_date&dir=<? echo $opposite_dir; ?>">last order</a></th>
      <th><a href="customers.php?<? echo $qstring; ?>&x=x&orderby=became_repeat&dir=<? echo $opposite_dir; ?>">repeat?</a></th>
      <th><a href="customers.php?<? echo $qstring; ?>&x=x&orderby=name&dir=<? echo $opposite_dir; ?>">name</a></th>
      <th><a href="customers.php?<? echo $qstring; ?>&x=x&orderby=city&dir=<? echo $opposite_dir; ?>">city</a></th>
      <th><a href="customers.php?<? echo $qstring; ?>&x=x&orderby=state&dir=<? echo $opposite_dir; ?>">state</a></th>
    </thead> 
    <tbody>
    <?php
    
      while ($o = mysqli_fetch_array($result)) {

       ?>
       <tr>
        <td valign="top"><? echo $o['id']; ?></td>
        <td nowrap valign="top"><a href="customer.php?email=<? echo $o['email']; ?>"><? echo $o['email']; ?></a></td>
        <td valign="top" class="text-right">$<? echo number_format((float)($o['total_spend']/100), 2, '.', ''); ?></td>
        <td valign="top" class="text-right"><? echo $o['total_orders']; ?></td>
        <td valign="top" class="text-right"><? echo $o['total_items']; ?></td>
        <td valign="top" class="text-right">$<? echo number_format((float)($o['avg_spend']/100), 2, '.', ''); ?></td>
        <td valign="top" class="text-right"><? echo number_format((float)($o['avg_items']), 1, '.', ''); ?></td>
        <td><a href="order.php?master_order_id=<? echo $o['first_order_id']; ?>"><? echo $o['first_order_id']; ?> / <? echo substr($o['first_order_date'],0,10); ?></a></td>
        <td><a href="order.php?master_order_id=<? echo $o['last_order_id']; ?>"><? echo $o['last_order_id']; ?> / <? echo substr($o['last_order_date'],0,10); ?></a></td>
        <td><? if($o['became_repeat']){ echo substr($o['became_repeat'],0,10); } else { echo "No"; } ?></td>
        <td valign="top"><? echo $o['name']; ?></td>
        <td valign="top"><? echo $o['city']; ?></td>
        <td valign="top"><? echo $o['state']; ?></td>
       </tr>
    
    <?php    
      } // end while
    } else {

      echo "No customers found with those filters.";

    } //end if num customers
    ?>

    </tbody> 
  </table>



<hr>
<?php require('includes/paging.php'); ?>

<hr>
<? echo $display_sql; ?>

<textarea style="margin-top:20px; height:100px;"><? mysqli_data_seek($result,0);  while ($o = mysqli_fetch_array($result)) {?> 
<? echo $o['email']; ?><? } ?></textarea>

  </div>
</div>
<div id="myFilter" class="reveal-modal" data-reveal>
  <h2>Filters</h2>
  
  <form action="customers.php" method="GET">
  
  <div class="row">
    <div class="large-4 columns" style="border:1px solid #ccc;padding-top:10px;">
      
      By last order date, between <input type="text" name="last_order_date_start" class="datepicker" value="<? echo $last_order_date_start; ?>" > and <input type="text" name="last_order_date_end" value="<? echo $last_order_date_end; ?>" class="datepicker">

    </div>
    <!--<div class="large-4 columns" style="border:1px solid #ccc;padding-top:10px;">
      
      By first order date, between <input type="text" name="first_order_date_start" class="datepicker" value="<? echo $first_order_date_start; ?>" > and <input type="text" name="first_order_date_end" value="<? echo $first_order_date_end; ?>" class="datepicker">

    </div>
    <div class="large-4 columns">
      
    

    </div>-->
  </div>
<hr>
  <div class="row">
    <div class="large-4 columns">
      
    Total spend over:
    <input type="text" name="total_spend" value="<? echo $total_spend; ?>">


    </div>
    <div class="large-4 columns">
      
    Total items over:
    <input type="text" name="total_items" value="<? echo $total_items; ?>">
      

    </div>
    <div class="large-4 columns">
      
    Total orders over:
    <input type="text" name="total_orders" value="<? echo $total_orders; ?>">
    

    </div>
  </div>  <hr>
  <div class="row">
    <div class="large-4 columns">
      
    Avg spend over:
    <input type="text" name="avg_spend" value="<? echo $avg_spend; ?>">



    </div>
    <div class="large-4 columns">
    Avg items over:
    <input type="text" name="avg_items" value="<? echo $avg_items; ?>">
      
      

    </div>
    <div class="large-4 columns">
    Repeat customer?
     <input type="checkbox" name="repeat_customer" value="1" <? if($repeat_customer == 1){ echo " checked "; } ?> >
      

    </div>
  </div>    

  
  

<input type="submit" value="GO" class="button radius">
  </form>

  <a class="close-reveal-modal">&#215;</a>
</div>

  <script>
  
    $( ".datepicker" ).datepicker();
  
  </script>

    
<?php require_once('includes/closer.php'); ?>
