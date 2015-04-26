<!DOCTYPE html>
<html>
<head>
	<title>MySQL from PHP</title>
</head>
<body>

<?php

	$dbc= @mysqli_connect("localhost", "bowditcw", "H8zFAA2E", "bowditcw") or
					die("Connect failed3: ". mysqli_connect_error());
					
	
	
	
	
	if ( $_POST['first'] != null ) {
		$first = $_POST['first'];
		$last = $_POST['last'];
		
		if ( $_POST['email'] != null ) {
			$email = $_POST['email'];
			
			$email_query = "select email from checkit where email = '$email'";
			$result = mysqli_query($dbc, $email_query) or die("bad query".mysqli_error($dbc));	
			$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
			$email1 = $row['email'];
			if( $email1 == null ) {
				if ( $_POST['password'] != null) {
					$password = $_POST['password'];
					
					if ( $_POST['password2'] != null) {
						$password2 = $_POST['password2'];
						
						if ($password == $password2) {
							handleform($dbc,$first,$last,$email,sha1($password));
						}
						else echo "Passwords don't match";
					}
					else echo 'No Password2 Entered';
					
				}
				else echo 'No Password Entered';
			}
			else echo 'Email Already Exists';
		}
		else echo 'No Email Entered';
	}
	else echo 'No Name Entered';
	echo "<br><br>";
	echo "<a href='http://cscilab.bc.edu/~oconnonx/CheckIt/index.php'>Back to Home Page</a>";
?>

</body>
</html>
<?php
function performQuery($dbc, $query){
	$result = mysqli_query($dbc, $query) or die("bad query".mysqli_error($dbc));
	return $result;
}


function handleform($dbc,$first,$last,$email,$password){
	$query = "INSERT INTO checkit VALUES ('$first','$last','0.0','', '$password','$email')";
	$result = performQuery($dbc, $query);
	if ( ! $result )
		echo "<br>Oops! Something went wrong";
	else
		echo "<br>Congratulations on joining CheckIt!";
	echo "<a href='http://cscilab.bc.edu/~oconnonx/CheckIt/index.phpt'>Back to Home Page</a>";

}
?>