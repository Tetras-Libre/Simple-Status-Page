<?php

function dieForbiden() {
     header("HTTP/1.1 403 Forbidden");
     echo "<h1>Permission denied</h1>";
     die();
}

if (!isset($_GET['code'])) {
	dieForbiden();
}
$code = $_GET['code'];
require_once('codeToRegexp.php');
if (!$code || !array_key_exists($code, $codeToRegexp)) {
	dieForbiden();
}
$regex = $codeToRegexp[$code];

?>
<!doctype html>

<html lang="fr" class="h-100">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>État des services</title>
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
   <style>
   .container {
     width: auto;
     max-width: 950px;
     padding: 0 15px;
   }

   .footer {
     background-color: #f5f5f5;
   }
   </style>
  </head>
  <body class="d-flex flex-column h-100">

<main role="main" class="flex-shrink-0">
  <div class="container">
    <h1 class="mt-5">État des services</h1>
    <div class="alert alert-secondary" role="alert">
<p>
Bienvenue sur la page public de status des serveur.
</p>
<p>
Vous trouverez ci-dessous la liste des serveurs surveillés.
</p>

</div>

<?php
require_once('../config.php');
require_once('../src/psm/Service/Database.php');


$host = constant("PSM_DB_HOST");
$port = constant("PSM_DB_PORT");
$db = constant("PSM_DB_NAME");
$dbuser = constant("PSM_DB_USER");
$passw = constant("PSM_DB_PASS");
$dbpf = constant("PSM_DB_PREFIX");
$port = constant("PSM_DB_PORT");

$db = new psm\Service\Database($host, $dbuser, $passw, $db, $port);


$sql = "SELECT * FROM ". $dbpf ."servers WHERE active='yes' AND label REGEXP '".$regex."';";
$result = $db->query($sql);

echo "<table class='table table-dark'>
<tr>
<th>Service</th>
<th>État</th>
<th>Dernière verification</th>
<th>Dernière erreur</th>
</tr>";

foreach($result as $row)
{
if ($row['status'] === "off") {
 $statusx = "Injoignable";
 $statusy = "danger";
 $lonline = "Actuellement injoignable. Dernier contact: " .$row['last_online'];
} else {
 $statusx = "Opérationnel";
 $statusy = "success";
 $lonline = "";
}
echo "<tr class='bg-". $statusy ."'>";
echo "<td>" . $row['label'] . "</td>";
echo "<td>" . $statusx . "</td>";
echo "<td>" . $row['last_check'] . "</td>";
if ($row['last_offline'] === null || strlen($row['last_offline']) < 1 || $row['status'] === "off") {
 if ($row['status'] === "off") {
 echo "<td>" . $lonline . "</td>";
 } else {
 echo "<td>Jamais</td>";
 }
} else {
 echo "<td>" . $row['last_offline'] . " (". $row['last_offline_duration'] .")</td>";
}
echo "</tr>";
}
echo "</table>";

?>
<button type="button" onClick="window.location.reload();" class="btn btn-outline-secondary">Actualiser</button> <?php echo date('m/d/Y h:i:s a', time()); ?>

</main>

<footer class="footer mt-auto py-3">
  <div class="container">
    <span class="text-muted"><a href="https://github.com/Tetras-Libre/Simple-Status-Page" target="_blank">Simple Status Page</a> for <a href="https://github.com/phpservermon/phpservermon/" target="_blank">PHP Server Monitor</a>.</span>
  </div>
</footer>
</body>
</html>
