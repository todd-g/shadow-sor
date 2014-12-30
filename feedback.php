<?php

require_once('includes/opener.php');



$sql = "

SELECT * FROM testimonial
ORDER BY created_on DESC

";
$result = mysqli_query($con,$sql);



?>
<div class="row">
  <div class="large-2 columns">
    <h2>Feedback</h2>
  </div>

</div>


<div class="row">
  <div class="large-12 columns">

  <table>
    <thead>
      <th>#</th>
      <th>created_on</th>
      <th>email</th>
      <th>name</th>
      <th>comment</th>
      
    </thead> 
    <tbody>
    <?php

      while ($o = mysqli_fetch_array($result)) {

        

       ?>
       <tr>
        <td valign="top"><a href="order.php?master_order_id=<? echo $o['master_order_id']; ?>"><? echo $o['master_order_id']; ?></a></td>
        <td valign="top"><? echo $o['created_on']; ?></td>
        <td valign="top"><a href="customer.php?email=<? echo $o['email']; ?>"><? echo $o['email']; ?></a></td>
        <td valign="top" ><? echo $o['name']; ?></td>
        <td valign="top" ><? echo $o['comment']; ?></td>
        
        </tr>
    <?php    
      }

    ?>

    </tbody> 
  </table>





  </div>
</div>
<div id="myFilter" class="reveal-modal" data-reveal>
  <h2>Filters</h2>
  
  <a class="close-reveal-modal">&#215;</a>
</div>
    
<?php require_once('includes/closer.php'); ?>
