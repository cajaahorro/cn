var xmlhttp;
// =false;

// if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
//  xmlhttp = new XMLHttpRequest();
// }

function ajax_call() {
	xmlhttp=GetXmlHttpObject();
	if (xmlhttp==null)
	  {
		  alert ("Browser does not support HTTP Request");
	  return;
	  }
//	var selIndex = document.getElementById('lascuotas').options[document.getElementById('lascuotas').selectedIndex].value;
//	var selIndex = document.getElementById("lascuotas").options[document.getElementById("lascuotas").selectedIndex].value; 
	var selIndex = document.getElementById('lascuotas').value;
//	comboValue = document.getElementById('lascuotas').options[selIndex].value;
	var linea='ajaxWork.php?montoprestamo=' + 
					(document.getElementById('monpre_sdp').value-document.getElementById('inicial').value) + 
						'&num_cuotas=' + selIndex + 
						'&p_interes=' + document.getElementById('interes_sd').value + 
						'&divisible=' + document.getElementById('factor_division').value +
						'&tipo_interes=' + document.getElementById('tipo_interes').value+
						'&f_ajax=' + document.getElementById('calculo').value + 
						'&cual=1'+
						'&descontar_interes=' + document.getElementById('descontar_interes').value+
						'&monto_futuro=' + document.getElementById('monto_futuro').value ;
						
						// +  document.getElementById('monpre_sdp').value;
						// document.getElementById('lascuotas');
 	// alert(linea);
	xmlhttp.onreadystatechange=cambio;
	xmlhttp.open("GET", linea, true);
//----------
/*
//setup the headers
    try {
        xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xmlhttp.setRequestHeader("Accept", "text/xml, application/xml, text/plain");
    } catch ( ex ) {
        window.alert('error' + ex.toString());
    }

*/
//----------
	xmlhttp.send(null)
//	return false;
}


function cambio() {
if (xmlhttp.readyState==4) {
//			document.getElementById('cuota').value = xmlhttp.responseText;	// forma original para uno solo
//			document.getElementById('interes_diferido').value = xmlhttp.responseText;

//	alert('1');
//	var xmlDoc=xmlhttp.responseXML.documentElement;
//	alert('2');
//	alert(xmlDoc);
	// var 
	xmlDoc=xmlhttp.responseXML;
//	alert(xmlhttp.responseXML.getElementsByTagName('cuota')[0].childNodes[0].nodeValue);
//	peticion.responseXML.getElementsByTagName("codigo" )[0];
//	document.getElementById("cuota").value= xmlDoc.getElementsByTagName("cuota")[0].childNodes[0].nodeValue;
	document.getElementById("cuota").value= xmlDoc.getElementsByTagName("cuota")[0].childNodes[0].nodeValue;
	document.getElementById("interes_diferido").value=xmlDoc.getElementsByTagName("interes_diferido")[0].childNodes[0].nodeValue;
	document.getElementById("montoneto").value=xmlDoc.getElementsByTagName("montoneto")[0].childNodes[0].nodeValue;
	document.getElementById("gastosadministrativos").value=xmlDoc.getElementsByTagName("gastosadministrativos")[0].childNodes[0].nodeValue;
	}
}

function GetXmlHttpObject()
{
if (window.XMLHttpRequest)
  {
//	  alert('a');
  // code for IE7+, Firefox, Chrome, Opera, Safari
  return new XMLHttpRequest();
  }
if (window.ActiveXObject)
  {
//	  alert('b');
  // code for IE6, IE5
  return new ActiveXObject("Microsoft.XMLHTTP");
  }
return null;
}

function calcularcancelar() {
	xmlhttp=GetXmlHttpObject();
	if (xmlhttp==null)
	  {
		  alert ("Browser does not support HTTP Request");
	  return;
	  }
//	alert('1');

	var data = document.getElementsByName( "cancelar[]" );
/*	if( data != null ){
		for(j=0; j < data.length; j++){
		// you have the data in the client for your array
//			eval( alert( data[j].innerHTML ) ) ;
			eval( alert( data[j].childNodes[0].nodeValue; ) ) ;
        }
      }
*/
// arreglo=document.getElementsByName("cancelar[]");
	var linea='ajaxCalc.php?arreglo='+ GetInfoString();
//		document.getElementById("data");
//	var linea='ajaxCalc.php?';
//-----
//-----
 	alert(linea);
	xmlhttp.onreadystatechange=cambio2;
	xmlhttp.open("GET", linea, true);
	xmlhttp.send(null)
//	return false;
}

	function GetInfoString(){
		var iCount =0;
	    var sData="";
//		iCount=document.getElementsByName("cancelar[]","").length;
		iCount=document.getElementsByName("cancelar[]","").length;
		alert('icount = '+iCount);
//		var checkboxes = document.getElementById("form1").cancelar;
//		alert(' cuantos '+checkboxes);
//		iCount=form1.elements.namedItem("cancelar[]","").length;
//		iCount=document.getElementyBy
//		iCount=myform.elements.namedItem("cancelar[]","").length;
	    if(iCount>>0){
	        for(i=0;i<iCount;i++) {
//	            if (myform.elements.namedItem("cancelar[]","")(i).checked){
//	            if (form1.elements.namedItem("cancelar[]","")(i).checked){
				var checkbox = document.getElementById("cancelar").checked;
				if (checkbox) { // (document.forms['form1'].elements[cancelar][i].checked){
					alert(i);

					sData += "&cancelar[]=" +  document.getElementsByName("cancelar[]","")(i).value;
//					sData += "&cancelar[]=" +  form1.elements.namedItem("cancelar[]","")(i).value;
//					sData += "&cancelar[]=" +  myform.elements.namedItem("cancelar[]","")(i).value;
				}
			}
	    }     
		alert(sData);
		return sData;
	}


function cambio2() {
if (xmlhttp.readyState==4) {
	xmlDoc=xmlhttp.responseXML;
	document.getElementById("cancelados").value= xmlDoc.getElementsByTagName("cancelados")[0].childNodes[0].nodeValue;
	document.getElementById("netoarecibir").value= xmlDoc.getElementsByTagName("neto")[0].childNodes[0].nodeValue;
	document.getElementById("montoprestamo").value= xmlDoc.getElementsByTagName("montoprestamo")[0].childNodes[0].nodeValue-xmlDoc.getElementsByTagName("interes_diferido")[0].childNodes[0].nodeValue;
	document.getElementById("descuentosadm").value= xmlDoc.getElementsByTagName("descuentosadm")[0].childNodes[0].nodeValue;
	document.getElementById("marcados").value= xmlDoc.getElementsByTagName("marcados")[0].childNodes[0].nodeValue;
	document.getElementById("reintegros").value= xmlDoc.getElementsByTagName("reintegros")[0].childNodes[0].nodeValue;
	if (document.getElementById("netoarecibir").value <= 0)
		alert('Verifique los datos. El neto a recibir no puede ser menor o igual a cero');
//	document.getElementById("interes_diferido").value=xmlDoc.getElementsByTagName("interes_diferido")[0].childNodes[0].nodeValue;
//	document.getElementById("montoneto").value=xmlDoc.getElementsByTagName("montoneto")[0].childNodes[0].nodeValue;
//	document.getElementById("gastosadministrativos").value=xmlDoc.getElementsByTagName("gastosadministrativos")[0].childNodes[0].nodeValue;
	}
}


function calccancel() {
	xmlhttp=GetXmlHttpObject();
	if (xmlhttp==null)
	  {
		  alert ("Browser does not support HTTP Request");
	  return;
	  }
	alert('1');

	var data = document.getElementsByName( "cancelar[]" );
	if( data != null ){
		for(j=0; j < data.length; j++){
		// you have the data in the client for your array
//			eval(
//				 alert( data[j].innerHTML ) ; // ) ;
//			eval( alert( data[j].childNodes[0].nodeValue; ) ) ;
        }
      }

// arreglo=document.getElementsByName("cancelar[]");
	var linea='ajaxCalc.php?cancelar='+ document.getElementsById( "cancelar" );
//		document.getElementById("data");
//	var linea='ajaxCalc.php?';
//-----
//-----
 	alert(linea);
	xmlhttp.onreadystatechange=cambio2;
	xmlhttp.open("GET", linea, true);
    xmlhttp.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
//	xmlhttp.setRequestHeader("Content-length", params.length);
//	xmlhttp.setRequestHeader("Connection", "close");
	xmlhttp.send(null)
//	return false;
}

function calccanc() {
	xmlhttp=GetXmlHttpObject();
	if (xmlhttp==null)
	  {
		  alert ("Browser does not support HTTP Request");
	  return;
	  }
	var selIndex = document.getElementById('registros').value;
	var linea='ajaxCalc2.php?registros=' + selIndex+'&';
	var otralinea='';
	
/*
var indice = 1;
eval("var variable" + indice + " = 'valor'");
alert('la variable 1='+variable1);
*/
	var totalregistros=0;
	for(j=0; j < selIndex; j++){
//			var valor='cancelar'+(j+1);
//			alert('valor ='+valor);
			
//			var indice = 1;
//			eval("var variable" + indice + " = 'valor'");
			
			var valor = eval("document.getElementById('cancelar"+(j+1)+"').checked");
//			alert(valor2);
			if (valor == true) {
				
				totalregistros++;
				var valor2 = eval("document.getElementById('cancelar"+(j+1)+"').value");
//				otralinea=otralinea+'cancelar'+(j+1)+'='+valor2+'&'
				otralinea=otralinea+'cancelar'+(totalregistros)+'='+valor2+'&'
			}
        }
//			alert(otralinea);

	/*
					(document.getElementById('monpre_sdp').value-document.getElementById('inicial').value) + 
						'&num_cuotas=' + selIndex + 
						'&p_interes=' + document.getElementById('interes_sd').value + 
						'&divisible=' + document.getElementById('factor_division').value +
						'&tipo_interes=' + document.getElementById('tipo_interes').value+
						'&f_ajax=' + document.getElementById('calculo').value + 
						'&cual=1';
						*/
	linea=linea+otralinea+'totalregistros='+totalregistros+'&micedula='+document.getElementById('micedula').value+'&montoprestamo=';
	linea+=document.getElementById('montoprestamo').value;
// 	alert(linea);
	xmlhttp.onreadystatechange=cambio2;
	xmlhttp.open("GET", linea, true);
	xmlhttp.send(null)
}

function ajax_call_zapatos() {
	xmlhttp=GetXmlHttpObject();
	if (xmlhttp==null)
	  {
		  alert ("Browser does not support HTTP Request");
	  return;
	  }
/*
var indice = 1;
eval("var variable" + indice + " = 'valor'");
alert('la variable 1='+variable1);
*/
	var selIndex = document.getElementById('registros').value;
//	var linea='ajaxCalc2.php?registros=' + selIndex+'&';
	var otralinea='';

	var totalregistros=0;
	var cuanto=0.00;
	var pares =0
	for(j=0; j < selIndex; j++)
	{
		totalregistros++;
		var necesita = eval("document.getElementById('cnt"+(j)+"').value");
		var precio   = eval("document.getElementById('precio"  +(j)+"').value");
		pares += parseInt (necesita);
//		alert(necesita);
		var cuanto = cuanto + (parseFloat (precio) * parseInt (necesita));
	}
// 	alert('sali');
	if (pares > 2) 
	{
		document.getElementById("cuotas").value= 2;
		document.getElementById("monto").value= cuanto / 2;
	}
	else 
	{
		document.getElementById("cuotas").value= 1;
		document.getElementById("monto").value= cuanto;
	}
	document.getElementById("totalprestamo").value= cuanto;
	document.getElementById("items").value= pares;
	xmlhttp.onreadystatechange=cambio_zapatos;
	xmlhttp.open("GET", linea, true);
	xmlhttp.send(null)
}

function cambio_zapatos() {
if (xmlhttp.readyState==4) {
//			document.getElementById('cuota').value = xmlhttp.responseText;	// forma original para uno solo
//			document.getElementById('interes_diferido').value = xmlhttp.responseText;

//	alert('1');
//	var xmlDoc=xmlhttp.responseXML.documentElement;
//	alert('2');
//	alert(xmlDoc);
	// var 
	xmlDoc=xmlhttp.responseXML;
//	alert(xmlhttp.responseXML.getElementsByTagName('cuota')[0].childNodes[0].nodeValue);
//	peticion.responseXML.getElementsByTagName("codigo" )[0];
//	document.getElementById("cuota").value= xmlDoc.getElementsByTagName("cuota")[0].childNodes[0].nodeValue;
	document.getElementById("totalprestamo").value= xmlDoc.getElementsByTagName("cuota")[0].childNodes[0].nodeValue;
	document.getElementById("interes_diferido").value=xmlDoc.getElementsByTagName("interes_diferido")[0].childNodes[0].nodeValue;
	document.getElementById("montoneto").value=xmlDoc.getElementsByTagName("montoneto")[0].childNodes[0].nodeValue;
	document.getElementById("gastosadministrativos").value=xmlDoc.getElementsByTagName("gastosadministrativos")[0].childNodes[0].nodeValue;
	}
}
///////////////////////////
function ajax_call_zapatosm() {
	xmlhttp=GetXmlHttpObject();
	if (xmlhttp==null)
	  {
		  alert ("Browser does not support HTTP Request");
	  return;
	  }
/*
var indice = 1;
eval("var variable" + indice + " = 'valor'");
alert('la variable 1='+variable1);
*/
	var selIndex = document.getElementById('registros').value;
//	var linea='ajaxCalc2.php?registros=' + selIndex+'&';
	var otralinea='';

	var totalregistros=0;
	var cuanto=0.00;
	var pares =0
	for(j=0; j < selIndex; j++)
	{
		totalregistros++;
		var necesita = eval("document.getElementById('cnt"+(j)+"').value");
		var precio   = eval("document.getElementById('precio"  +(j)+"').value");
		pares += parseInt (necesita);
//		alert(necesita);
		var cuanto = cuanto + (parseFloat (precio) * parseInt (necesita));
	}
// 	alert('sali');
	if (pares < 12)
		alert("Error tienen que ser un minimo de 12 unidades");
/*
	if (pares > 2) 
	{
		document.getElementById("cuotas").value= 2;
		document.getElementById("monto").value= cuanto / 2;
	}
	else 
*/
	{
		document.getElementById("cuotas").value= 1;
		document.getElementById("monto").value= cuanto;
	}
	document.getElementById("totalprestamo").value= cuanto;
	document.getElementById("items").value= pares;
	xmlhttp.onreadystatechange=cambio_zapatos;
	xmlhttp.open("GET", linea, true);
	xmlhttp.send(null)
}

/*
function cambio_zapatos() {
if (xmlhttp.readyState==4) {
//			document.getElementById('cuota').value = xmlhttp.responseText;	// forma original para uno solo
//			document.getElementById('interes_diferido').value = xmlhttp.responseText;

//	alert('1');
//	var xmlDoc=xmlhttp.responseXML.documentElement;
//	alert('2');
//	alert(xmlDoc);
	// var 
	xmlDoc=xmlhttp.responseXML;
//	alert(xmlhttp.responseXML.getElementsByTagName('cuota')[0].childNodes[0].nodeValue);
//	peticion.responseXML.getElementsByTagName("codigo" )[0];
//	document.getElementById("cuota").value= xmlDoc.getElementsByTagName("cuota")[0].childNodes[0].nodeValue;
	document.getElementById("totalprestamo").value= xmlDoc.getElementsByTagName("cuota")[0].childNodes[0].nodeValue;
	document.getElementById("interes_diferido").value=xmlDoc.getElementsByTagName("interes_diferido")[0].childNodes[0].nodeValue;
	document.getElementById("montoneto").value=xmlDoc.getElementsByTagName("montoneto")[0].childNodes[0].nodeValue;
	document.getElementById("gastosadministrativos").value=xmlDoc.getElementsByTagName("gastosadministrativos")[0].childNodes[0].nodeValue;
	}
}
*/
///////////////////////////
function ajax_call_celulares() {
	xmlhttp=GetXmlHttpObject();
	if (xmlhttp==null)
	  {
		  alert ("Browser does not support HTTP Request");
	  return;
	  }
/*
var indice = 1;
eval("var variable" + indice + " = 'valor'");
alert('la variable 1='+variable1);
*/
	var selIndex = document.getElementById('registros').value;
//	var linea='ajaxCalc2.php?registros=' + selIndex+'&';
	var otralinea='';

	var totalregistros=0;
	var cuanto=0.00;
	var pares =0
	for(j=0; j < selIndex; j++)
	{
		totalregistros++;
		var necesita = eval("document.getElementById('cnt"+(j)+"').value");
		var precio   = eval("document.getElementById('precio"  +(j)+"').value");
		pares += parseInt (necesita);
//		alert(necesita);
		var cuanto = cuanto + (parseFloat (precio) * parseInt (necesita));
	}
// 	alert('sali');
	if ((cuanto > 1000)) //  && (pares > 1)
	{
		document.getElementById("cuotas").value= 2;
		document.getElementById("monto").value= cuanto / 2;
	}
	else 
	{
		document.getElementById("cuotas").value= 1;
		document.getElementById("monto").value= cuanto;
	}
	document.getElementById("totalprestamo").value= cuanto;
	document.getElementById("items").value= pares;
	xmlhttp.onreadystatechange=cambio_celulares;
	xmlhttp.open("GET", linea, true);
	xmlhttp.send(null)
}

function cambio_zapatos() {
if (xmlhttp.readyState==4) {
//			document.getElementById('cuota').value = xmlhttp.responseText;	// forma original para uno solo
//			document.getElementById('interes_diferido').value = xmlhttp.responseText;

//	alert('1');
//	var xmlDoc=xmlhttp.responseXML.documentElement;
//	alert('2');
//	alert(xmlDoc);
	// var 
	xmlDoc=xmlhttp.responseXML;
//	alert(xmlhttp.responseXML.getElementsByTagName('cuota')[0].childNodes[0].nodeValue);
//	peticion.responseXML.getElementsByTagName("codigo" )[0];
//	document.getElementById("cuota").value= xmlDoc.getElementsByTagName("cuota")[0].childNodes[0].nodeValue;
	document.getElementById("totalprestamo").value= xmlDoc.getElementsByTagName("cuota")[0].childNodes[0].nodeValue;
	document.getElementById("interes_diferido").value=xmlDoc.getElementsByTagName("interes_diferido")[0].childNodes[0].nodeValue;
	document.getElementById("montoneto").value=xmlDoc.getElementsByTagName("montoneto")[0].childNodes[0].nodeValue;
	document.getElementById("gastosadministrativos").value=xmlDoc.getElementsByTagName("gastosadministrativos")[0].childNodes[0].nodeValue;
	}
}

/////////////////////////////////////
function ajax_call_motos() {
	xmlhttp=GetXmlHttpObject();
	if (xmlhttp==null)
	  {
		  alert ("Browser does not support HTTP Request");
	  return;
	  }
/*
var indice = 1;
eval("var variable" + indice + " = 'valor'");
alert('la variable 1='+variable1);
*/
	var selIndex = document.getElementById('registros').value;
//	var linea='ajaxCalc2.php?registros=' + selIndex+'&';
	var otralinea='';

	var totalregistros=0;
	var cuanto=0.00;
	var pares =0;
	for(j=0; j < selIndex; j++)
	{
		totalregistros++;
		var necesita = eval("document.getElementById('cnt"+(j)+"').value");
		var precio   = eval("document.getElementById('precio"  +(j)+"').value");
		pares += parseInt (necesita);
//		alert(necesita);
		var cuanto = cuanto + (parseFloat (precio) * parseInt (necesita));
	}
// 	alert('sali');
/*
	if (pares > 2) 
	{
		document.getElementById("cuotas").value= 2;
		document.getElementById("monto").value= cuanto / 2;
	}
	else 
	{
*/
		document.getElementById("cuotas").value= 52;
//		document.getElementById("monto").value= cuanto;
		document.getElementById("monto").value= cuanto / 52;
//	}
	document.getElementById("totalprestamo").value= cuanto;
	document.getElementById("items").value= pares;
	xmlhttp.onreadystatechange=cambio_motos;
	xmlhttp.open("GET", linea, true);
	xmlhttp.send(null)
}

function cambio_motos() {
if (xmlhttp.readyState==4) {
//			document.getElementById('cuota').value = xmlhttp.responseText;	// forma original para uno solo
//			document.getElementById('interes_diferido').value = xmlhttp.responseText;

//	alert('1');
//	var xmlDoc=xmlhttp.responseXML.documentElement;
//	alert('2');
//	alert(xmlDoc);
	// var 
	xmlDoc=xmlhttp.responseXML;
//	alert(xmlhttp.responseXML.getElementsByTagName('cuota')[0].childNodes[0].nodeValue);
//	peticion.responseXML.getElementsByTagName("codigo" )[0];
	document.getElementById("monto").value= xmlDoc.getElementsByTagName("monto")[0].childNodes[0].nodeValue;
	document.getElementById("totalprestamo").value= xmlDoc.getElementsByTagName("cuota")[0].childNodes[0].nodeValue;
	document.getElementById("interes_diferido").value=xmlDoc.getElementsByTagName("interes_diferido")[0].childNodes[0].nodeValue;
	document.getElementById("montoneto").value=xmlDoc.getElementsByTagName("montoneto")[0].childNodes[0].nodeValue;
	document.getElementById("gastosadministrativos").value=xmlDoc.getElementsByTagName("gastosadministrativos")[0].childNodes[0].nodeValue;
	}
}
///////////////////////////
function ajax_call_viajes() {
	xmlhttp=GetXmlHttpObject();
	if (xmlhttp==null)
	  {
		  alert ("Browser does not support HTTP Request");
	  return;
	  }
/*
var indice = 1;
eval("var variable" + indice + " = 'valor'");
alert('la variable 1='+variable1);
*/
	var selIndex = document.getElementById('registros').value;
//	var linea='ajaxCalc2.php?registros=' + selIndex+'&';
	var otralinea='';
	var adulto = 1928.16;
	var menor = 1139.68;
	var infante = 0;

	var totalregistros=0;
	var cuanto=0.00;
	// sumo de una el titular
	var cuanto = cuanto + (parseFloat (adulto) * parseInt(eval("document.getElementById('nochest').value"))) ;
	for(j=0; j < selIndex; j++)
	{
		totalregistros++;
		var nombre = eval("document.getElementById('nombre"+(j)+"').value");
		if (nombre.length >= 4)
		{
			var fecnac = eval("document.getElementById('nacimiento"+(j)+"').value");
			var fecvia = document.getElementById('sel3').value;
			var array_fecnac = fecnac.split("-") 
			var array_fecvia = fecvia.split("-") 
//			alert(fecnac + "-" + fecvia);

/*
			var edad=array_fecvia[0] - array_fecnac[0] - 1; //-1 porque no se si ha cumplido años ya este año
	
		    //si resto los meses y me da menor que 0 entonces no ha cumplido años. Si da mayor si ha cumplido
		    if (array_fecvia[1] + 1 - array_fecnac[1] < 0) //+ 1 porque los meses empiezan en 0
		//       return edad
		    if (array_fecvia[1] + 1 - array_fecnac[1]() > 0)
				edad=edad+1

		    //entonces es que eran iguales. miro los dias
    		//si resto los dias y me da menor que 0 entonces no ha cumplido años. Si da mayor o igual si ha cumplido
			if (array_fecvia[2] - array_fecnac[2] >= 0)
			       edad=edad + 1
			   
*/
			edad = calcular_edad(array_fecnac[2],array_fecnac[1],array_fecnac[0],array_fecvia[2],array_fecvia[1],array_fecvia[0]);
			document.getElementById("edad"+(j)).value= edad;
			var persona = 0;
			if (edad < 4)
			{
				document.getElementById("pago"+(j)).value= 0;
			}
			else 
			if (edad > 12)
			{
				persona = adulto;
				document.getElementById("pago"+(j)).value= persona;
			}
			else {
				persona = menor;
				document.getElementById("pago"+(j)).value= persona;
			}

			var cuanto = cuanto + (parseFloat (persona) * parseInt(eval("document.getElementById('noches"+(j)+"').value"))) ;
		}
	}
	cuanto = cuanto * 1.12;
	if (document.getElementById('tiposocio').value == "Activo")
	{
		var pago1= cuanto *0.5;
		var pago2= cuanto *0.25;
		document.getElementById('observacion').value = 'Primer y 2do pago en Agosto 2014/Diciembre por Bs. '+parseFloat(pago2) + ' / 3er Pago Abril 2015 por Bs. '+parseFloat(pago1)+' c/u';
	}
	else
	{
		var pago1= cuanto *0.5;
		document.getElementById('observacion').value = 'Primer pago en Agosto 2014 por Bs. '+parseFloat(pago1) + ' / 2do Pago en Diciembre 2014 por Bs. '+parseFloat(pago1)+' ';
	}
	
	document.getElementById("totalprestamo").value= parseFloat(cuanto);
	xmlhttp.onreadystatechange=cambio_viajes;
	xmlhttp.open("GET", linea, true);
	xmlhttp.send(null)
}


function calcular_edad(dia_nacim,mes_nacim,anio_nacim, df, mf, af)
{
    fecha_hoy = new Date();
    ahora_anio = af; // fecha_hoy.getYear();
    ahora_mes = mf; // fecha_hoy.getMonth();
    ahora_dia = df; // fecha_hoy.getDate();
    edad = (ahora_anio ) - anio_nacim;
    if ( ahora_mes < (mes_nacim - 1))
    {
      edad--;
    }
    if (((mes_nacim - 1) == ahora_mes) && (ahora_dia < dia_nacim))
    { 
      edad--;
    }
/*
    if (edad > 1900)
    {
    edad -= 1900;
    }
*/
  return edad;
}
////////////////////////