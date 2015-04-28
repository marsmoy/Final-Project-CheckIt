<?php
include ('include/dbconn.php');
$dbc = connectToDB();
$cookie_email = "email";
$cookie_pass  = "pass";   
if (isset($_COOKIE[$cookie_email]) && isset($_COOKIE[$cookie_pass])){
      $email = $_COOKIE[$cookie_email];
      $password = $_COOKIE[$cookie_pass];
} else {
      $email = $_POST['email'];
      $password = $_POST['password'];
      if (validProfile($password,$email,$dbc)) {
        setcookie($cookie_email, $email, time() + (86400 * 30), "/"); // 86400 = 1 day
        setcookie($cookie_pass, $password, time() + (86400 * 30), "/"); // 86400 = 1 day
      }
}

function init(){
    $cookie_email = "email";
    $cookie_pass  = "pass"; 
    if (isset($_COOKIE[$cookie_email]) && isset($_COOKIE[$cookie_pass])){
      $email = $_COOKIE[$cookie_email];
      $password = $_COOKIE[$cookie_pass];
    } else {
      echo "<a href='http://cscilab.bc.edu/~oconnonx/CheckIt/checkit_signin.php?signin=Sign+In'>Invalid Password back to Login Page</a>";
      die();
    }
    $dbc = connectToDB();
    
    if (validProfile($password,$email,$dbc)) {
      //echo "Valid profile";
      displayProfile($dbc,$email,$password);
    }
    else {
      echo "<a href='http://cscilab.bc.edu/~oconnonx/CheckIt/checkit_signin.php?signin=Sign+In'>Invalid Password back to Login Page</a>";
    }
  }
  
  function validProfile($password,$email,$dbc){
    $sha_password = sha1($password);
    $email_query = "select email,password from checkit where email = '$email' and password = '$sha_password'";
    // and password = '$sha_password'";
    $result = performQuery($dbc, $email_query);
    $rows = mysqli_num_rows($result);
    if($rows == 0)
      return false;
    else {
      return true;
    }
  }
  function stockInfo($stock_name) {
      $page = 'http://finance.yahoo.com/q?s=' . $stock_name;
      $content = file_get_contents($page);
      $stocklower = strtolower($stock_name);
      $value_pattern = "!yfs_l84_$stocklower\">([0-9,]+\.[0-9]*)!";
      $change_pattern = "!yfs_p43_$stocklower\">\\([0-9]{1,2}\\.[0-9]{2}%\\)!";
      
      preg_match_all($value_pattern, $content, $value_res);
      preg_match_all($change_pattern, $content, $change_res);
      
      echo "The entire match for price is: " . htmlentities($value_res[0][0]) . "<br>\n";
      echo "Price is: " . htmlentities($value_res[1][0]) . "<br />\n";

      echo "The entire match for % change is: " . htmlentities($change_res[0][0]) . "<br>\n";
      $change =  htmlentities($change_res[0][0]);
      $change1 = substr($change,-7);
      $x = strpos($change1,"(");
      if($x===FALSE) {
        $change1 = "(" . $change1;
      }

      //$change2 = substr($change1,1,6)
      echo "Percent change is: $change1";
    }
    
      function displayProfile($dbc,$email,$password) {
        $sha_password = sha1($password);
        $profile_query = "select * from checkit where email = '$email' and password = '$sha_password'";
        $result = performQuery($dbc,$profile_query);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        
        $first = $row['first'];
        $last = $row['last'];
        $cash = $row['cash'];
        $stocks = $row['stocks'];
        $stock_array = explode(" ",$stocks);
        $stock_name = array();
        $stock_amount = array();
        
        $i = 1;
        foreach($stock_array as $value){
          if($i%2==0){
            $stock_price[] = $value;
          }
          else{
            $stock_name[] = $value;
          }
          $i= $i+1;
        }
        foreach($stock_name as $value){
          echo "$value";
        }
        
        $email = $row['email'];
        echo "$first $last $cash $stocks $email";

        ?>
        
        <form method='get' action='http://cscilab.bc.edu/~oconnonx/CheckIt/include/stocksearch.php' onsubmit='return validate();'>
          <input id='search' type='text' name='search' value='Enter stock ticker'><br>
          <span class="ereport" id="searcherror"></span><br>
          <input type='submit' name='submit_search' value='Search'>
        </form>
        
        <?php
      }
?>

<!-- =========================================== -->
<!-- Welcome to CheckIt, your personal portfolio -->
<!-- Flaherty     Bowditch      Chu  -->
<!-- =========================================== -->

<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="utf-8" />
     <title>CheckIt</title>
     <script>
           function validate(){
            var validSearch = validateSearch();
            if (!validSearch) return false;

            return true;
          }

          function validateSearch(){
            var thesearch= document.getElementById("search").value ;
            
            if (thesearch.length < 1 || thesearch=='Enter stock ticker') {
              var errorrpt=document.getElementById("searcherror");
              errorrpt.innerHTML = "Please enter a stock ticker";
              return false;
            } 
            var errorrpt=document.getElementById("searcherror");
            errorrpt.innerHTML = "";
        
            return true;
          }
      </script>
</head>
<body>
	<?php
		init();
		stockInfo("ANET");
	?>
</body>
</html>