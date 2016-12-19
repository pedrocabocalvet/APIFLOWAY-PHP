<!DOCTYPE html>
<html>
<head>

	<title>Prueba del api</title>
</head>
<body>

<?php 

include "BD.php";
include "jsonResponse.php";

$api =new BD();
$js = new jsonResponse();
$usuarios = $api->getUsuarios();



$js->setData($usuarios);
var_dump($js->json_response());

//var_dump($data_jason);


?>
</body>
</html>