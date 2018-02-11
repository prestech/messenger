<?php session_start() ?>
<!DOCTYPE html>
<html>
<head>

	<title>welcome</title>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

 	<script
	  src="http://code.jquery.com/jquery-3.3.1.js"
	  integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
	  crossorigin="anonymous"></script>

	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

	<!-- Specify required php scrips-->
	<?php
		#require $_SERVER['DOCUMENT_ROOT']."/env_params/php_path_constants.php";
		$login_script = $_SERVER['DOCUMENT_ROOT']."model/php/model/login_service.php";
		
		//print_r($login_script);
		//By removing session variable, user will be forced to sign in with the username and password
		//remove session variable
		session_unset();
		session_destroy();

	?>

	<style type="text/css">
		body{
			background: #6F256F;
		}
		.login_div{

			background: red;
			display: flex;   
		}
		#login_div{
			position: relative;
			background: #DFDDDE;
			padding: 5% 1% 5% 1%;
			margin-top: 10%;
			height: 60%;
			width: 60%;
			max-height: 550px;
			max-width: 450px;			
		}

		#form_div div{
			margin-bottom: 5%;
		}
		#facebook_gmail_div{
			margin-left: 1%;
			margin-right: 1%;
			margin-bottom: 10%;
			margin-top: 10%;
		}

	</style>



</head>


<body>
<!--LOGIN INPUT -->
	<div class="container" id=login_div>
				
		<div id="form_div">
		   <form class="form-horizontal" id="login_form" action = '<?php echo "/php/service/login_service.php" ?>' method="POST">
				

				<div>
					<label for="username"> Username: </label>
					<input class="form-control" type="text" name="username" id="username" required>
				</div>

				<div>
					<label for="password">Password</label>
					<input class="form-control" type="password" name="password" id="password" required>
				</div>

				<div class="checkbox">
					<label for="checkbox">
					<input type="checkbox" name="checkbox" id="checkbox" > 
					Remember me</label>

				</div>

				<div>
					<input class="btn btn-primary btn-block" value="login" type="submit" style="background:#6F256F">

				</div>
		

			<!--link to signup page --> 
			<div>Don't have an account? <a href="<?php #echo SIGNUP_PAGE ?>">create account</a>
			</div>

		</form>	
	 </div>
  </div>
</body>
</html>
