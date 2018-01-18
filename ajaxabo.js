var xmlhttp;

function amor_cap() {
	xmlhttp=GetXmlHttpObject();
	if (xmlhttp==null)
	  {
		  alert ("Browser does not support HTTP Request");
	  return;
	  }
	var selIndex= document.getElementById('registros').value;
	var linea='ajaxabo.php?registros=' + selIndex+'&';
	var otralinea='';
//	alert(linea);
	var totalregistros=0;
	for(j=0; j < selIndex; j++){			
			var valor = eval("document.getElementById('cancelar"+(j+1)+"').checked");
//			alert(valor2);
			if (valor == true) {
				
				totalregistros++;
				var valor2 = eval("document.getElementById('cancelar"+(j+1)+"').value");
				otralinea=otralinea+'cancelar'+(totalregistros)+'='+valor2+'&'
			}
        }
//		alert(otralinea);

	linea=linea+otralinea+'totalregistros='+totalregistros+'&';
	// alert(linea);
	xmlhttp.onreadystatechange=cambio2;
	xmlhttp.open("GET", linea, true);
	xmlhttp.send(null)
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

function cambio2() {
if (xmlhttp.readyState==4) {
	xmlDoc=xmlhttp.responseXML;
	document.getElementById("totalnominas").value= xmlDoc.getElementsByTagName("totalnominas")[0].childNodes[0].nodeValue;
	document.getElementById("totalregistros").value= xmlDoc.getElementsByTagName("totalregistros")[0].childNodes[0].nodeValue;
/*
document.getElementById("netoarecibir").value= xmlDoc.getElementsByTagName("neto")[0].childNodes[0].nodeValue;
	document.getElementById("montoprestamo").value= xmlDoc.getElementsByTagName("montoprestamo")[0].childNodes[0].nodeValue;
	document.getElementById("descuentosadm").value= xmlDoc.getElementsByTagName("descuentosadm")[0].childNodes[0].nodeValue;
	document.getElementById("marcados").value= xmlDoc.getElementsByTagName("marcados")[0].childNodes[0].nodeValue;
	document.getElementById("reintegros").value= xmlDoc.getElementsByTagName("reintegros")[0].childNodes[0].nodeValue;
	if (document.getElementById("netoarecibir").value <= 0)
		alert('Verifique los datos. El neto a recibir no puede ser menor o igual a cero');
//	document.getElementById("interes_diferido").value=xmlDoc.getElementsByTagName("interes_diferido")[0].childNodes[0].nodeValue;
//	document.getElementById("montoneto").value=xmlDoc.getElementsByTagName("montoneto")[0].childNodes[0].nodeValue;
//	document.getElementById("gastosadministrativos").value=xmlDoc.getElementsByTagName("gastosadministrativos")[0].childNodes[0].nodeValue;
*/
}
}

