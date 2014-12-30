<?php

require_once('includes/opener.php');

if(isset($_GET['master_order_id']))
{
  $master_order_id = $_GET['master_order_id'];
} else {
  $master_order_id = 0;
}

if($master_order_id > 0){

  $order = orderDetailsById($con,$master_order_id);

  $customer = getCustomerInfoByEmail($con,$order['email']);

  $next_id = getNextOrderId($con,$master_order_id);
  $prev_id = getPreviousOrderId($con,$master_order_id);

  if($order){

    $sql = "SELECT * FROM order_tag_x_order WHERE order_id = " . $order['id'] . " ORDER BY tag ASC";
    $tag_result = mysqli_query($con,$sql);

    $alltags_results = getAllTags($con);

  }

}


?>




<? if ($order){  ?>
<div class="row">
  <div class="large-6 columns">
    <h3>Order <? echo $master_order_id; ?> <small>
        <? if ($next_id){ ?>
          <a href="order.php?master_order_id=<? echo $next_id; ?>">&laquo; (<? echo $next_id; ?>)</a>

         <? } ?>

<? if ($prev_id){ ?>
          <a href="order.php?master_order_id=<? echo $prev_id; ?>">(<? echo $prev_id; ?>) &raquo;</a>

         <? } ?>

    </small></h3>
  </div>
</div>


<div class="row">
    <div class="large-8 columns">
        <div class="panel callout">
          <div class="row">
            <div class="large-3 columns">
              <h3>$<? echo $order['total']/100; ?></h3>
              <p>
              <? echo $order['total_items']; ?> items over
              <? echo $order['total_designs']; ?> designs.<br>
              via <? echo $order['order_type']; ?>.
              </p>
              <hr>
              <p style="font-family:'Inconsolata'; font-size:10px;">
                
                ordered: <? echo substr($order['created_on'],0,10); ?><br>
                batched: <? echo substr($order['batch_date'],0,10); ?><br>
                shipped: <? echo substr($order['ship_email_sent_on'],0,10); ?><br>

              </p>
              <p>
              <a href="http://www.supportourribbons.com/order/view?id=<? echo $order['id']; ?>" target="_blank">View in SOR Admin</a>
              </p>
            </div>
<? $all_words = array(); ?>
            <div class="large-9 columns">
              <ul class="small-block-grid-3">
                <? foreach ($order['cart_items'] as $item){ 


// get suggested tags
// put all words into an array

$text1 = explode(" ",$item['text1']);
$text2 = explode(" ",$item['text2']);

$all_text = array_merge($text1,$text2);
 
foreach ($all_text as $word) {
  if(strlen($word)){
    $all_words[] = strtolower(str_replace("'", "", $word));
  } 
}

//print_r($all_words);

  

                  ?>
                  <li>
                  
                    <h4><? echo $item['quantity']; ?>x</h4>
                    <a title="view details" href="item.php?id=<? echo $item['maker4_item_id']; ?>"><img src="http://www.supportourribbons.com/custom-magnet-image/<? echo $item['maker4_item_id'] ?>-170px.png" border="0"></a>
                    <br><? echo $item['text1']; ?> <? if ($item['text2']){ ?> / <? echo $item['text2']; ?><? } ?>
                    <br><a title="Permalink on SOR" href="http://www.supportourribbons.com/custom-oval-magnet-sticker/<? echo $item['maker4_item_id']; ?>" target="_blank">#</a>
                    <a title="view details" href="item.php?id=<? echo $item['maker4_item_id']; ?>">&Theta;</a>
                  </li>
                <? } // end loop items ?>
              </ul>
            </div>
          </div>  



        </div>
      
    </div>

    
    <div class="large-4 columns">
    <div class="panel">
      <h3><? echo $order['email']; ?></h3>
      <p>
      <strong>Billed to:</strong><br>
      <? echo ucwords($order['billing_address']['name']); ?> in 
      <? echo ucwords($order['billing_address']['city']); ?>, <? echo $order['billing_address']['state']; ?> on <? echo $order['order_date2']; ?> <br>
      
      <strong>Ship<? if ($order['ship_date']){ ?>ped<? } ?> to:</strong><br>
      <? echo ucwords($order['shipping_address']['name']); ?> in 
      <? echo ucwords($order['shipping_address']['city']); ?>, <? echo $order['shipping_address']['state']; ?><? if ($order['ship_date']){ ?> on <? echo $order['ship_date']; ?> <? } ?><br>
</p>
     
    <hr>
    <h5><? echo $customer['num_orders']; ?> Orders = $<? echo $customer['lifetime_spend']/100; ?></h5>

    <? 
      foreach ($customer['orders'] as $c_order) {
    ?>    
      <? if($c_order['master_order_id'] == $order['master_order_id']){ echo "&laquo; "; } ?><a href="order.php?master_order_id=<? echo $c_order['master_order_id']; ?>" ><? echo $c_order['master_order_id']; ?> on <? echo $c_order['created_on']; ?></a><br>
    <?
      }
    ?>

    </div>

    </div>
    
</div>

<? } else { ?>
No order found.
<? } ?>

<div class="row">
  <div class="large-2 columns">
    
      Add a new tag:
      <input type="text" name="tag" id="tagText">
      <input type="hidden" name="order_id" value="<? echo $order['id']; ?>">
      <input type="hidden" name="action" value="addTag">
      <a href="javascript:void(0);" id="tagSubmit" class="button small radius">+ Add Tag</a>
      <br><br><br>
      <? if($order['tag_dismissed'] != 1){ ?>
      <a href="javascript:void(0);" id="tagDismiss" class="button small radius secondary">Dismiss</a>
      <? } ?>
  </div>
  <div class="large-7 columns">
    <? if ($next_id){ ?>
          <a href="order.php?master_order_id=<? echo $next_id; ?>">&laquo; (<? echo $next_id; ?>)</a>

         <? } ?>

<? if ($prev_id){ ?>
          <a href="order.php?master_order_id=<? echo $prev_id; ?>">(<? echo $prev_id; ?>) &raquo;</a>

         <? } ?>
    <hr>
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
    <div class="alltags" id="tag_marines" data-tag="marines">marines</div>
    <div class="alltags" id="tag_navy" data-tag="navy">navy</div>
    <div class="alltags" id="tag_air-force" data-tag="air-force">air-force</div>
    <hr>
    <? foreach($all_words as $word){ ?>    
      <div class="alltags" id="tag_<? echo $word; ?>" data-tag="<? echo $word; ?>"><? echo $word; ?></div>
    <? } ?>
    <hr>
      <? while($alltags = mysqli_fetch_array($alltags_results)){ ?>
      <div class="alltags" id="tag_<? echo $alltags['tag']; ?>" data-tag="<? echo $alltags['tag']; ?>"><? echo $alltags['tag']; ?></div>
      <? } ?>
    </div>
    

  </div>
  <div id="tagHolder" class="large-3 columns">
   <strong>Tags:</strong><br>
   <ul id="tagList">
   <? while($tags = mysqli_fetch_array($tag_result)){ ?>
      <li id="tag_<? echo $tags['id']; ?>">[ <a href="javascript:void(0);" class="tagDelete" data-id="<? echo $tags['id']; ?>" id="deleteTag_<? echo $tags['id']; ?>">X</a> ] <? echo $tags['tag']; ?></li>
   <? } ?>
   </ul>
  </div>
  

</div>
<script>

$('.alltags').click(function(){

  id = this.id;
  tag = $('#'+id).data('tag');

  data_str = "action=addTag&order_id=<? echo $order['id']; ?>&tag=" + tag;
  doApiCall(data_str,addTagCallback);

});

$('#tagSubmit').click(function(){

  addATag();

});


$('#tagDismiss').click(function(){

  dismissTag();

});

function dismissTag(){

    data_str = "action=dismissTag&order_id=" + <? echo $order['id']; ?>;
    doApiCall(data_str,dismissTagCallback);

}

function dismissTagCallback(response){
  $('#tagDismiss').fadeOut("slow");
}

$(document).keypress(function(e) {
    if(e.which == 13) {
        addATag();
    }
});

function addATag(){
    var tag = $('#tagText').val();
  if(tag){
  
    tag = tag.replace(/ /g,'-');

    $('#tagText').val("");

    data_str = "action=addTag&order_id=<? echo $order['id']; ?>&tag=" + tag;
    //alert(data_str);

    doApiCall(data_str,addTagCallback);

  } else {
    alert("no tag found");
  }
}

function addTagCallback(response){
  //alert("callbackCalled!");
  console.log(response);

  $('#tagHolder ul').append("<li id='tag_" + response['data']['new_id'] + "'>* " + response['data']['new_tag'] + "</li>");

}

$('.tagDelete').on("click", function(){

  id = this.id;
  tag_id = $('#' + id).data('id');
  //alert(tag_id);

  if(confirm("Delete this tag?")){
    data_str = "action=deleteTag&tag_id=" + tag_id;
    doApiCall(data_str,deleteTagCallback);

  } else {

  }

});

function deleteTagCallback(response){
  
  console.log(response);
  $('#tag_' + response['data']['deleted_tag_id']).fadeOut('slow');

}


</script>
    
<?php require_once('includes/closer.php'); ?>
