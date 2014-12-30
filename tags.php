<?php

require_once('includes/opener.php');

if(isset($_GET['tag']))
{
  $filter_tag = $_GET['tag'];
} else {
  $filter_tag = null;
}

// there is a filter
if($filter_tag){

    $tagged_sql = "SELECT master_order_id, order_id, created_on, tag, email, O.id FROM order_tag_x_order OTXO INNER JOIN `order` O ON O.id = OTXO.order_id WHERE tag = '" . $filter_tag . "' ORDER BY order_id DESC";
    $tagged_orders = mysqli_query($con,$tagged_sql);

    $tagged_count = mysqli_num_rows($tagged_orders);

} 

  $sql = "SELECT tag, count(id) AS tag_count FROM order_tag_x_order GROUP BY tag ORDER BY tag_count DESC";
  $alltags_results = mysqli_query($con,$sql);



?>

<style>
.alltags {
  float:left; cursor: pointer; font-size:10px; padding:4px; background:#ccc; border-radius:4px; margin:2px;
}

.alltags:hover {background:#a0f6ac;}

hr {margin:8px 0px;}
</style>


<div class="row">
  <div class="large-12 columns">
    <div id="alltags_holder" style="margin-top:10px;">
   
    
      <? while($alltags = mysqli_fetch_array($alltags_results)){ 
          //$width = 120 + ($alltags['tag_count'] * 1.5);
          $width = 120 ;
        ?>
      <a href="tags.php?tag=<? echo $alltags['tag']; ?>" class="alltags" style="width:<? echo $width; ?>px;" id="tag_<? echo $alltags['tag']; ?>" data-tag="<? echo $alltags['tag']; ?>"><? echo $alltags['tag_count']; ?> | <? echo $alltags['tag']; ?></a>
      <? } ?>
    </div>
   
  </div>
  
</div>
<? if($filter_tag){ ?>
<hr><div class="row">
  <div class="large-8 columns">
  <h4><? echo $tagged_count; ?> Orders matching '<? echo $filter_tag; ?>'</h4>
  <table>
  <thead>
    <tr>
      <th>Order ID</th>
      <th>email</th>
      <th>order date</th>
    </tr>
  </thead>
  <tbody>
  <? while($orders = mysqli_fetch_array($tagged_orders)){ ?>
    <tr>
    <td><a href="order.php?master_order_id=<? echo $orders['master_order_id']; ?>"><? echo $orders['order_id']; ?></a></td>
    <td><? echo $orders['email']; ?></td>
    <td><? echo $orders['created_on']; ?></td>
    </tr>
  <? } ?>
  </tbody>
  </table>
  </div>
  <div class="large-4 columns">
  <textarea style="width:100%; height:200px; margin:10px; padding:10px;"><? 
mysqli_data_seek($tagged_orders,0);
  while($orders = mysqli_fetch_array($tagged_orders)){ echo $orders['email']; echo "\n"; } ?>
  </textarea>
  </div>


</div>
<? } ?>

<hr>
<? echo $tagged_sql; ?><br>    
<?php require_once('includes/closer.php'); ?>
