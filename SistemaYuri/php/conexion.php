<?php
$nombreArchivo;
$nombreExcel;
$enlace;
function IniciarConexion()
{
	  $GLOBALS['enlace'] = mysqli_connect('localhost', 'root', '');
	  mysqli_set_charset($GLOBALS['enlace'],'utf8');
	  if (!$GLOBALS['enlace']) {
		  die('No se pudo conectar: ' . mysql_error());
	  }
	  if (!mysqli_select_db($GLOBALS['enlace'],'yuri')) {
		  die('No se pudo seleccionar la base de datos: ' . mysql_error());
	  }
}

function CerrarConexion()
{
	mysqli_close($GLOBALS['enlace']);
    
}




?>