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

//Query for select tag options
//first query is for mboard
//create an associative array on primary key partID
$mboard_query = "select * from Part where type = 'Motherboard'";
$result = $link->query($mboard_query);
for ($set = array(); $row = $result->fetch_assoc(); $set[array_shift($row)] = $row);
print_r($set);

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
	
<select name="mboard">
	<option value="def">Best Available</option>
</select>

<select name="processor">
	<option value="def">Best Available</option>
</select>

<select name="gpu">
	<option value="def">Best Available</option>
</select>

<select name="psu">
	<option value="def">Best Available</option>
</select>

<select name="opt1">
	<option value="def">Nothing</option>
</select>

<select name="opt2">
	<option value="def">Nothing</option>
</select>

<select name="opt3">
	<option value="def">Nothing</option>
</select>	
	
<input type="submit" name="submit" value="Submit"></form>
<?php
print_r($_SESSION);
?>
</body>
</html>  