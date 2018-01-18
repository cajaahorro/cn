<head>
<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="../bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" media="screen"> 
<script type="text/javascript" src="../bootstrap/js/jquery3.js"></script>
script type="text/javascript" src="validation.min.js"></script>
link href="style.css" rel="stylesheet" type="text/css" media="screen">
script type="text/javascript" src="script.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
</head>
<?php
	echo "<input type='submit' class=\"btn btn-primary\" id='Buscar' value='Buscar' />";
srand(time()); //Introducimos la "inicial"
$aleat = rand(0,9999); //rand(mínimo y el máximo);
echo $aleat.'<br>';
echo '<div class="container">';

for ($x=0;$x<=9;$x++)
{
	$digits_needed=1;
	$random_number=''; // set up a blank string
	$count=0;
	echo '<div class="hidden-xs col-xs-12 col-sm-8 col-md-6">'; // hidden-sm 
	while ( $count < $digits_needed ) {
	    $random_digit = mt_rand(0, 9);
	    echo $random_number;
	    $random_number .= $random_digit;
	    $count++;
	}
	echo '</div>';

	// echo "The random $digits_needed digit number is $random_number<br>";

}

?>
<script type="text/javascript">
setInterval(function(){
$.ajax({ url:'generator.php', cache : false, success: function(data){
    document.write(data.foo);
}, dataType: "json"});
}, 3000);   
</script>

<?php
//for ($y=0;$y<=8;$y++)
for ($z=0;$z<=8;$z++)
{
$x = rand(0,9);
$array = array(
    //'foo'  => $x  
    $x
);
echo json_encode($array);
}
echo '<br>';


?>
 


<script src="http://code.jquery.com/jquery-latest.js" type="text/javascript"></script>

<script type="text/javascript">

$(function () {
    
     var numMin =  '345';
     var numMax = '26654';
     
     var adjustedHigh = (parseFloat(numMax) - parseFloat(numMin)) + 1;
     
     
     var numRand = Math.floor(Math.random() * adjustedHigh) + parseFloat(numMin);
     
     
     if ((IsNumeric(numMin)  && (IsNumeric(numMax)) && (parseFloat(numMin) <= parseFloat(numMax )) && (numMin != '') && (numMax != ''))) {
         $("#randomnumber").val('DTKT'+numRand);
     }
});

function IsNumeric(n){
    return !isNaN(n);
}

</script>


<input type="text" name="txtTicketId" id="randomnumber" /> 


------
puede ser
<div id="outerBox">
<div id="quoteBox">
<div id="textBox">
<p id="quoteText">text text text</p>
</div>
</div>
</div>

<div id="buttonOuterBox">
<div id="newQuoteButton">
  <p>Get another quote</p>
</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
  var quotes = ["quote 1", "quote 2", "quote 3", "quote 4", "quote 5"];
  var counter = 0;

  $("#newQuoteButton").click(function() {
    $("#quoteText").text(quotes[counter]);
    counter++;
    if (counter == 5) {
      counter = 0;
    }
  });
});
</script>

