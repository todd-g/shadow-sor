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


$base_url = 'untagged_orders.php';    //Provide location of you index file  
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

SELECT O.*, C.*, DATE_FORMAT(O.created_on,'%c-%e-%y %h:%i %p') AS created_date FROM
`order` O
INNER JOIN cart C ON C.id = O.cart_id
WHERE O.invalid = 0
" . $where . "
AND O.id NOT IN (
  SELECT order_id FROM order_tag_x_order
)
AND O.tag_dismissed != 1
GROUP BY O.master_order_id
ORDER BY O.created_on DESC
";

$display_sql = $sql;

$result = mysqli_query($con,$sql);
$untagged_total = mysqli_num_rows($result);

/*
        $qsql = "SELECT sum(quantity) AS qty FROM cart_item CI INNER JOIN maker4_item M on M.id = CI.maker4_item_id  WHERE CI.cart_id = " . $o['cart_id'];
        $qresult = mysqli_query($con,$qsql);
        $qrow = mysqli_fetch_array($qresult);

        $osql = "SELECT CI.maker4_item_id, M.text1 FROM cart_item CI INNER JOIN maker4_item M on M.id = CI.maker4_item_id  WHERE CI.cart_id = " . $o['cart_id'];
        $oresult = mysqli_query($con,$osql);

*/


$alltags_results = getAllTags($con);

?>
<div class="row">
  <div class="large-3 columns">
    <h4>Found <? echo $untagged_total; ?> orders</h4>
  </div>
</div>

<div class="row">
<div class="large-6">
<div id="order_id_holder" style="display:none;"></div>


</div>

<div class="large-6">
<style>
      .alltags {
        float:left; cursor: pointer; font-size:12px; padding:7px; background:#ccc; border-radius:4px; margin:4px;
      }

      .alltags:hover {background:#a0f6ac;}

      hr {margin:8px 0px;}
    </style>
    <div id="alltags_holder">
    <div class="alltags" id="tag_military" data-tag="military">military</div>
    <div class="alltags" id="tag_causes" data-tag="causes">causes</div>
    <div class="alltags" id="tag_memorial" data-tag="memorial" style="margin-right:50px;">memorial</div>
    
    <div class="alltags" id="tag_army" data-tag="army">army</div>
    <div class="alltags" id="tag_marines" data-tag="miarines">marines</div>
    <div class="alltags" id="tag_navy" data-tag="navy">navy</div>
    <div class="alltags" id="tag_air-force" data-tag="air-force">air-force</div>
    <hr>
      <? while($alltags = mysqli_fetch_array($alltags_results)){ ?>
      <div class="alltags" id="tag_<? echo $alltags['tag']; ?>" data-tag="<? echo $alltags['tag']; ?>"><? echo $alltags['tag']; ?></div>
      <? } ?>
</div>

</div>

<div class="row">
  <div class="large-12 columns">

<hr>
<? echo $display_sql; ?>

  </div>
</div>
<script>

$('.alltags').click(function(){

  id = this.id;
  tag = $('#'+id).data('tag');

  order_id = $('#order_id_holder').html();

  data_str = "action=addTag&order_id=" + order_id + "&tag=" + tag;
  doApiCall(data_str,addTagCallback);

});




</script
    
<?php require_once('includes/closer.php'); ?>
