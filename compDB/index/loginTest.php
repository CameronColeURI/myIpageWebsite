<?php
ini_set("session.save_handler", "files");
session_save_path('/home/users/web/b2862/ipg.cameroncoleinfo/cgi-bin/tmp');
session_name('PHPSESSID');
session_start();

echo session_name();
echo session_id();
if (!is_writable(session_save_path())) {
    echo 'Session path "'.session_save_path().'" is not writable for PHP!'; 
}

include 'connection.php';
include "password_hasher/password_compat-master/lib/password.php";

//define variables and set to empty values

$uname=$pword="";
$uname_err=$pword_err="";

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
}

//check login with database
$query = "select password, custID, admin
        			from Customer
        			where username = '$uname'";

if($result = $link ->query($query)){
	while($row = $result->fetch_assoc()) {
		$storedPwd = $row["password"];
                $admin = $row['admin'];
	}
	$result->free();
}

if(!empty($storedPwd)){
	if (password_verify($pword, $storedPwd)) {
		echo "<h2>login succeeded</h2>";
		$_SESSION["user"] = $admin;
                print_r($_SESSION);
	}else{
		echo "<h2>login failed</h2>";
	}
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
<h1>Login Test</h1>
<p><span class="error">* required field.</span></p>
<form method="POST"
	action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
Username: <input name="uname" type="text" value="<?php echo $uname; ?>">
<span class="error">* <?php echo $uname_err;?></span> <br>
Password: <input name="pword" type="text" value="<?php echo $pword; ?>">
<span class="error">* <?php echo $pword_err;?></span> <br>
<input type="submit" name="submit" value="Submit"></form>
<?php
print_r($_SESSION);
?>
</body>
</html>
