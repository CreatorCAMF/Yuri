// JavaScript Document

/*************************************************************************************/
/******************  Metodos para Ajax *************************************/
/*************************************************************************************/

/**************************************************************************************/
/* Nombre: 		conexionAJAX                                                                
   Funcion:		Crea el objeto XMLHttpRequest que permite el intercambio de datos
   				en segundo plano.
   Entradas: 	url: Dirección a la que debe ir el mensaje (metodo get)
   				cfunc: funcion que se desea ejecutar con los datos obtenidos.
   Salidas: 	Envia los datos de la respuesta a una función diferente que
   				tratra los datos a su manera.
   Ojo:			El readyState debe ser igual a 4 ya que eso indica que la respuesta
				ha finalizado y esta lista y el status igual a 200 para saber que 
				encontro  la pagina
				En open es GET o POST (GET en este caso), la url y si es asincrono
				o no, si es asincrono, el resto de funciones se ejecutaran y cuando este
				listo mandara llamar a un a función, si no es asincrono esperara hasta
				obtener la respuesta y que pueda proseguir                            */
/**************************************************************************************/
                   
function conexionAJAX(url, cfunc) {
    var xhttp;
	if((window.XMLHttpRequest) || ((typeof XMLHttpRequest) != undefined))
	{
		xhttp=new XMLHttpRequest();
	}
	else
	{
		xhttp = new ActiveXObject(Microsoft.XMLHTTP);
	}
    
    xhttp.onreadystatechange = function() {
        if (xhttp.readyState == 4 && xhttp.status == 200) {
            cfunc(xhttp);
        }
    };
	xhttp.open("GET", url, true);
	xhttp.send();
}




function llenarReporte(xhttp)
{
    var informacion=xhttp.responseText.split("%");
    var escuelas =informacion[0].split("#");
    var categorias=informacion[1].split("#");
    var clases=informacion[2].split("#");
    var totalesCategorias=informacion[3].split("#");
    var totalesEscuelas=informacion[4].split("#");
    var total=informacion[5].split("*");
    var periodo=informacion[6];
    var html="";
    var i,j,k,x,y;
    var profesoresTotalesPrepa=0;
    var profesoresTotalesProfesional=0;
    var profesoresMenosCargaPrepa=0;
    var profesoresMenosCargaProfesional=0;
    html+="<table class='table table-bordered table-condensed'>";
    html+="    <thead class='lila'>";
    html+="         <tr>";
    html+="            <td>Escuela</td>";
    html+="           <td>Categor&iacute;a</td>";
    html+="            <td>N&oacute;mina</td>";
    html+="            <td>Nombre</td>";
    html+="            <td>Grupos</td>";
    html+="            <td>Horas</td>";
    html+="            <td>Alumnos</td>";
    html+="            <td>Unidades</td>";
    html+="        </tr>";   
    html+="    </thead>";
    html+="    <tbody id='bodyDataTable'>";
    x=0;
    y=0;
    i=0;
    j=0;
    var tCat=[totalesCategorias.length+1];
    var d=0
    tCat[0]="";
    for(d=0; d<totalesCategorias.length;d++)
    {
        var stc=totalesCategorias[d].split("*");       
        var cat=categorias[d].split("-");   
        tCat[d+1]="<tr class='amarillo'><td colspan='2' >Total "+stc[0]+"</td><td>"+cat[2]+"</td><td>"+stc[1]+"</td><td>"+stc[2]+"</td><td>"+stc[3]+"</td><td>"+stc[4]+"</td></tr>";
    }
    
    
    var tEsc=[totalesEscuelas.length+1];
    var e=0
    tEsc[0]="";
    for(e=0; e<totalesEscuelas.length;e++)
    {
        var ste=totalesEscuelas[e].split("*");       
        var esc=escuelas[e].split("-");   
        tEsc[e+1]="<tr class='verde'><td colspan='3'>Total "+ste[0]+"</td><td>"+esc[1]+"</td><td>"+ste[1]+"</td><td>"+ste[2]+"</td><td>"+ste[3]+"</td><td>"+ste[4]+"</td></tr>";
        if(ste[0].indexOf("Prepa")>=0)
        {
            profesoresTotalesPrepa+=parseInt(esc[1]);
        }
        else
        {
            profesoresTotalesProfesional+=parseInt(esc[1]);   
        }
    }
    
    for(k=0;k<clases.length;k++)
    {        
        if(k==j)
        {                       
            html+=tCat[i];
        }
        if(k==y)
        {            
                       
            html+=tEsc[x];
        }
        html+="<tr>";
        if(k==y)
        {        
            var escuela=escuelas[x].split("-");
            var rs=parseInt(escuela[1]);   
            y+=rs;
            var a=0;
            for(a=0;a<categorias.length;a++)
            {
                var kategoria=categorias[a].split("-");
                
                if(kategoria[1].indexOf(escuela[0].trim())>=0)
                {
                    
                    rs++;
                    //alert(kategoria[2]+" Profesores en " + kategoria[1] +" En total "+ rs);
                }
            }         
            html+="<td rowspan='"+rs+"' class='naranja'>"+escuela[0]+"</td>";        
            x++;
        }
        if(k==j)
        {        
            var categoria=categorias[i].split("-");
            //alert(categoria);
            var rs2=parseInt(categoria[2]);               
            j+=rs2;
            html+="<td rowspan='"+rs2+"' class='azul'>"+categoria[1]+"</td>";
            i++;
        }
        var info=clases[k].split("*");
        for(var z=0;z<info.length;z++)
        {
            if(z==0)
            {
                html+="<td><a href='#dataClasses' id='"+info[z]+"'  onClick='buscarClasesProfesor(this.id);'>"+info[z]+"</a></td>"; 
            }
            else
            {
                if(z==3)
                {
                    var c=categorias[i-1].split("-");
                    if(c[1].indexOf("Director")>=0)
                    {
                        if(parseFloat(info[3])>=6.00)
                        {
                            html+="<td>"+info[z]+"</td>";                    
                        }
                        else
                        {
                            html+="<td class='rojo'>"+info[z]+"</td>";
                            profesoresMenosCargaProfesional++;
                        }
                    }
                    else
                    {
                        if(c[1].indexOf("Profesor")>=0&&c[1].indexOf("Investigador")<0)
                        {
                            if(c[1].indexOf("Prepa")>=0)
                            {
                                if(parseFloat(info[3])>=15.00)
                                {
                                    html+="<td>"+info[z]+"</td>";  
                                }
                                else
                                {
                                    html+="<td class='rojo'>"+info[z]+"</td>"; 
                                    profesoresMenosCargaPrepa++;   
                                }
                            }
                            else
                            {
                                if(parseFloat(info[3])>=12.00)
                                {
                                    html+="<td>"+info[z]+"</td>";  
                                }
                                else
                                {
                                    html+="<td class='rojo'>"+info[z]+"</td>";
                                    profesoresMenosCargaProfesional++; 
                                }
                            }
                        }
                        else
                        {
                            html+="<td>"+info[z]+"</td>"; 
                            profesoresTotalesProfesional--;
                        }
                    }
                    
                }           
                else
                {
                    html+="<td>"+info[z]+"</td>";   
                }
            }
            
            
            
        }
        html+="</tr>";
        
    }
    
    html+=tCat[tCat.length-1];
    html+=tEsc[tEsc.length-1];

    html+="<tr class='lila'><td colspan='3'>Total </td><td>"+y+"</td><td>"+total[0]+"</td><td>"+total[1]+"</td><td>"+total[2]+"</td><td>"+total[3]+"</td></tr>";
    html+="    </tbody>";
    html+="</table>";    
    
    $('#dataTable').html(html);
    
    
    
    
    
    html="<table class='table table-bordered table-condensed'>";
    html+="    <thead class='blue'>";
    html+="         <tr>";
    html+="            <td colspan='2'>Profesores con menos de su carga docente</td>";
    html+="        </tr>";   
    html+="    </thead>";
    html+="    <tbody id='bodyDataTable'>";
    html+="         <tr>";
    html+="            <td>"+(profesoresMenosCargaPrepa/profesoresTotalesPrepa)*100+"%</td>";
    html+="            <td>Preparatoria</td>";
    html+="        </tr>"; 
    html+="         <tr>";
    html+="            <td>"+(profesoresMenosCargaProfesional/profesoresTotalesProfesional)*100+"%</td>";
    html+="            <td>Profesional</td>";
    html+="        </tr>"; 
    html+="    </tbody>";
    html+="</table>"; 
    $('#dataCarga').html(html);
    
    var v=$('#dataTable').html()+" "+$('#dataCarga').html();
    html="<a onClick='descargarReporte();'>Descargar reporte</a>";
    html+="<form action='php/descarga.php' method='POST' id='formularioReporte' enctype='multipart/form-data'>";
    html+="<input type='hidden' name='datos' value='"+v+"' />";
    html+="</form>";
    
    
    $('#opciones').html(html);
    
    $('#Periodo').html("<h2 id='ValPeriodo'>"+periodo+"</h2>");
    
    
    
}



function descargarReporte() {
    $("#formularioReporte").submit();
}

function llenarClasesProfesor(xhttp)
{
    var datos = xhttp.responseText.split("#");
    var i =0;
    var html="";
    html+="<table class='table table-bordered table-condensed'>";
    html+="    <thead class='lila'>"
    html+="         <tr>";
    html+="         <td colspan='8'>Clases de "+datos[datos.length-1]+" </td>";
    html+="         </tr>";
    html+="         <tr>";
    html+="            <td>Materia</td>";
    html+="            <td>Curso</td>";
    html+="            <td>nombre materia</td>";
    html+="            <td>Horas clase</td>";
    html+="            <td>Horas laboratorio</td>";
    html+="            <td>Unidades</td>";
    html+="            <td>Alumnos inscritos</td>";
    html+="            <td>Responsabilidad</td>";
    html+="        </tr>";   
    html+="    </thead>";
    html+="    <tbody id='bodyDataTable'>";
    
    for(i=0;i<datos.length-1;i++)
    {
        var clase= datos[i].split("*");
        var j=0;
        html+="<tr>";
        for(j=0;j<clase.length;j++)
        {
            html+="<td>"+clase[j]+"</td>";
        }
        html+="</tr>";
    }
    html+="</tbody>";
    html+="</table>";
    $('#dataClasses').html(html);
    
    
}



function periodos(xhttp)
{
    var html="<ul class='list-group'>";
    html+="<li class='list-group-item'>Periodo<span class='badge'># de clasess</span></li>";
    
    var datos=xhttp.responseText.split("#");  
    for(var i=0; i<datos.length-1;i++)
    {
        var period=datos[i].split("*");
        html+="<li class='list-group-item' id='"+period[0]+"' onClick='pedirReporte(this.id);'>"+period[0]+"<span class='badge'>"+period[1]+"</span></li>";
    }
    html+="</ul>";
    $('#periodos').html(html);
}


function pedirReporte(periodo)
{
    
    var datos= "php/func.php?reportePeriodo="+periodo;
    conexionAJAX(datos,llenarReporte);
}

function buscarClasesProfesor(nomina)
{
    var periodo = $("#Periodo h2").html();
    var datos= "php/func.php?nomina="+nomina+"&archivoPeriodo="+periodo;
    conexionAJAX(datos,llenarClasesProfesor);
}

function cargarReporte()
{
    
    $.post("php/func.php",
    { nombre_archivo_cliente: $('#archivo').val(), enviar: $('#boton').val() },
    function(data){
        alert("Data Loaded: " + data);
    }
    );
        /*alert($('#archivo').val());
    var datos= "php/func.php?nombre_archivo_cliente="+$('#archivo').val()+"&enviar="+$('#boton').val();
    alert(datos);
    conexionAJAX(datos,periodos);*/
}



function Inicio()
{ 
    $('#loadingImage').css("visibility","hidden");
    var datos= "php/func.php?periodo=true";
    conexionAJAX(datos,periodos);
}


function Cargando()
{
    if($('#nomina').val()==""|| $("#password").val() == "")
    {    
        alert("Algun campo esta vacio...");
    }
    else 
    {
        //$key="Yuri";

        //$('#nomina').val(rc4($key,($('#nomina').val())));
        //$('#password').val(rc4($key,($('#password').val())));
        $("#formUpload").submit();
        $('#loadingImage').css("visibility","visible");
    }
     
}


function rc4(key, str) {
	var s = [], j = 0, x, res = '';
	for (var i = 0; i < 256; i++) {
		s[i] = i;
	}
	for (i = 0; i < 256; i++) {
		j = (j + s[i] + key.charCodeAt(i % key.length)) % 256;
		x = s[i];
		s[i] = s[j];
		s[j] = x;
	}
	i = 0;
	j = 0;
	for (var y = 0; y < str.length; y++) {
		i = (i + 1) % 256;
		j = (j + s[i]) % 256;
		x = s[i];
		s[i] = s[j];
		s[j] = x;
		res += String.fromCharCode(str.charCodeAt(y) ^ s[(s[i] + s[j]) % 256]);
	}
	return res;
}





