<!DOCTYPE html>
<?php
include ('dbconn.php');
?>
<!-- =========================================== -->
<!-- Welcome to CheckIt, your personal portfolio -->
<!-- Flaherty			Bowditch			Chu  -->
<!-- =========================================== -->


<html lang="en">
<head>
     <meta charset="utf-8" />
     <title>CheckIt</title>
</head>
<body>
	<?php
		init();
	?>
</body>
</html>
<?php
	function init(){
		$dbc = connectToDB();
// 		print_r ($_POST);		
		$email = $_POST['email'];
		$password = $_POST['password'];
		if (validProfile($password,$email,$dbc))
			echo "Valid profile";
 			displayProfile($dbc);
		else 
			echo "fail bruh";
	}
	
	function validProfile($password,$email,$dbc){
		$sha_password = sha1($password);
		$email_query = "select email,password from checkit where email = '$email' and password = '$sha_password'";
		// and password = '$sha_password'";
		$result = performQuery($dbc, $email_query);
		$rows = mysqli_num_rows($result);
		if($rows == 0)
			return false;
		else 
			return true;
	}
    
      function displayProfile($dbc) {
        $profile_query = "select * from checkit where email = '$email' and password = '$sha_password'";
        $result = performQuery($dbc,$profile_query);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        
        $first = $row['first'];
        $last = $row['last'];
        $cash = $row['cash'];
        $stocks = $row['stocks'];
        $email = $row['email'];
        echo "$first $last $cash $stocks $email";
        
        
      }
?>