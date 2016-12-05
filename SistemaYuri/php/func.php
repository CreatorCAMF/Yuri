<?php 
error_reporting(E_ERROR | E_WARNING | E_PARSE);
$GLOBALS['data']="";
include '../Classes/PHPExcel.php';
include '../Classes/PHPExcel/Calculation.php';
include '../Classes/PHPExcel/Cell.php';
include 'conexion.php';
include 'archivo.php';




if($_REQUEST['enviar'])
{
    //$usuario=$_REQUEST['nomina'];
    //$password=$_REQUEST['password'];
    //$id=$_REQUEST['id'];
    //session_start();
    //$key="Yuri";
    //$usuario=rc4($key,$usuario);
    //$password=rc4($key,$password);
    /*if(strcmp($usuario,"L03054060")==0)
    {
        if (mylogin($usuario,$password)) 
        {*/
            if(cargaArchivo())
            {
                leerExcel();
                subirProfesores();
                depurarClases();
                header('Location: http://localhost/SistemaYuri/');
            }
            else{
                echo "error";
            }
            
            /*$_SESSION[$id]=1;
            $_SESSION['usuario']=$usuario;
            $_SESSION['password']=$password;*/
        /*}
        else
        {
            $_SESSION[$id]=2;
        }      
    }
    else
    {
        $_SESSION[$id]=2;
    }*/
      
   //header('Location: http://localhost/SistemaYuri/');  
}

if($_REQUEST['nomina'])
{
    $nomina=$_REQUEST['nomina'];
    $periodo=$_REQUEST['archivoPeriodo'];
    echo buscarClasesProfesor($nomina,$periodo);
}

if($_REQUEST['periodo'])
{
    echo periodos();
}

if($_REQUEST['reportePeriodo'])
{
    $periodo=$_REQUEST['reportePeriodo'];
    echo enviarReporte($periodo);
}


function mylogin($usuario, $password)
{
  //Establece una conección con el servidor LDAP a un hostname y puerto especifico.
  $ldap = @ldap_connect("LDAP://10.25.162.4") or die("No se puede conectar con el Servidor LDAP.");  //  LDAP server valido!


  $tipo = 'Empleados';

  if ($ldap) {
     //Hace una operacion bind en el directorio
    $bind_results = @ldap_bind($ldap,"cn=" . $usuario . ",ou=" . $tipo .",OU=TOL,dc=SVCS,dc=ITESM,dc=MX", $password);

    //string base_dn
    $dn = "ou=".$tipo.",OU=TOL,dc=SVCS,dc=ITESM,dc=MX";
    //string filter
    $filter="(|(cn=" . $usuario . "*))";
    //array attributes
    $nds_stuff = array("title", "sn", "givenname", "mail");

    //Esto hace una busqueda por un filtro en especifico en el directorio con el scope definido en LDAP_SCOPE_SUBTREE
    $results=ldap_search($ldap, $dn, $filter, $nds_stuff);

    //Esta funcion se usa para simplificar la lectura de multiples entradas devueltas por el resultado
    $info = ldap_get_entries($ldap, $results);

    //Esto checa que se haya hecho el bind
    if ($info["count"] != 0) {
       return true;
    } else {
      //Mensaje de error al no encontrar le resultado
      return false;

    }
    ldap_close($ldap);
  } else {
    return false;
    exit;
  }
}

function buscarClasesProfesor($nomina,$periodo)
{
    $respuesta="";
    $nombre;
    IniciarConexion();
    $QuerySQL="SELECT * FROM clases WHERE nomina LIKE '$nomina' AND archivo LIKE '$periodo'";
    $transaccionSQL=mysqli_query($GLOBALS['enlace'],$QuerySQL);
    if(!$transaccionSQL)
    {
        die("Hubo problemas al consultar las clases del profesor");
    } 
    else
    {
        while ($row=mysqli_fetch_object($transaccionSQL)) 
        {
            $respuesta.="$row->materia*";
            $respuesta.="$row->curso*";
            //$respuesta.="$row->crn*";
            $respuesta.="$row->nombre_materia*";
            $respuesta.="$row->horas_clase*";
            $respuesta.="$row->horas_laboratorio*";
            $respuesta.="$row->unidades*";
            $respuesta.="$row->alumnos_inscritos*";
            $respuesta.="$row->responsabilidad#";
            $nombre="$row->trato $row->nombre $row->apellido_p $row->apellido_m";
        }
        
    }
    $respuesta.="$nombre";
    return $respuesta;
}




function depurarClases()
{
    IniciarConexion();
    $QuerySQL="DELETE from clases WHERE responsabilidad = 0";
    $transaccionSQL=mysqli_query($GLOBALS['enlace'],$QuerySQL);
    if(!$transaccionSQL)
    {
        die("No se ha podido eliminar...".mysqli_error());
    }
    else
    {
        echo "Clases sin responsabilidad eliminadas   ";   
    }
    $QuerySQL="DELETE from clases WHERE materia LIKE '%PRXX%'";
    $transaccionSQL=mysqli_query($GLOBALS['enlace'],$QuerySQL);
    if(!$transaccionSQL)
    {
        die("No se ha podido eliminar...".mysqli_error());
    }
    else
    {
        echo "Clases en extranjero eliminadas   ";   
    }
    $QuerySQL="DELETE from clases WHERE atributos LIKE '%DAEC%' OR atributos LIKE '%CAEC%' OR atributos LIKE '%DAEE%'";
    $transaccionSQL=mysqli_query($GLOBALS['enlace'],$QuerySQL);
    if(!$transaccionSQL)
    {
        die("No se ha podido eliminar...".mysqli_error());
    }
    else
    {
        echo "Clases DAE eliminadas   ";   
    }
    $QuerySQL="DELETE from clases WHERE materia LIKE '%XA%' OR materia LIKE '%XF%' OR materia LIKE '%XR%' OR materia LIKE '%YA%' OR materia LIKE '%YC%'";
    $transaccionSQL=mysqli_query($GLOBALS['enlace'],$QuerySQL);
    if(!$transaccionSQL)
    {
        die("No se ha podido eliminar...".mysqli_error());
    }
    else
    {
        echo "Clases deportivas y culturales eliminadas   ";   
    }
    $QuerySQL="DELETE from clases WHERE atributos LIKE '%CLIE%' OR atributos LIKE '%CLIN%' OR atributos LIKE '%CLIP%' OR atributos LIKE '%UV-R%' OR atributos LIKE '%CLLC%' OR atributos LIKE '%LIDR%'";
    $transaccionSQL=mysqli_query($GLOBALS['enlace'],$QuerySQL);
    if(!$transaccionSQL)
    {
        die("No se ha podido eliminar...".mysqli_error());
    }
    else
    {
        echo "Clases virtuales  eliminadas   ";   
    }
    $QuerySQL="DELETE from clases WHERE atributos LIKE '%CSCA%' AND atributos NOT LIKE '%CPAA%'";
    $transaccionSQL=mysqli_query($GLOBALS['enlace'],$QuerySQL);
    if(!$transaccionSQL)
    {
        die("No se ha podido eliminar...".mysqli_error());
    }
    else
    {
        echo "Clases de atributo sin credito eliminadas  ";   
    }
    $QuerySQL="DELETE from clases WHERE materia LIKE '%WA%' AND atributos NOT LIKE '%CPAA%'";
    $transaccionSQL=mysqli_query($GLOBALS['enlace'],$QuerySQL);
    if(!$transaccionSQL)
    {
        die("No se ha podido eliminar...".mysqli_error());
    }
    else
    {
        echo "Clases de materia sin credito eliminadas  ";   
    }
    $QuerySQL="DELETE from clases WHERE alumnos_inscritos = '0' AND atributos NOT LIKE '%VPL%'";
    $transaccionSQL=mysqli_query($GLOBALS['enlace'],$QuerySQL);
    if(!$transaccionSQL)
    {
        die("No se ha podido eliminar...".mysqli_error());
    }
    else
    {
        echo "Clases de materia sin credito eliminadas  ";   
    }
    $QuerySQL="DELETE from clases WHERE unidades = '0'";
    $transaccionSQL=mysqli_query($GLOBALS['enlace'],$QuerySQL);
    if(!$transaccionSQL)
    {
        die("No se ha podido eliminar...".mysqli_error());
    }
    else
    {
        echo "Clases de materia sin unidades eliminadas  ";   
    }
    $QuerySQL="DELETE from clases WHERE categoria_pago NOT LIKE '%Planta%' OR categoria_pago LIKE '%Auxiliar Planta%'";
    $transaccionSQL=mysqli_query($GLOBALS['enlace'],$QuerySQL);
    if(!$transaccionSQL)
    {
        die("No se ha podido eliminar...".mysqli_error());
    }
    else
    {
        echo "Clases de profesor que no es de planta eliminadas  ";   
    }
    CerrarConexion();
}



function enviarReporte($periodo)
{
    
    $respuesta="";
    
   
    IniciarConexion();
    $QuerySQL="SELECT escuela,COUNT(categoria) as 'numero_profesores' FROM (SELECT c.archivo, p.escuela, p.categoria  FROM clases c, profesores p WHERE c.nomina=p.nomina AND archivo like '$periodo' GROUP BY c.nomina ORDER BY p.escuela ASC, p.categoria ASC, c.nomina ASC) AS subquery GROUP BY escuela ORDER by escuela";
    $consultaSQL=mysqli_query($GLOBALS['enlace'],$QuerySQL);
    if(!$consultaSQL)
    {
        die("Error al obtener el numero de profesores por escuela");
    }
    else
    {
        while($row=mysqli_fetch_object($consultaSQL))
        {
            $respuesta.="$row->escuela-$row->numero_profesores#";
        }  
        $respuesta=substr($respuesta,0,-1);
        $respuesta.="%";
    }
    
    
    $QuerySQL="SELECT escuela, categoria, COUNT(categoria) as numero_profesores FROM (SELECT c.archivo, p.escuela, p.categoria FROM clases c, profesores p WHERE c.nomina=p.nomina AND archivo like '$periodo' GROUP BY c.nomina ORDER BY p.escuela ASC, p.categoria ASC, c.nomina ASC) AS subquery GROUP BY categoria ORDER by escuela, categoria";
    $consultaSQL=mysqli_query($GLOBALS['enlace'],$QuerySQL);
    if(!$consultaSQL)
    {
        die("Error al obtener el numero de profesores por categoria");
    }
    else
    {
        while($row=mysqli_fetch_object($consultaSQL))
        {
            $respuesta.="$row->escuela-$row->categoria-$row->numero_profesores#";
        } 
        $respuesta=substr($respuesta,0,-1);
        $respuesta.="%"; 
    }
    
    $QuerySQL="SELECT p.escuela, p.categoria, c.nomina, concat(c.nombre,concat(' ',concat(c.apellido_p,concat(' ',c.apellido_m)))) AS 'nombre',COUNT(c.nomina) AS 'grupos', ROUND(sum((c.horas_clase+c.horas_laboratorio)*(c.responsabilidad/100)),2) as 'horas_reales', ROUND(sum(c.alumnos_inscritos *(c.responsabilidad/100)),2) as 'alumnos_reales', ROUND(sum(c.unidades*(c.responsabilidad/100)),2) as 'unidades_reales' FROM clases c, profesores p WHERE c.nomina=p.nomina AND archivo like '$periodo' GROUP BY c.nomina ORDER BY p.escuela ASC, p.categoria ASC, c.nomina ASC";
    $consultaSQL=mysqli_query($GLOBALS['enlace'],$QuerySQL);
    if(!$consultaSQL)
    {
        die("Error al obtener las clases de profesores");
    }
    else
    {
        while($row=mysqli_fetch_object($consultaSQL))
        {
            $respuesta.="$row->nomina*";
            $respuesta.="$row->nombre*";
            $respuesta.="$row->grupos*";
            $respuesta.="$row->horas_reales*";
            $respuesta.="$row->alumnos_reales*";
            $respuesta.="$row->unidades_reales#";
            
        } 
        $respuesta=substr($respuesta,0,-1); 
        $respuesta.="%"; 
    }    
    
    $QuerySQL="SELECT  p.categoria,COUNT(c.nomina) AS 'grupos', ROUND(sum((c.horas_clase+c.horas_laboratorio)*(c.responsabilidad/100)),2) as 'horas_reales', ROUND(sum(c.alumnos_inscritos *(c.responsabilidad/100)),2) as 'alumnos_reales', ROUND(sum(c.unidades*(c.responsabilidad/100)),2) as 'unidades_reales' FROM clases c, profesores p WHERE c.nomina=p.nomina AND archivo like '$periodo'GROUP BY p.categoria ORDER BY p.escuela ASC, p.categoria ASC, c.nomina ASC";
    $consultaSQL=mysqli_query($GLOBALS['enlace'],$QuerySQL);
    if(!$consultaSQL)
    {
        die("Error al obtener las clases de categoria");
    }
    else
    {
        while($row=mysqli_fetch_object($consultaSQL))
        {
            $respuesta.="$row->categoria*";
            $respuesta.="$row->grupos*";
            $respuesta.="$row->horas_reales*";
            $respuesta.="$row->alumnos_reales*";
            $respuesta.="$row->unidades_reales#";
            
        } 
        $respuesta=substr($respuesta,0,-1); 
        $respuesta.="%";
    }    
    $QuerySQL="SELECT p.escuela, p.categoria, c.nomina, concat(c.nombre,concat(' ',concat(c.apellido_p,concat(' ',c.apellido_m)))) AS 'nombre',COUNT(c.nomina) AS 'grupos', ROUND(sum((c.horas_clase+c.horas_laboratorio)*(c.responsabilidad/100)),2) as 'horas_reales', ROUND(sum(c.alumnos_inscritos *(c.responsabilidad/100)),2) as 'alumnos_reales', ROUND(sum(c.unidades*(c.responsabilidad/100)),2) as 'unidades_reales' FROM clases c, profesores p WHERE c.nomina=p.nomina AND archivo like '$periodo' GROUP BY p.escuela ORDER BY p.escuela ASC, p.categoria ASC, c.nomina ASC";
    $consultaSQL=mysqli_query($GLOBALS['enlace'],$QuerySQL);
    if(!$consultaSQL)
    {
        die("Error al obtener las clases de escuela");
    }
    else
    {
        while($row=mysqli_fetch_object($consultaSQL))
        {
            $respuesta.="$row->escuela*";
            $respuesta.="$row->grupos*";
            $respuesta.="$row->horas_reales*";
            $respuesta.="$row->alumnos_reales*";
            $respuesta.="$row->unidades_reales#";
            
        } 
        $respuesta=substr($respuesta,0,-1); 
        $respuesta.="%";
    }  
    $QuerySQL="SELECT COUNT(c.nomina) AS 'grupos', ROUND(sum((c.horas_clase+c.horas_laboratorio)*(c.responsabilidad/100)),2) as 'horas_reales', ROUND(sum(c.alumnos_inscritos *(c.responsabilidad/100)),2) as 'alumnos_reales', ROUND(sum(c.unidades*(c.responsabilidad/100)),2) as 'unidades_reales' FROM clases c WHERE archivo like '$periodo'";
    $consultaSQL=mysqli_query($GLOBALS['enlace'],$QuerySQL);
    if(!$consultaSQL)
    {
        die("Error al obtener el total");
    }
    else
    {
        while($row=mysqli_fetch_object($consultaSQL))
        {
            $respuesta.="$row->grupos*";
            $respuesta.="$row->horas_reales*";
            $respuesta.="$row->alumnos_reales*";
            $respuesta.="$row->unidades_reales#";
            
        } 
        $respuesta=substr($respuesta,0,-1); 
    }  
    CerrarConexion();
    $respuesta.="%$periodo";
    //echo $respuesta;
    return $respuesta;
    
}


function periodos()
{
    
    $response="";
    $QuerySQL="SELECT archivo, COUNT(archivo) as 'clases' FROM clases GROUP BY archivo;";
    IniciarConexion();
    $consultaSQL=mysqli_query($GLOBALS['enlace'],$QuerySQL);
    if(!$consultaSQL)
    {
        return "Error al obtener los datos";
    }
    else {
        while ($row=mysqli_fetch_object($consultaSQL)) {
            $response.="$row->archivo*$row->clases#";
        }
    }
    CerrarConexion();
    return $response;
}




function mb_chr($char) {
    return mb_convert_encoding('&#'.intval($char).';', 'UTF-8', 'HTML-ENTITIES');
}

function mb_ord($char) {
    $result = unpack('N', mb_convert_encoding($char, 'UCS-4BE', 'UTF-8'));

    if (is_array($result) === true)
    {
        return $result[1];
    }
    return ord($char);
}

function rc4($key, $str) {
    if (extension_loaded('mbstring') === true) {
        mb_language('Neutral');
        mb_internal_encoding('UTF-8');
        mb_detect_order(array('UTF-8', 'ISO-8859-15', 'ISO-8859-1', 'ASCII'));
    }

    $s = array();
    for ($i = 0; $i < 256; $i++) 
    {
        $s[$i] = $i;
    }
    $j = 0;
    for ($i = 0; $i < 256; $i++) 
    {
        $j = ($j + $s[$i] + mb_ord(mb_substr($key, $i % mb_strlen($key), 1))) % 256;
        $x = $s[$i];
        $s[$i] = $s[$j];
        $s[$j] = $x;
    }
    $i = 0;
    $j = 0;
    $res = '';
    for ($y = 0; $y < mb_strlen($str); $y++)
    {
        $i = ($i + 1) % 256;
        $j = ($j + $s[$i]) % 256;
        $x = $s[$i];
        $s[$i] = $s[$j];
        $s[$j] = $x;

        $res .= mb_chr(mb_ord(mb_substr($str, $y, 1)) ^ $s[($s[$i] + $s[$j]) % 256]);
    }
    return $res;
}


?>