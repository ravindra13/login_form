<?php 
 $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/../';
          header('Location: ' . $home_url);
?>