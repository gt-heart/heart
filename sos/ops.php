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
      <?= $_COOKIE['HeartMsg'] ?? "Alarme Falso" ?>
      <?= $_COOKIE['HeartError'] ?? "Calma, foi um erro falso!" ?>
    </section>
  </body>
</html>
