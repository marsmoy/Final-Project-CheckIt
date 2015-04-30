<?php
$email = $_GET['email'];
if (isset($_GET['search'])){
  $stock = $_GET['search'];
  }
if (isset($_GET['buy_query'])){
  $buy_stock = $_GET['buy_query'];
  }
if (isset($_GET['sell_query'])){
  $sell_stock = $_GET['sell_query'];
  }
if (isset($_GET['cash'])){
  $cash = $_GET['cash'];
}

?>

<!DOCTYPE html>

<!-- =========================================== -->
<!-- Welcome to CheckIt, your personal portfolio -->
<!-- Flaherty     Bowditch      Chu  -->
<!-- =========================================== -->


<html lang="en">
<head>
     <meta charset="utf-8" />
     <title>CheckIt Stock Search</title>
      <script type="text/javascript">
      
    function validateBuy(){
      var thebuy = document.getElementById("buy").value ;
      var buy_regex = /^\d+$/;
      if (thebuy.length < 1 || !buy_regex.test(thebuy) ) {
        var errorrpt=document.getElementById("buyerror");
        errorrpt.innerHTML = "Please enter a valid amount to buy";
        return false;
      } 
      var errorrpt=document.getElementById("buyerror");
      errorrpt.innerHTML = "";
  
      return true;
    }
    
    function validateSell(){
      var thesell = document.getElementById("sell").value ;
      var sell_regex = /^\d+$/;
      if (thesell.length < 1 || !thsell.test(thebuy) ) {
        var errorrpt=document.getElementById("sellerror");
        errorrpt.innerHTML = "Please enter a valid amount to buy";
        return false;
      } 
      var errorrpt=document.getElementById("sellerror");
      errorrpt.innerHTML = "";
  
      return true;
    }
    </script>
</head>
<body>

  <?php
    if( isset($stock)) {
      if(isValid($stock)){
        searchDisplay($stock);
      }
    }
    if( isset($buy_stock)) {
      if(isValid($buy_stock)) {
        buyDisplay($buy_stock,$email);
      }
    }
    if( isset($sell_stock)) {
      if(isValid($sell_stock)) {
        $stocks = $_GET['stocks'];
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
            }
        sellDisplay($sell_stock,$email,$stock_name);
      }
    }
        
      
    
  ?>
</body>
</html>
<?php
  function isValid($stock_name) {
      $page = 'http://finance.yahoo.com/q?s=' . $stock_name;
      $content = file_get_contents($page);
      $stocklower = strtolower($stock_name);
      $value_pattern = "!yfs_l84_$stocklower\">([0-9,]+\.[0-9]*)!";
      $change_pattern = "!yfs_p43_$stocklower\">\\([0-9]{1,2}\\.[0-9]{2}%\\)!";
      
      preg_match_all($value_pattern, $content, $value_res);
      preg_match_all($change_pattern, $content, $change_res);

      $error_pattern = "!no result!";

      preg_match_all($error_pattern, $content, $error_res);


      if (!isset($value_res[0][0])) {
        echo "Invalid stock ticker, please
            <a href='http://cscilab.bc.edu/~oconnonx/CheckIt/profile.php'>Try again</a>";
        return False;
      }
      else {
        return True;
        }}
        
        
        
        

  function stockInfo($stock_name) {
      $page = 'http://finance.yahoo.com/q?s=' . $stock_name;
      $content = file_get_contents($page);
      $stocklower = strtolower($stock_name);
      $value_pattern = "!yfs_l84_$stocklower\">([0-9,]+\.[0-9]*)!";
      $change_pattern = "!yfs_p43_$stocklower\">\\([0-9]{1,2}\\.[0-9]{2}%\\)!";
      
      preg_match_all($value_pattern, $content, $value_res);
      preg_match_all($change_pattern, $content, $change_res);

      $error_pattern = "!no result!";

      preg_match_all($error_pattern, $content, $error_res);

      //echo "Error res is " . $error_res . "<br>";
      //echo "error res [o][o] is " . $error_res[0][0] . "<br>";

      if (!isset($value_res[0][0])) {
        die("Invalid stock ticker, please
            <a href='http://cscilab.bc.edu/~oconnonx/CheckIt/index.php'>Try again</a>");
      }
      
        $change =  htmlentities($change_res[0][0]);
        $change1 = substr($change,-7);
    $x = strpos($change1,"(");
        if($x===FALSE) {
          $change1 = "(" . $change1;
        }

    $price = htmlentities($value_res[1][0]);
    
      
        return array ($price,$change1);
    }
    
    function searchDisplay($stock){
      $tuple = stockInfo($stock);
      $stock = strtoupper($stock);
      echo "The stock $stock is $tuple[0] with a change of $tuple[1]<br><br>";

      $img_src = 'http://chart.finance.yahoo.com/t?s=' . $stock . '&lang=en-US®ion=US&width=600&height=360';

      echo "<img src=$img_src >";

      echo "<br><a href='../profile.php'>Return to your Profile</a>";
      }
      
    function buyDisplay($stock,$email){
      $tuple = stockInfo($stock);
      $stock = strtoupper($stock);
      //echo $email;
      echo "The stock $stock is $tuple[0] with a change of $tuple[1]";

      $img_src = 'http://chart.finance.yahoo.com/t?s=' . $stock . '&lang=en-US®ion=US&width=600&height=360';

      echo "<br><img src=$img_src >";

      $cash = $_GET['cash'];
      ?>
      <form method = "get" action = "checkit_ops.php" onsubmit='return validateBuy();'>
        <input type="hidden" name="stock" value="<?php echo $stock ?>"> 
        <input type="hidden" name="email" value="<?php echo $email ?>">
        <input type="hidden" name="cash" value= "<?php echo $cash ?>">
        <label for="buy">How many stocks do you want to buy?: </label>
        <input type = "text" id = "buy" name = "buy">
        <span class="ereport" id="buyerror"></span>
        <input type = "submit"  name = "buy_submit" value = "Buy">
        <a href="../profile.php">Profile</a>
    </form>
        <?php
      }
      
    function sellDisplay($stock,$email,$stock_name){
      $tuple = stockInfo($stock);
      $stock = strtoupper($stock);
      echo "The stock $stock is $tuple[0] with a change of $tuple[1]";

      $img_src = 'http://chart.finance.yahoo.com/t?s=' . $stock . '&lang=en-US®ion=US&width=600&height=360';

      echo "<br><img src=$img_src >";

      $previously_owned = false;
    foreach($stock_name as $name){
      if(strcmp($name, $stock) == 0){
        $previously_owned = true;
      }
    }
    if(!$previously_owned){
      echo "<br>You cannot sell $stock because you do not own any shares.";
      echo "<br><a href='../profile.php'>Return to your Profile</a>";
      }
    else{
        ?>
          <form method = "get" action = "checkit_ops.php" onsubmit='return validateSell();'>
            <input type="hidden" name="stock" value="<?php echo $stock ?>"> 
            <input type="hidden" name="email" value="<?php echo $email ?>">
            <input type="hidden" name="cash" value= "<?php echo $cash ?>">
            <label for="sell">How many stocks do you want to sell?: </label>
          <input type = "text" id = "sell" name = "sell">
          <span class="ereport" id="sellerror"></span>
          <input type = "submit"  name = "sell_submit" value = "Sell"><br>
          <a href="../profile.php">Profile</a>
        </form>
      <?php
      }
      }

?>