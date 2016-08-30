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

	include 'blogConnection.php';
	include "password_hasher/password_compat-master/lib/password.php";

	$log_uname=$log_pword="";
	$log_result=$log_uname_err=$log_pword_err="";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if (empty($_POST['uname'])){
			$log_uname_err='Username is required';
		}else{
			$log_uname=test_input($_POST['uname']);
		}

		if (empty($_POST['pword'])){
			$log_pword_err='Password is required';
		}else{
			$log_pword=test_input($_POST['pword']);
		}
	}

	//check login with database
	$query = "select password, userID, admin
	        			from User
	        			where username = '$log_uname'";

	if($result = $link ->query($query)){
		while($row = $result->fetch_assoc()) {
			$storedPwd = $row["password"];
	                $admin = $row['admin'];
		}
		$result->free();
	}

	if(!empty($storedPwd)){
		if (password_verify($pword, $storedPwd)) {
			$log_result='Login Success!';
			$_SESSION["user"] = $admin;
	                print_r($_SESSION);
		}else{
			$log_result='Password is invalid';
		}
	}else{
                if(!empty($log_uname)){
                         $log_result='Username is invalid';
                }
        }

	function test_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Projects Directory</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<link rel="stylesheet"
	href="../css/font-awesome-4.6.3/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="../styles.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="modalCSS.css">
<style>
.error {
	color: #FF0000;
}
</style>

</head>
<body>
<a href="#openModal">Modal Test</a>

<div id="openModal" class="modalDialog">
<div><a href="#close" title="Close" class="close">X</a>
<h2>Login</h2>
<p><span class="error"><?php echo $log_result;?></span></p>
	<ul class="modalList">
	<li><form method="POST"
		action="http://cameroncole.info/blog/blogModal.php#openModal">
	Username: <input name="uname" type="text" value="<?php echo $log_uname; ?>">
	<span class="error"><?php echo $log_uname_err;?></span> <br>
	Password: <input name="pword" type="password" value="<?php echo $log_pword; ?>">
	<span class="error"><?php echo $log_pword_err;?></span> <br>
		</li><li><input type="submit" value="Submit" ></form></li>
	</ul>
</div>
</div>
<?php
print_r($_SESSION);
?>
</body>
</html>
