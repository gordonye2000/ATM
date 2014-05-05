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

/**** https://github.com/gordonye2000/ATM.git  ***/

include_once('config.php');

// Get the ATM data 
$atm = atm::getInstance($db);

// Get the account data 
$account = account::getInstance($db);

echo " ---------- ATM Detail ---------<br /><br />";
echo " $50 x " . $atm->fifty . ", subtotal: " . (50*$atm->fifty) . "<br />";
echo " $20 x " . $atm->twenty . ", subtotal: " . (20*$atm->twenty) . "<br />";
echo " Total: " . (50*$atm->fifty + 20*$atm->twenty) . "<br /><br />";

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