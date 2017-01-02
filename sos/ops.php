<?php require_once(__DIR__.'/../pulse.php'); ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>ERROR</title>
  </head>
  <body>
    <h1>
      The heart is not working right. :/
    </h1>
    <section>
      <?php Session::msg(); ?>
      <?php Session::errorPrint(); ?>
    </section>
  </body>
</html>
