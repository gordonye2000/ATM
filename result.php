<html>
<head>
<meta http-equiv="refresh" content="600">
<script>
function reloadPage()
  {
  location.reload();
  }
</script>
</head>
<body>

<input type="button" value="Reload page" onclick="reloadPage()">
<br /><br />


<?php
include_once('config.php');

// Get the ATM data 
$atm = atm::getInstance($db);

// update and get the account data 
$account = account::getInstance($db);

if($atm->total < 20 || $atm->fifty <= 0 || $atm->twenty <= 0 ) {
	echo "<br />There is not enough note in ATM! Please reset ATM factory configuration <a href='reset_atm.php' >here</a>.";
	exit();
}

if($account->balance < 20) {
	echo "<br />Your balance is too low! Transfer money to this account to reach account balance to $500, <a href='reset_account.php' >ok?</a>.";
	exit();
}
if($_REQUEST['amount'] > $atm->total) {
	echo "<br />ATM has not enough money! <br /><br />";
	echo 'Please click <a href="result.php">here</a> to reload page.';
	exit();
}

if($_REQUEST['amount'] > $account->balance) {
	echo "<br />Your balance is too low! <br /><br />";
	echo 'Please click <a href="result.php">here</a> to reload page.';
	exit();
}


$output = "";
$fq = 0;
$tq = 0;

if( $_REQUEST['amount'] && $_REQUEST['amount'] >= 20) {
	if( $_REQUEST['amount'] >= 50) { /********* work out $50 first ************/		
		$fQuotient = floor($_REQUEST['amount']/50);
		$fRest = $_REQUEST['amount'] % 50;

		if( $fRest > 0) {  /********* work out $20 next ************/
			$tQuotient = floor($fRest/20);
			$tRest = $fRest % 20;
			if( $tRest > 0) { /********* can not be divided by $20, then reduce one $50, try to divided by $20 again ************/
				$fQuotient_1 = $fQuotient - 1;
				$tQuotient_1 = floor(($fRest+50)/20);
				$tRest_1 = ($fRest+50) % 20;				
				if ($tRest_1 == 0){
					$output = "<br />Done! withdraw " . $fQuotient_1 . " x $50 , " . $tQuotient_1 . " x $20, total: $" . ($fQuotient_1 * 50 + $tQuotient_1 * 20) . ".";
					$fq = $fQuotient_1;
					$tq = $tQuotient_1;
				}else {
					//$output = '<br />' . $fQuotient_1 . ' , ' . $tQuotient_1 . ' , ' . $tRest_1;
					$min = $tQuotient_1 * 20;
					$max = ceil(($fRest+50)/20) * 20;
					$output = "<br />Please withdraw either $" . (($fQuotient_1 * 50) + $min) . " or $" . (($fQuotient_1 * 50) + $max) . ".";
				}
			}
			else {
				$output = "<br />Done! withdraw " . $fQuotient . " x $50 , " . $tQuotient . " x $20, total: $" . ($fQuotient * 50 + $tQuotient * 20) . ".";
				$fq = $fQuotient;
				$tq = $tQuotient;
			}
		}
		else { 
			$output = "<br />Done! withdraw " . $fQuotient . " x $50, total: $" . ($fQuotient * 50) . ".";
			$fq = $fQuotient;
		}
	}
	else if( $_REQUEST['amount'] >= 20 && $_REQUEST['amount'] < 50) {
		$tQuotient = floor($_REQUEST['amount']/20);
		$tRest = $_REQUEST['amount'] % 20;

		if($tRest > 0) {
			$min = $tQuotient * 20;
			$max = ceil($_REQUEST['amount']/20) * 20;
			$output = "<br />Please withdraw either $" . $min . " or $" . $max . ".";
		}
		else { 
			$output = "<br />Done! distribute " . $tQuotient . " x $20, total: $" . ($tQuotient * 20) . ".";
			$tq = $tQuotient;
		}
	}
}
else{
	$output = "<br />Please withdraw at least $20 !";
}


if($atm->fifty < $fq || $atm->twenty < $tq ) {
	echo "<br />There is not enough note in ATM! Please reset ATM factory configuration <a href='reset_atm.php' >here</a>.";
	exit();
}

$withdraw = $fq * 50 + $tq * 20;

if($withdraw > 0) {
	$balance = $account->balance - $withdraw;
	$account->update_account(array('balance'=>$balance));
	$account->_getAccountInfo();
	
	$total = $atm->total - $withdraw;
	$fifty = $atm->fifty - $fq;
	$twenty = $atm->twenty - $tq;
	$atm->update_atm(array('total'=>$total, 'fifty'=>$fifty, 'twenty'=>$twenty));
	$atm->_getATMInfo();
}





echo "<br /> ------------- ATM Detail ---------------------------------<br /><br />";
echo " $50 x " . $atm->fifty . ", subtotal: " . (50*$atm->fifty) . "<br />";
echo " $20 x " . $atm->twenty . ", subtotal: " . (20*$atm->twenty) . "<br />";
echo " Total: " . (50*$atm->fifty + 20*$atm->twenty);

echo "<br /><br /> ----------- Withdraw Result ------------------------------<br /><br />";
echo $output;
echo "<br /><br />Thank you for your business! <br /><br />";
echo " -------------- Gordon's Account Detail --------------------- <br /><br />";

echo "Account Name: " . $account->name . "<br />";
echo "Account Address: " . $account->address . "<br />";
echo "Account Balance: " . $account->balance . "<br /><br />";
echo " ---------------- Withdraw Cash Here ------------------- <br /><br />";
?>

<form action="result.php" method="get">
  withdraw amount: <input type="text" name="amount"><br />
  <input type="submit" value="Submit">
</form> 


</body>
</html>