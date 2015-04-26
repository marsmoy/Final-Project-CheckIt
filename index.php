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
     
     	function validate(){
			var validFirst = validateFirst();
       		var validLast = validateLast();
			var validEmail = validateEmail();
			var validPassword = validatePassword();
			
			if (!validFirst) return false;
       		if (!validLast) return false;
			if (!validEmail) return false;
			if (!validPassword) return false;
			return true;
		}

		function validateFirst(){
			var thename= document.getElementById("first").value ;
			
			if (thename.length < 1) {
				var errorrpt=document.getElementById("firsterror");
				errorrpt.innerHTML = "Please enter a first name";
				return false;
			} 
			var errorrpt=document.getElementById("firsterror");
			errorrpt.innerHTML = "";
	
			return true;
		}
		
		function validateLast(){
			var thename= document.getElementById("last").value ;
			
			if (thename.length < 1) {
				var errorrpt=document.getElementById("lasterror");
				errorrpt.innerHTML = "Please enter a last name";
				return false;
			} 
			var errorrpt=document.getElementById("lasterror");
			errorrpt.innerHTML = "";
	
			return true;
		}
		
		function validateEmail(){
			var theemail= document.getElementById("email").value ;
			var emailregex=/^[A-Za-z0-9]{1}\\w*@[a-zA-Z]{1}\\w*\\.(com|gov|edu)$/;
			
			if (!emailregex.test(theemail)) {
				var errorrpt=document.getElementById("emailerror");
				errorrpt.innerHTML = "Please enter a valid email";
				return false;
			} 
			var errorrpt=document.getElementById("emailerror");
			errorrpt.innerHTML = "";

			return true;
		}
		
		function validatePassword(){
			var pass1= document.getElementById("password").value ;
			var pass2= document.getElementById("password2").value ;
			
			if (pass1 != pass2) {
				var errorrpt=document.getElementById("passerror");
				errorrpt.innerHTML = "Passwords Don't Match";
				return false;
			} 
		    if (pass1.length < 1) {
				var errorrpt1=document.getElementById("pass1error");
				errorrpt1.innerHTML = "Please enter a password";
				return false;
			} 
			if (thepass2.length < 1) {
				var errorrpt=document.getElementById("passerror");
				errorrpt.innerHTML = "Please enter a password";
				return false;
			} 
			var errorrpt=document.getElementById("passerror");
			errorrpt.innerHTML = "";
			var errorrpt1=document.getElementById("pass1error");
			errorrpt1.innerHTML = "";
	
			return true;
		}
      </script>
</head>
<body>

	<?php
       	displayHome();
		displaynewAccountForm();
		forgotPasswordForm();
	?>
</body>
</html>
<?php
    function displayHome(){
?>
		<h1>CheckIt Stock Portoflio</h1>
		<form method="get">
			<input type="submit" name="about" value="About CheckIt">
		</form>
		<form method="get" action="cscilab.bc.edu/~oconnonx/CheckIt/profile.php">
			<input type="text" name="user" value="username">
			<input type="text" name="pw" value="password">
			<input type="submit" name="login" value="Login">
		</form>
  
	<!--RSS below -->

	<?php
	//initialize news source

	$rss_feed = "http://rss.nytimes.com/services/xml/rss/nyt/Business.xml";

	$rss= new SimpleXMLElement(file_get_contents($rss_feed));
	$title = $rss->channel->title;
	echo "<h1>$title</h1>";
	$items = $rss->channel->item;
		foreach ($items as $item) {
			echo "<div class='news'>
			<h2>$item->title</h2>\n";
			echo '<a href="' . $item->link . '">' . $item->title . '</a><br>';
			echo $item->description . "<br><br>\n";
			echo "<br></div>";
		}
	}  
      
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

    function displaynewAccountForm(){
      ?>
        <fieldset><legend><h4>Create Checkit Account</h4></legend>
          <br><br>
          <form method="post" action = "include/checkit_ops.php" onsubmit = "return validate();">
              <label for="first">Please enter your first name: </label>
              <input type = "text" id = "first" name = "first">
				<span class="ereport" id="firsterror"></span>
            	<br><br>
            	<label for="name">Please enter your last name: </label>
				<input type = "text" id = "last" name = "last">
				<span class="ereport" id="lasterror"></span>
				<br><br>
				<label for="email">Please enter your email: </label>
				<input type = "text" id = "email" name = "email">
				<span class="ereport" id="emailerror"></span>
				<br><br>
				<label for="password">Please enter your password: </label>
				<input type = "password" id = "password" name = "password">
				<span class="ereport" id="pass1error"></span>
				<br><br>
				<label for="password2">Please confirm your password: </label>
				<input type = "password" id = "password2" name = "password2">
				<span class="ereport" id="passerror"></span>
				<br><br>
    			<input type = "submit"  name = "submit_button" value = "Submit">
           </form>
         </fieldset>
<?php
}
function displayAbout(){
?>
	<div>CheckIt is a simple idea. Unlike the messy, intimidating web pages<br>
	one will find on big banking websites meant to confuse and dazzle<br>
	so they can steal your money, CheckIt is simple!<br><br>
	Create your account, add your stocks. Once done, CheckIt will serve<br>
	two basic functions for you. Buy, sell, and research. You can <br>
	search prices, view the big indices, and keep track of your total value.<br>
	I, for one, CheckIt every day!<br><br>
	Copyright 2015 Bowditch, Chu, Flaherty & co.</div>
<?php
}

?>