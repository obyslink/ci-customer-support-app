<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="<?=base_url()?>assets/images/codeigniter_logo.png" type="image/x-icon">
	<link rel="stylesheet" id="fsb-image-css" href="<?=base_url()?>assets/css/fullscreen-image.css" type="text/css" media="all">
	<a target="_blank" href="#"><img src="<?=base_url()?>assets/images/bkgd.png" id="fsb_image"></a>

    <title>Customer Support Platform - Login</title>

    <!-- Bootstrap Core CSS -->
    <link href="<?=base_url()?>assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="<?=base_url()?>assets/css/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?=base_url()?>assets/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="<?=base_url()?>assets/css/font-awesome.min.css" rel="stylesheet" type="text/css">


</head>

<body>


	<div class="container">
	    <div class="row">
	        <div class="col-md-4 col-md-offset-4">
	            <div class="login-panel panel panel-default">
	                <div class="panel-heading">
	                    <h3 class="panel-title">Customer Support platform - Please Sign In</h3>
	                </div>
	                <div class="panel-body">
	                	<small id="login-empty-input" class="error">fill in your email and password <br>&nbsp;</small>
	                	<?php if($alert): ?>
	                		<small id="login-invalid-input" class="error">Wrong email or password<br>&nbsp;</small>
	                	<?php endif; ?>

	                    <form role="form" method="post" onsubmit="return checkEmptyInput();" action="<?=base_url()?>authentication/login/">
	                        <fieldset>
	                            <div class="form-group">
	                                <input class="form-control" id="email" placeholder="E-mail" name="email" type="email" autofocus>
	                            </div>
	                            <div class="form-group">
	                                <input class="form-control" id="password" placeholder="Password" name="password" type="password" value="">
	                            </div>
	                            <div class="form-group">
	                                <small><a href="#" onclick="alert('Please contact the administrator to reset your password!')">Forgot Password?</a></small>
	                            </div>
	                            <input id="login-submit" type="submit" value="Login" class="btn btn-lg btn-success btn-block">
	                        </fieldset>
	                    </form>
					</div>
					<div>
					<div class="alert alert-dismissible alert-info">
							<h5>
							<p><strong>LOG IN DETAILS:</strong></p><br/>
									<p>Login as Admin with email:<strong> admin@devjob.com</strong>; password: <strong>Pass</strong> </p>
									<p>or as a First User <strong>user1@devjob.com </strong> </p>
									<p>and Second User <strong>user2@devjob.com</strong> with password: <strong>User1234</strong></p>
							</h5>
						</div>
					</div>
	            </div>
	        </div>
	    </div>
	</div>

	

    <!-- jQuery -->
    <script src="<?=base_url()?>assets/js/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?=base_url()?>assets/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="<?=base_url()?>assets/js/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="<?=base_url()?>assets/js/sb-admin-2.js"></script>

    <script>
    	window.onload = hideLoginErrors();
    	function hideLoginErrors(){
    		$("#login-empty-input").hide();
    	}

		function checkEmptyInput(){
			hideLoginErrors();
			$("#login-invalid-input").hide();
			if( $("#email").val() == '' || $("#password").val() == '' ){
				$("#login-empty-input").show();
				return false;
			}
		}
	</script>

</body>

</html>
