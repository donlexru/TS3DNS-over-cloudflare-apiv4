<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
     <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="signin.css" rel="stylesheet">	
  </head>
  <body>
  <!-- config.php-->
  	<?
if (isset($_GET['p'])){
        $action=$_GET['p'];
    } else {
        $action='';
    }
	
	switch ($action){
		case "":
            echo "";
            break;
		case 'success':
            require_once('success.php');
            $command=new controller_Welcome();
            break;
		case 'error':
            require_once('error.php');
            $command=new controller_Welcome();
            break;
			
    }
	if($action )
	{
	$command->execute();
	}else{
	}
	error_reporting(1);

include("config.php");

?>
<!-- //config.php-->
    <div class="container">
	<h1>Установка адреса</h1>
<form class="form-inline" action="index.php?p=success" method="POST">
<div class="col-md-12">
  <input type="text" class="form-control mb-4 mr-sm-4 mb-sm-4" id="inlineFormInput" name="subname" placeholder="Адрес (до 25 символов)">

 <select class="custom-select mb-4 mr-sm-4 mb-sm-4" id="inlineFormCustomSelect" name="select">
    <?php
foreach ($domains AS $domain) {
    echo '<option value="' . htmlspecialchars($domain) . '">' . htmlspecialchars($domain) . '</option>';
}
?>
  </select>
  </div>
  
  <div class="col-md-12">
  <select class="custom-select mb-4 mr-sm-4 mb-sm-4" id="inlineFormCustomSelect" name="ip">
								<option value="example.com" text="example.com" name="ip">example.com</option>
								<option value="example2.com" text="example2.com" name="ip">example2.com</option>
  </select>
  
<input type="text" class="form-control mb-4 mr-sm-4 mb-sm-4" id="inlineFormInput" placeholder="Порт" name="port">

  </div>
<div class="col-md-6">
  <input type="hidden" name="do" value="insert">
  <button type="submit" class="btn btn-primary" value="Create Channel">Создать TSDNS запись</button>
</div>
  </form>
    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/bootstrap.js"></script>
  </body>