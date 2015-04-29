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
        $email = $_POST['email'];
        $password = $_POST['password'];
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
      
    $price = htmlentities($value_res[1][0]);
  
        $change =  htmlentities($change_res[0][0]);
        $change1 = substr($change,-7);
    $x = strpos($change1,"(");
        if($x===FALSE) {
          $change1 = "(" . $change1;
        }

        return array ($price,$change1);

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
        if(strcmp($stocks,"")==0){
          echo "You do not own any stocks!";
        }
        else{
           $stock_array = explode(" ",$stocks);
        $stock_name = array();
        $stock_owned = array();
        $i = 1;
        foreach($stock_array as $value){
          if($i%2==0){
            $stock_owned[] = $value;
          }
          else{
            $stock_name[] = $value;
          }
          $i= $i+1;
        }$name_price = array();
          $name_change = array();
          foreach($stock_name as $value){
            $type = stockInfo($value);
            $name_price[$value] = $type[0];
            $name_change[$value] = $type[1];
          }

      echo "Hello $first $last!<br>\n";

      ?>
      <fieldset>
      <legend>Stock Table</legend>
      <table>
        <tr>
          <th>Ticker</th>
          <th>Value</th>
          <th>Change</th>
          <th>Amount Owned</th>
        </tr>
      <?php
      $sum = 0;
      $total_change = 0;
      for ( $i = 0; $i < sizeof($stock_name); $i++ ) {
        $stock = $stock_name[$i];
        $sum = $sum + ($name_price[$stock])*($stock_owned[$i]);
        $total_change = $total_change + substr($name_change[$stock],1,strlen($name_change[$stock])-1);
        echo "<tr>
              <td>$stock</td><td>$name_price[$stock]</td><td>$name_change[$stock]</td><td>$stock_owned[$i]</td>
            </tr>\n";
      }
      $average_change = $total_change/sizeof($stock_name);
      $average_change = substr($average_change,0,4);
      ?>

      </table>
      </fieldset>
      <?php

      echo "
        <br><form method='get' action='./index.php'>
            <input type='submit' value='Home Page'>
        </form>
      ";
      
      echo "
      <br><form method='get' action='./include/clearcookies.php'>
        <input type='submit' name='logout' value='Logout'>
      </form>
      ";
        $name_price = array();
          $name_change = array();
          foreach($stock_name as $value){
            $type = stockInfo($value);
            $name_price[$value] = $type[0];
            $name_change[$value] = $type[1];
          }

      ?>
    
      <?php
      
      
      echo "Portfolio Value: &#36;$sum<br>\n";
      echo "Average Change: $average_change&#37;<br>\n";
        }
       
        //print_r($stock_name);
      ?>
    Buy Stock
      <form method='get' action='include/stocksearch.php' onsubmit='return validate2();'>
        <input type="hidden" name="email" value= "<?php echo $email ?>">
        <input type="hidden" name="cash" value= "<?php echo $cash ?>">
            <input id='buy_query' type='text' name='buy_query' placeholder='Enter stock ticker'><br>
            <span class="ereport" id="searcherror2"></span><br>
            <input type='submit' name='buy_search' value='Buy'>
        </form>
        <br>


    <div id="right">
      <h5> Your Chat Feed Today! </h5>
      <input type="text" name="textBox" id="textBox" placeholder="Send a message..."/>
      <button onclick="sendMessage()">Send</button>
      <div id="chat"></div>
    </div>
      <script src="http://cdn.pubnub.com/pubnub-3.7.1.min.js"></script>
      <script src="/~oconnonx/CheckIt/js/main.js"></script>

        <br>
        Sell Stock
      <form method='get' action='include/stocksearch.php' onsubmit='return validate3();'>
        <input type="hidden" name="email" value= "<?php echo $email ?>">
        <input type="hidden" name="cash" value= "<?php echo $cash ?>">
        <input type="hidden" name="stocks" value= "<?php echo $stocks ?>">
            <input id='sell_query' type='text' name='sell_query' placeholder='Enter stock ticker'><br>
            <span class="ereport" id="searcherror3"></span><br>
            <input type='submit' name='sell_search' value='Sell'>
        </form>
        <br><br>
        Search Stock
        <form method='get' action='include/stocksearch.php' onsubmit='return validate();'>
          <input type="hidden" name="email" value= "<?php echo $email ?>">
            <input id='search' type='text' name='search' placeholder='Enter stock ticker'><br>
            <span class="ereport" id="searcherror"></span><br>
            <input type='submit' name='submit_search' value='Search'>
        </form><br>

        <form method='get' action='include/cash_ops.php'>
            <input type="text" name="amount" placeholder="Enter amount">
            <input type="submit" name="deposit" value="Deposit">
            <input type="submit" name="withdraw" value="Withdraw">
            <input type="hidden" name="cash" value= "<?php echo $cash ?>">
            <input type="hidden" name="email" value="<?php echo $email ?>">

        </form>
        <?php
          
    echo "<br>Cash: &#36;$cash<br>\n";
        
          
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
     <link rel="stylesheet" type="text/css" href="CSS/check.css">
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
          
          function validate2(){
            var validSearch = validateSearch2();
            if (!validSearch) return false;

            return true;
          }

          function validateSearch2(){
            var thesearch= document.getElementById("buy_query").value ;
            
            if (thesearch.length < 1 || thesearch=='Enter stock ticker') {
              var errorrpt=document.getElementById("searcherror2");
              errorrpt.innerHTML = "Please enter a stock ticker";
              return false;
            } 
            var errorrpt=document.getElementById("searcherror2");
            errorrpt.innerHTML = "";
        
            return true;
          }
          
          function validate3(){
            var validSearch = validateSearch3();
            if (!validSearch) return false;

            return true;
          }

          function validateSearch3(){
            var thesearch= document.getElementById("sell_query").value ;
            
            if (thesearch.length < 1 || thesearch=='Enter stock ticker') {
              var errorrpt=document.getElementById("searcherror3");
              errorrpt.innerHTML = "Please enter a stock ticker";
              return false;
            } 
            var errorrpt=document.getElementById("searcherror3");
            errorrpt.innerHTML = "";
        
            return true;
          }
      </script>
</head>
<body>
  <?php
    init();
  ?>
</body>
</html>