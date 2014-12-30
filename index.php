<?php

require_once('includes/opener.php');

$qstring = $_SERVER['QUERY_STRING'];

if(isset($_GET['images']))
{
  $images = $_GET['images'];
} else {
  $images = 0;
}

$where = '';

if(isset($_GET['date_start']) && $_GET['date_start'] != '')
{
  $date_start = $_GET['date_start'];

  $date_parts = explode("/", $date_start);
  $check_date = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];

  $where = $where . " AND O.created_on > '" . $check_date . "'";
} else {
  $date_start = '';
}

if(isset($_GET['date_end']) && $_GET['date_end'] != '')
{
  $date_end = $_GET['date_end'];

  $date_parts = explode("/", $date_end);
  $check_date = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];

  $where = $where . " AND O.created_on < '" . $check_date . "'";
} else {
  $date_end = '';
}

if(isset($_GET['total_price']))
{
  $total_price = intval($_GET['total_price']);
  $where = $where . " AND C.total_price > " . $total_price * 100;
} else {
  $total_price = '';
}


$query = "SELECT * FROM
`order` O
INNER JOIN cart C
ON C.id = O.cart_id
WHERE O.invalid = 0
" . $where . "
GROUP BY O.master_order_id
"; 
$result_total = mysqli_query($con,$query);
$total_rows = mysqli_num_rows($result_total);


$base_url = 'index.php';    //Provide location of you index file  
$per_page = 50;                           //number of results to shown per page 
$num_links = 7;                           // how many links you want to show
$total_rows = $total_rows; 
$cur_page = 1;                           // set default current page to 1

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

SELECT O.*, O.id AS order_id, C.*, DATE_FORMAT(O.created_on,'%c-%e-%y %h:%i %p') AS created_date FROM
`order` O
INNER JOIN cart C ON C.id = O.cart_id
WHERE O.invalid = 0
" . $where . "
GROUP BY O.master_order_id
ORDER BY O.created_on DESC
LIMIT " . $offset . ", " . $per_page . "

";

$display_sql = $sql;

$result = mysqli_query($con,$sql);



?>
<div class="row">
  <div class="large-3 columns">
    <h4>Found <? echo $total_rows; ?> orders</h4>
  </div>
  <div class="large-8 columns" style="padding-top:10px;">
    <?php require_once('includes/paging.php'); ?>
  </div>
  <div class="large-1 columns">
    <a href="#" data-reveal-id="myFilter" class="button round tiny" style="margin-top:5px;">Filters</a>
  </div>
</div>


<div class="row">
  <div class="large-12 columns">

  <table>
    <thead>
      <th>#</th>
      <th>created_on</th>
      <th>email</th>
      <th>total</th>
      <th>items</th>
      <th></th>
      <th>tags</th>
    </thead> 
    <tbody>
    <?php

      while ($o = mysqli_fetch_array($result)) {

        $qsql = "SELECT sum(quantity) AS qty FROM cart_item CI INNER JOIN maker4_item M on M.id = CI.maker4_item_id  WHERE CI.cart_id = " . $o['cart_id'];
        $qresult = mysqli_query($con,$qsql);
        $qrow = mysqli_fetch_array($qresult);

        $osql = "SELECT CI.maker4_item_id, M.text1 FROM cart_item CI INNER JOIN maker4_item M on M.id = CI.maker4_item_id  WHERE CI.cart_id = " . $o['cart_id'];
        $oresult = mysqli_query($con,$osql);

        $tsql = "SELECT tag FROM order_tag_x_order WHERE order_id = " . $o['order_id'];
        //echo $tsql;
        $tresult = mysqli_query($con,$tsql);

       ?>
       <tr>
        <td valign="top"><a href="order.php?master_order_id=<? echo $o['master_order_id']; ?>"><? echo $o['master_order_id']; ?></a></td>
        <td nowrap valign="top"><? echo $o['created_date']; ?></td>
        <td valign="top"><a href="customer.php?email=<? echo $o['email']; ?>"><? echo $o['email']; ?></a></td>
        <td  valign="top" class="text-right">$<? echo $o['total_price']/100; ?></td>
        <td  valign="top" class="text-right"><? echo $qrow['qty']; ?></td>
        <td>
        <? while ($ci = mysqli_fetch_array($oresult)){ ?>
          <a href="item.php?maker4_item_id=<? echo $ci['maker4_item_id']; ?>">
          <? if ($images == 1){ ?>
          <img src="http://www.supportourribbons.com/custom-magnet-image/<? echo $ci['maker4_item_id'] ?>-100px.png" border="0">
          <? } else {
            echo $ci['maker4_item_id'];
            echo " | "; 
           } ?>
           </a>

        <? } //end ci while ?>
        </td>
        <td>
            <? while($t = mysqli_fetch_array($tresult)){ ?>
              <a href="tags.php?tag=<? echo $t['tag']; ?>"><? echo $t['tag']; ?></a> |
            <? } ?>
        </td>
        </tr>
    <?php    
      }

    ?>

    </tbody> 
  </table>



<hr>
<?php require('includes/paging.php'); ?>

<hr>
<? echo $display_sql; ?>

  </div>
</div>
<div id="myFilter" class="reveal-modal" data-reveal>
  <h2>Filters</h2>
  
  <form action="index.php" method="GET">
  
  <div class="row">
    <div class="large-4 columns">
      
      <input type="checkbox" name="images" value="1" <? if($images == 1){ echo " checked "; } ?> > Show product images?

    </div>
    <div class="large-4 columns">
      
      By date, between <input type="text" name="date_start" class="datepicker" value="<? echo $date_start; ?>" > and <input type="text" name="date_end" value="<? echo $date_end; ?>" class="datepicker">

    </div>
    <div class="large-4 columns">
      
    Order $ amount greater than
    <input type="text" name="total_price" value="<? echo $total_price; ?>">

    </div>
  </div>

  <div class="row">
    <div class="large-4 columns">
      
      

    </div>
    <div class="large-4 columns">
      
      

    </div>
    <div class="large-4 columns">
      
    Quantity greater than
    <input type="text" name="total_qty" value="<? echo $total_qty; ?>">

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
