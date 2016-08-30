<?php
include 'connection.php';
include "password_hasher/password_compat-master/lib/password.php";

$uname=$pword=$conf_pword=$fname=$lname=$email=$pnum=$staddr=$city=$state='';
$uname_err=$pword_err=$conf_pword_err=$fname_err=$lname_err=$email_err=$pnum_err=$staddr_err=$city_err=$state_err='';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (empty($_POST['uname'])){
		$uname_err='Username is required';
	}else{
		$uname=test_input($_POST['uname']);
	}

	if (empty($_POST['pword'])){
		$pword_err='Password is required';
	}else{
		$pword=test_input($_POST['pword']);
	}
	
if (empty($_POST['conf_pword'])){
		$conf_pword_err='Please re-type password to confirm';
	}else{
		$conf_pword=test_input($_POST['conf_pword']);
		
		if($pword!=$conf_pword)
		{
			$conf_pword_err = 'Passwords do not match';
			$pword = '';
			$conf_pword = '';
		}
	}

	if (empty($_POST['fname'])){
		$fname_err='First name is required';
	}else{
		$fname=test_input($_POST['fname']);
	}

	if (empty($_POST['lname'])){
		$lname_err='Last name is required';
	}else{
		$lname=test_input($_POST['lname']);
	}

	if (empty($_POST['email'])){
		$email_err='Email is required';
	}else{
                $email = $_POST['email'];
		if (filter_var($email, FILTER_VALIDATE_EMAIL)){
			$email=$_POST['email'];
		}else{
			$email_err='Email is invalid';
                        $email = '';
		}
	}

	//phone number not required
	$pnum = $_POST['pnum'];

	if (empty($_POST['staddr'])){
		$staddr_err='Street address is required';
	}else{
		$staddr=test_input($_POST['staddr']);
	}

	if (empty($_POST['city'])){
		$city_err='City is required';
	}else{
		$city=test_input($_POST['city']);
	}

	if (empty($_POST['state'])){
		$state_err='State is required';
	}else{
		$state=test_input($_POST['state']);
	}
}
// Check to make sure the username is not already in use
// If it is, display already in use message
// If not, hash the password and insert the new customer into the database
$query = "select * from Customer where username = '$uname'";
if($result = $link ->query($query)){
	while($row = $result->fetch_assoc()) {
		$is_user = $row["username"];
	}
	$result->free();
}
//username not taken
if(empty($is_user)){
	$query = "select RIGHT(max(custID),3) as num from Customer";
	if($result = $link ->query($query)){
		while($row = $result->fetch_assoc()) {
			$custNum = $row["num"];
		}
		$result->free();
	}
	
	$newID = (integer)$custNum + 1;
	
	$numLength = strlen((string)$newID);
	if($numLength == 1){
		$newID = 'cust00'. $newID;
	}else if ($numLength == 2){
		$newID = 'cust0' . $newID;
	}else{
		$newID = 'cust' . $newID;
	}

	$hashed_pword = password_hash("$pword", PASSWORD_BCRYPT);

	$query = "insert into Customer
        				(custID, username, password, fname, lname, pnum, staddr, city, state, admin, email)
        				values ('$newID', '$uname', '$hashed_pword', '$fname', '$lname','$pnum','$staddr','$city','$state', 0, '$email')";
	if ($link->query($query) === TRUE) {
		echo "New record created successfully";
	} else {
		echo "Error: " . $query . "<br>" . $link->error;
	}
}else{
echo "username taken";
}

function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}
?>
<!DOCTYPE HTML>
<html>
<head>
<style>
.error {
	color: #FF0000;
}
</style>
</head>
<body>

<h1>Register</h1>
<p><span class="error">* required field.</span></p>
<form method="POST"
	action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">

Username: <input name="uname" type="text" value="<?php echo $uname; ?>">
<span class="error">* <?php echo $uname_err;?></span> <br>

Password: <input name="pword" type="text" value="<?php echo $pword; ?>">
<span class="error">* <?php echo $pword_err;?></span> <br>

Confirm Password: <input name="conf_pword" type="text"
	value="<?php echo $pword; ?>"> <span class="error">* <?php echo $conf_pword_err;?></span>
<br>

First Name: <input name="fname" type="text"
	value="<?php echo $fname; ?>"> <span class="error">* <?php echo $fname_err;?></span>
<br>

Last Name: <input name="lname" type="text" value="<?php echo $lname; ?>">
<span class="error">* <?php echo $lname_err;?></span> <br>

Email: <input name="email" type="text" value="<?php echo $email; ?>"> <span
	class="error">* <?php echo $email_err;?></span> <br>

Phone Number: <input name="pnum" type="text"
	value="<?php echo $pnum; ?>"> <span class="error">* <?php echo $pnum_err;?></span>
<br>

Street Address: <input name="staddr" type="text"
	value="<?php echo $staddr; ?>"> <span class="error">* <?php echo $staddr_err;?></span>
<br>

City: <input name="city" type="text" value="<?php echo $city; ?>"> <span
	class="error">* <?php echo $city_err;?></span> <br>

State: <input name="state" type="text" value="<?php echo $state; ?>"> <span
	class="error">* <?php echo $state_err;?></span> <br>

<input type="submit" name="submit" value="Submit"></form>

</body>
</html>
