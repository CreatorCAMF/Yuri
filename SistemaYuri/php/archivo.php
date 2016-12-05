
<?php
 
$GLOBALS['sql']="";
function cargaArchivo()
{

    if (is_uploaded_file($_FILES['archivo']['tmp_name']))
    {
        $nombreDirectorio = "../archivosSubidos/";
        $nombreFichero = $_FILES['archivo']['name'];
        $nombreCompleto = $nombreDirectorio . $nombreFichero;
        if (is_file($nombreCompleto))
        {
            $idUnico = time();
            $GLOBALS['nombreArchivo']=$nombreFichero."-".date('Y-m-d H:i:s', $idUnico);
            $nombreFichero = $idUnico . "-" . $nombreFichero;
            
        }
        
        $s = move_uploaded_file($_FILES['archivo']['tmp_name'], $nombreCompleto);
        
        $GLOBALS['nombreExcel']=($nombreCompleto);
        
        if($s==1)
        {
            //echo "Archivo guardado correctamente";  
            header('Post: func.php&nombre=$nombreCompleto');
        }
        
        return true;
    }
    
    else
    {
        echo "No se ha podido subir el fichero";
        return false;
    }
        
}

function leerExcel()
{
    $lector     = PHPExcel_IOFactory::createReader('Excel2007');
    $lector ->setReadDataOnly(true);
    $datos[48];
    $documentoExcel = $lector->load($GLOBALS['nombreExcel']);
    
    $hoja = $documentoExcel->getSheet(0); 
    
    $ultimaFila = $hoja->getHighestRow(); 
    $ultiColumna = $hoja->getHighestColumn();
    $ultimaColumna = PHPExcel_Cell::columnIndexFromString($ultiColumna);
    
    
    $nombreExcel=$GLOBALS['nombreArchivo'];

    
    $GLOBALS['sql']="INSERT into clases VALUES( '$nombreExcel', ";
    
    for($fila=2;$fila<=$ultimaFila;$fila++)
    {
        //for($fila=0;$fila<$ultimaFila;$fila++)
        $columnaActual=0;
        $columna=0;
        for($columna=0;$columna<$ultimaColumna;$columna++)
        {
            $celda=$hoja->getCellByColumnAndRow($columna, $fila)->getValue(); 
            if($columna<48)
            {
                try
                {
                    $datos[$columna]=$celda;
                    if($columna==42||$columna==43)
                    {
                        if(strlen($celda)>1)
                        {
                            $GLOBALS['sql'].="'$celda',";
                        }
                        else{
                            $GLOBALS['sql'].="'0',";
                        }
                    }
                    else
                    {
                        $GLOBALS['sql'].="'$celda',";
                    }
                    
                }
                catch(Exception $e)
                {
                    echo "fila: $fila, columna: $columna\n";
                }
                              
            }
            else
            {
                if(strlen($celda)>0)
                {
                    $columnaActual=$columna;
                }
                else
                {
                    $columnaActual=$ultimaColumna;   
                }
                break;
            }    
        }
        $GLOBALS['sql']=substr($GLOBALS['sql'],0,-1);
        $GLOBALS['sql'].=");";
        IniciarConexion();
        $transaccionSQL=mysqli_query($GLOBALS['enlace'],$GLOBALS['sql']);
        if(!$transaccionSQL)
        {
            
            //$res=$GLOBALS['sql'];
            //echo "$res";
            //die("No se ha podido consultar...".mysqli_error());
            
        }
        else
        {
            //echo "fila insertada exitosamente";   
        }
        CerrarConexion();
        //$res=$GLOBALS['sql'];
        //echo "$res";
        $GLOBALS['sql']="INSERT into clases VALUES('$nombreExcel', ";
        
        
        //echo "$columnaActual,   $ultimaColumna";
        while($columnaActual<$ultimaColumna)
        {
            $columnaActual=profesoresExtra($datos,$hoja,$ultimaFila,$columnaActual,$fila);
        }
        
    }
    /*$GLOBALS['sql']=substr($GLOBALS['sql'],0,-2);
    $GLOBALS['sql'].=";";*/
    
    $res=$GLOBALS['sql'];
    //echo "$res";
    
}
    
    
function profesoresExtra($datos,$hoja,$ultimaFila,$filaActual,$columna)
{
    $nombreExcel=$GLOBALS['nombreArchivo'];
    for($i=0;$i<35;$i++)
    {
       $GLOBALS['sql'].="'$datos[$i]',";
    }
    $filaSuma=$filaActual+13;
    $exp=$filaActual+7;
    $meses=$filaActual+8;
    for($filaActual;$filaActual<$filaSuma;$filaActual++)
    {
        $celda=$hoja->getCellByColumnAndRow($filaActual, $columna)->getValue(); 
        try
        {
            //$datos[$fila]=$celda;
            if($filaActual==$exp||$filaActual==$meses)
            {
                if(strlen($celda)>0)
                {
                    $GLOBALS['sql'].="'$celda',";
                }
                else{
                    $GLOBALS['sql'].="'0',";
                }
            }
            else
            {
                $GLOBALS['sql'].="'$celda',";
            }
        }
        catch(Exception $e)
        {
            echo "fila: $fila, columna: $columna\n";
        }
    }
    $GLOBALS['sql']=substr($GLOBALS['sql'],0,-1);
    $GLOBALS['sql'].=");";
    //$res=$GLOBALS['sql'];
    //echo "$res";
    IniciarConexion();
    $transaccionSQL=mysqli_query($GLOBALS['enlace'],$GLOBALS['sql']);
    if(!$transaccionSQL)
    {
        $res=$GLOBALS['sql'];
        echo "$res";
        die("No se ha podido consultar...".mysqli_error());
    }
    else
    {
        //echo "fila insertada exitosamente";   
    }
    CerrarConexion();
    $GLOBALS['sql']="INSERT into clases VALUES( '$nombreExcel', ";
    return $filaActual;
    
}

 
 
 
 
 
function subirProfesores()
{
    $lector     = PHPExcel_IOFactory::createReader('Excel2007');
    $lector ->setReadDataOnly(true);
    $documentoExcel = $lector->load($GLOBALS['nombreExcel']);
    
    $hoja = $documentoExcel->getSheet(1); 
    
    if(!$hoja)
    {

    }
    else
    {
        $ultimaFila = $hoja->getHighestRow(); 
        $ultiColumna = $hoja->getHighestColumn();
        $ultimaColumna = PHPExcel_Cell::columnIndexFromString($ultiColumna);
        
        $QuerySQL="INSERT into profesores VALUES(";
        
        for($fila=2;$fila<=$ultimaFila;$fila++)
        {
            $columna=0;
            for($columna=0;$columna<3;$columna++)
            {
                $celda=$hoja->getCellByColumnAndRow($columna, $fila)->getValue(); 
                $QuerySQL.="'$celda',";
            }
            $QuerySQL=substr($QuerySQL,0,-1);
            $QuerySQL.=");";
            IniciarConexion();
            $transaccionSQL=mysqli_query($GLOBALS['enlace'],$QuerySQL);
            CerrarConexion();
            $QuerySQL="INSERT into profesores VALUES(";  
        
        }
    }
    
    
}
 
 
 
 
 
 ?>