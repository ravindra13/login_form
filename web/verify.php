<?php 
	require_once('connectvars.php');
	if(isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash'])){
    // Verify data
    $email = mysql_escape_string($_GET['email']); // Set email variable
    $hash = mysql_escape_string($_GET['hash']); // Set hash variable
	// Connect to the database
    $dbc = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD, DB_NAME) or die('<h1>Something bad happened.</h1></br><h3>Try again later</h3>');
	$query = "SELECT email, hash, active FROM mismatch_user WHERE email='$email' AND hash='$hash' AND active='0'";
	$data = mysqli_query($dbc, $query);
	$match = mysqli_num_rows($data);
	if($match == 1){
		$query = "UPDATE mismatch_user SET active = '1' WHERE email = '$email' AND hash = '$hash' AND active = '0' ";
		mysqli_query($dbc, $query);
		// We have a match, activate the account
		echo '<h3> Your account has been activated </h3>';
	}else{
		echo '<h3>The url is either invalid or you already have activated your account.</h3>';
		// No match -> invalid url or account has already been activated.
	}
	mysqli_close($dbc);
}
else {
	echo '<h3>Invalid approach, please use the link that has been send to your email.</h3>';
}
?>