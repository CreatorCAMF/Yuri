<?php

$data = $_POST["datos"];

header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=reporte.xls");
header("Pragma: no-cache");
header("Expires: 0");

echo "$data";

?>
