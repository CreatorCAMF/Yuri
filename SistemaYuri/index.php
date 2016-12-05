<?php
  error_reporting(E_ERROR | E_WARNING | E_PARSE);
  session_start();
  $GLOBALS['id']=session_id();

?>

<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Formulario carga de archivos</title>
    <script src="js/bootstrap.js" type="text/javascript"></script>
    <script src="js/main.js" type="text/javascript"></script>
    <script src="js/jquery-1.11.2.min.js" type="text/javascript"></script>
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="css/estilos.css" rel="stylesheet" type="text/css">
</head>
<body onload="Inicio();">
 
 
 
 

<div id="Periodo" class="col-xs-4 col-lg-6 col-md-8 col-sm-12 col-xs-offset-4 col-lg-offset-3 col-md-offset-2">
    
</div>

<div id="dataTable" class="container-fluid col-xs-12 col-sm-12 col-md-10 col-lg-8 col-md-offset-1 col-lg-offset-2">
   
</div>


<div id="dataClasses" class="container-fluid col-xs-12 col-sm-12 col-md-10 col-lg-8 col-md-offset-1 col-lg-offset-2">
</div>

<div id="dataCarga" class="container-fluid col-xs-12 col-sm-12 col-md-8 col-lg-4 col-md-offset-2 col-lg-offset-4">
</div>




 <div class="container">
    
 </div>

 <div class="container" id="opciones">
     <div class="container col-xs-6 col-sm-12 col-md-6 col-lg-6" id="periodos" >
        
     </div>
     <div class="container col-xs-6 col-sm-12 col-md-6 col-lg-6 well" >
        <form action="php/func.php" method="POST" enctype="multipart/form-data" id="formUpload" autocomplete="off">
        
            <div class="form-group">
                <?php
                $id=$GLOBALS['id'];

                $s=$_SESSION[$id];
                echo $_SESSION['hola'];
                echo  "<input type='hidden' name='id' id='id' value='$id' placeholder='$id'/>";
                ?>
               
            </div> 
            <?php 
                $id=$GLOBALS['id'];
                //if(!$_SESSION[$id])
                if(true)
                {
                    echo "<div class='form-group'>
                            <label for='exampleInputEmail1'>N&oacute;mina</label>
                            <input type='text'  class='form-control' name='nomina' id='nomina'  placeholder='N&oacute;mina'>
                        </div>
                        <div class='form-group'>
                            <label for='exampleInputPassword1'>Contrase&ntilde;a</label>
                            <input type='password' class='form-control' name='password' id='password' placeholder='Contrase&ntilde;a'>
                        </div>
                        <div class='form-group'>
                            <label for='archivo'>Archivo</label>
                            <input type='file' name='archivo' id='archivo' />
                        </div>    
                        <div class='form-group'>
                            <input type='submit' name='enviar' id='enviar' value='Enviar'>
                            <!--<button type='button' id='boton' class='btn btn-default' onclick='Cargando();'>Enviar</button>-->
                        </div>";
                }
                else
                {
                    if($_SESSION[$id]==1)
                    {
                        $usuario=$_SESSION['usuario'];
                        $password=$_SESSION['password'];
                        echo "<div class='form-group has-success'>
                        <label for='exampleInputEmail1'>N&oacute;mina</label>
                        <input type='text'  class='form-control' name='nomina' id='nomina' value='$usuario' placeholder='N&oacute;mina' disabled>
                        </div>
                        <div class='form-group has-success'>
                            <label for='exampleInputPassword1'>Contrase&ntilde;a</label>
                            <input type='password' class='form-control' name='password' value='$password' id='password' placeholder='Contrase&ntilde;a' disabled>
                        </div>
                        <div class='form-group has-success'>
                            <label for='archivo'>Archivo</label>
                            <input type='file' name='nombre_archivo_cliente' id='archivo' />
                        </div>    
                        <div class='form-group has-success'>
                            <input type='hidden' name='enviar' id='enviar' value='Enviar'>
                            <button type='button' id='boton' class='btn btn-default' onclick='Cargando();'>Enviar</button>
                        </div>";
                    }
                    else
                    {
                        echo "<div class='form-group has-warning'>
                        <label for='exampleInputEmail1'>N&oacute;mina</label>
                        <input type='text'  class='form-control' name='nomina' id='nomina'  placeholder='N&oacute;mina' disabled>
                        </div>
                        <div class='form-group has-warning'>
                            <label for='exampleInputPassword1'>Contrase&ntilde;a</label>
                            <input type='password' class='form-control' name='password'  id='password' placeholder='Contrase&ntilde;a' disabled>
                        </div>
                        <div class='form-group has-warning'>
                            <label for='archivo'>Archivo</label>
                            <input type='file' name='nombre_archivo_cliente' id='archivo' disabled/>
                        </div>    
                        <div class='form-group has-warning'>
                            <input type='hidden' name='enviar' id='enviar' value='Enviar'>
                            <button type='button' id='boton' class='btn btn-default' onclick='Cargando();' disabled>Enviar</button>
                        </div>";
                    }
                }
            ?>
        </div>

        </form>
     </div>
 </div>
 
 <div class="col-xs-2 col-lg-2 col-md-2 col-sm-12 col-xs-offset-5 col-lg-offset-5 col-md-offset-5 ">
     <img src="img/cargando.gif" class="img-responsive" id="loadingImage">
     
 </div>




 

 
</body>
 
</html>