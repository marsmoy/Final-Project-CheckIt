<!DOCTYPE html>

<!-- =========================================== -->
<!-- Welcome to CheckIt, your personal portfolio -->
<!-- Flaherty			Bowditch			Chu  -->
<!-- =========================================== -->


<html lang="en">
<head>
     <meta charset="utf-8" />
     <title>CheckIt</title>
  	 <script type="text/javascript">
        function emailValidate(){
			var theemail = document.getElementById("email1").value ;
			if (theemail.length < 1) {
				var errorrpt=document.getElementById("email1error");
				errorrpt.innerHTML = "Please enter an email";
				return false;
			} 
			var errorrpt=document.getElementById("email1error");
			errorrpt.innerHTML = "";
	
			return true;
		}
		
	
      </script>
</head>
<body>

	<?php
		forgotPasswordForm();
	?>
</body>
</html>
 
<?php
    function forgotPasswordForm(){
?>
		<fieldset><legend><h4>Enter Information for New Password</h4></legend>
			<br><br>
			<form method = "get" action = "include/checkit_password.php" onsubmit = "return emailValidate();">
				<label for="email">Please enter your email: </label>
				<input type = "text" id = "email1" name = "email1">
				<span class="ereport" id="email1error"></span>
				<br><br>
				<input type = "submit"  name = "submit_button" value = "Email New Password" >
			</form>
		</fieldset>
		<?php
	}
?>