<?php

require_once('includes/connection.php');

?>


<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SOR Shadow Admin</title>
    <link rel="stylesheet" href="css/foundation.css" />
    <link rel="stylesheet" href="css/app.css" />
    <link rel="stylesheet" href="css/start/jquery-ui-1.10.4.custom.css" />
    <script src="js/vendor/modernizr.js"></script>

    <script src="js/vendor/jquery.js"></script>
    <script src="js/foundation.min.js"></script>
    <script src="js/jquery-ui-1.10.4.custom.min.js"></script>

    <script src="js/jquery.apiwrapper.js"></script>
   
  </head>
  <body>
    
<nav class="top-bar" data-topbar>
  <ul class="title-area">
    <li class="name">
      <h1><a href="#">SOR SHADOW ADMIN</a></h1>
    </li>
     <!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone -->
    <li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
  </ul>

  <section class="top-bar-section">
    <!-- Left Nav Section -->
    <ul class="left">
      <li><a href="index.php">Orders</a></li>
      <li><a href="customers.php">Customers</a></li>
      <li><a href="tags.php">Tags</a></li>
      <li><a href="feedback.php">Feedback</a></li>
      <li><a href="jobs.php">Jobs</a></li>
    </ul>
  </section>
</nav>
