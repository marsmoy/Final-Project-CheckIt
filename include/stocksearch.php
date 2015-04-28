<?php

$stock = $_GET['search'];

?>

<!DOCTYPE html>

<!-- =========================================== -->
<!-- Welcome to CheckIt, your personal portfolio -->
<!-- Flaherty			Bowditch			Chu  -->
<!-- =========================================== -->


<html lang="en">
<head>
     <meta charset="utf-8" />
     <title>CheckIt Stock Search</title>
</head>
<body>

	<?php
		stockInfo($stock);
	?>
</body>
</html>
<?php

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

?>