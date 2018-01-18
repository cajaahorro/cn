<!DOCTYPE html>
<html dir="ltr" lang="en-US">
   <head>
      <meta charset="UTF-8" />
      <title>A date range picker for Bootstrap</title>
      <link href="http://netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">
      <link rel="stylesheet" type="text/css" media="all" href="daterangepicker.css" />
      <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
      <script type="text/javascript" src="http://netdna.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
      <script type="text/javascript" src="moment.js"></script>
      <script type="text/javascript" src="daterangepicker.js"></script>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
   </head>
   <body style="margin: 60px 0">

      <div class="container">

        <h1 style="margin: 0 0 20px 0">Configuration Builder</h1>

        <div class="row">

          <div class="col-md-4 col-md-offset-2 demo">
            <h4>Your Date Range Picker</h4>
<input type="text" name="birthdate" value="10/24/1984" />
 
<script type="text/javascript">
$(function() {
    $('input[name="birthdate"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        "format": "DD/MM/YYYY",
			"startDate": "10/02/2016",
			"endDate": "10/08/2016",
			"minDate": "DD/MM/YYYY",
			"maxDate": "DD/MM/YYYY",
			"cancelClass": "btn-warning"
    }, 
    function(start, end, label) {
    	/*
        var years = moment().diff(start, 'years');
        alert("You are " + years + " years old.");
        */
    });
});
</script>
          </div>

          <div class="col-md-6">
            <h4>Configuration</h4>

            <div class="well">
              <textarea id="config-text" style="height: 300px; width: 100%; padding: 10px"></textarea>
            </div>
          </div>

        </div>

      </div>

      <style type="text/css">
      .demo { position: relative; }
      .demo i {
        position: absolute; bottom: 10px; right: 24px; top: auto; cursor: pointer;
      }
      </style>

      script type="text/javascript">
		$('#demo').daterangepicker({
			"singleDatePicker": true,
			"startDate": "10/02/2016",
			"endDate": "10/08/2016",
			"minDate": "DD/MM/YYYY",
			"maxDate": "DD/MM/YYYY",
			"cancelClass": "btn-warning"
		}, function(start, end, label) {
		console.log("New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')");
		});
      $(document).ready(function() {
      });
      </script

   </body>
</html>
