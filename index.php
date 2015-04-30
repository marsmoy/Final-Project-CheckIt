<!DOCTYPE html>

<!-- =========================================== -->
<!-- Welcome to CheckIt, your personal portfolio -->
<!-- Flaherty			Bowditch			Chu  -->
<!-- =========================================== -->


<html lang="en">
<head>
	<meta charset="utf-8" />
    <title>CheckIt</title>
    <link rel="stylesheet" type="text/css" href="CSS/check.css">
 	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script> 
	 <style>
     	.news {
			overflow: auto;
			height: 300px;	
			border: 3px groove blue
 		}
 		p { font-size: xx-small }
 		body{
 			color: white;
 			background: url("https://castlehillview.files.wordpress.com/2015/01/stock-market-3.jpg")
 		}

 	 </style>

  	 <script type="text/javascript">
     
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
			var thename= document.getElementById("last").value;
			
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

	if (isset($_GET['about'])){
		displayAbout();
	}
	if(isset($_GET['create'])){
			displaynewAccountForm();
	}
	displayHome();
		?>

</body>
</html>
<?php
    function displayHome(){
?>
		<h1 class='page-header text-center'>CheckIt Stock Portfolio</h1>
		<form method="get">
			<input class='btn btn-sm btn-default' type="submit" name="about" value="About CheckIt">
		</form>

		<?php
		if (!isset($_COOKIE['email'])){ echo "

		<form method='get' action='http://cscilab.bc.edu/~oconnonx/CheckIt/checkit_signin.php'>
			<input class='btn btn-lg btn-primary' type='submit' name='signin' value='Sign In'>
		</form>
		<form method='get' action='./include/index2.php'>
			<input class='btn btn-sm btn-default' type='submit' name='create' value='Create Account'>
		</form>
		";
		} else { echo "
			<form action='profile.php'>
				<input class='btn btn-sm btn-primary' type='submit' name='signin' value='Profile'>
			</form> ";
				echo "
      		<br><form method='get' action='./include/clearcookies.php'>
        		<input class='btn btn-sm btn-default' type='submit' name='logout' value='Logout'>
      		</form>";
		}

		?>

		<h1><div id="rightwhite"> Your Chat Feed Today! </div></h1><br><br><br>
		<div id="right">
		<div class="form-inline">
			<input class="form-control"type="text" name="textBox" id="textBox" placeholder="Send a message..."/>	
			<button class="form-control form-inline" onclick="sendMessage()">Send</button>
		</div>
		<div id="chat"></div>

		</div>
		<script src="http://cdn.pubnub.com/pubnub-3.7.1.min.js"></script>
		<script src="/~oconnonx/CheckIt/js/main.js"></script>

 <!-- 
 	<img src="https://castlehillview.files.wordpress.com/2015/01/stock-market-3.jpg" alt="main page" height="400" width="500"><br>
		<p>
			Image source: https://zacharydiamond.files.wordpress.com/2014/12/ski-mask-hacker-2.jpg?w=470&h=140&crop=1
		</p>
 -->
	<div class='form-inline'>
			<h1>Some important links</h1><br>
			<a class='form-control' href="https://cs.bc.edu">Here is BC Comp Sci</a><br>
			<a class='form-control' href="http://linkedin.com/in/jflah">Click here to network with me</a><br>
			<a class='form-control' href="https://github.com/JFlah/Final-Project-CheckIt">Our github</a><br> </div>

	<!--RSS below -->

	<?php
	//initialize news source

	$rss_feed = "http://rss.nytimes.com/services/xml/rss/nyt/Business.xml";

	$rss= new SimpleXMLElement(file_get_contents($rss_feed));
	$title = $rss->channel->title;
	echo "<h1 class='text-center'>$title</h1>";
	echo "<div class='container'>";
	$items = $rss->channel->item;
		foreach ($items as $item) {
			echo "<div class='form-control news'>
			<h2>$item->title</h2>\n";
			echo '<a class="form-inline" href="' . $item->link . '">' . $item->title . '</a><br>';
			echo $item->description . "<br><br>\n";
			echo "<br></div>";
		}
	echo "</div>";
	}  
      
function displayAbout(){
?>
	<fieldset>CheckIt is a simple idea. Unlike the messy, intimidating web pages<br>
	one will find on big banking websites meant to confuse and dazzle<br>
	so they can steal your money, CheckIt is simple!<br><br>
	Create your account, add your stocks. Once done, CheckIt will serve<br>
	two basic functions for you. Buy, sell, and research. You can <br>
	search prices, view the big indices, and keep track of your total value.<br>
	I, for one, CheckIt every day!<br><br>
	Copyright 2015 Bowditch, Chu, Flaherty & co.</fieldset>
<?php
}

?>