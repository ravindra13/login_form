<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Mismatch - Sign Up</title>
  <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
  <h3>Mismatch - Sign Up</h3>

<?php
  require_once('appvars.php');
  require_once('connectvars.php');

  // Connect to the database
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die('<h1>Something bad happened.</h1></br><h3>Try again later</h3>');

  if (isset($_POST['submit'])) {
    // Grab the profile data from the POST
    $email = mysqli_real_escape_string($dbc, trim($_POST['email']));
	$mobile = mysqli_real_escape_string($dbc, trim($_POST['mobile']));
	$first_name = mysqli_real_escape_string($dbc, trim($_POST['firstname']));
	$last_name = mysqli_real_escape_string($dbc, trim($_POST['lastname']));
    $password1 = mysqli_real_escape_string($dbc, trim($_POST['password1']));
    $password2 = mysqli_real_escape_string($dbc, trim($_POST['password2']));

    if (!empty($email) && !empty($mobile) && !empty($first_name) && !empty($last_name)&& !empty($password1) && !empty($password2) && ($password1 == $password2)) {
      // Make sure someone isn't already registered using this email
		if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)){
			// Return Error - Invalid Email
			echo '<p class="error">Please give a valid email address</p>';
			$email="";
		}
		
		else if(strlen($password1)<8){
			
			echo '<p class="error">Password must contain at least 8 characters</p>';
			
		} 
		
		
		else{
			// Return Success - Valid Email
		
      $query = "SELECT * FROM mismatch_user WHERE email = '$email'";
      $data = mysqli_query($dbc, $query);
      if (mysqli_num_rows($data) == 0) {
        // The email is unique, so insert the data into the database
		$hash=md5(rand(1,10000));
        $query = "INSERT INTO mismatch_user (email, mobile, first_name, last_name, password, join_date, hash) VALUES ('$email', '$mobile', '$first_name', '$last_name', SHA('$password1'), NOW(), '$hash')";
        mysqli_query($dbc, $query);

        // Confirm success with the email
        echo '<p>Your new account has been successfully created.  <br /> please verify it by clicking the activation link that has been send to your email.' ; //You\'re now ready to <a href="login.php">log in</a>.</p>';
		//writing email
		$to      = $email; // Send email to our user
		$subject = 'Signup | Verification'; // Give the email a subject 
		$message = '
 
		Thanks for signing up!
		Your account has been created.
		Please click this link to activate your account:
		http://www.yourwebsite.com/verify.php?email='.$email.'&hash='.$hash; // Our message above including the link
                     
		$headers = 'From:noreply@yourwebsite.com' . "\r\n"; // Set from headers
		mail($to, $subject, $message, $headers); // Send our email
		
        mysqli_close($dbc);
        exit();
      }
      else {
        // An account already exists for this email, so display an error message
        echo '<p class="error">An account already exists for this email. Please use a different address.</p>';
        $email = "";
      }
		}
    }
    else {
      echo '<p class="error">You must enter all of the sign-up data, including the desired password twice.</p>';
    }
  }

  mysqli_close($dbc);
?>

  <p>Please enter your name, email, mobile no. and desired password to sign up to Mismatch.</p>
  <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <fieldset>
      <legend>Registration Info</legend>
      <label for="email">Email:</label>
      <input type="text" id="email" name="email" value="<?php if (!empty($email)) echo $email; ?>" /><br />
      <label for="mobile">Mobile No.:</label>
      <input type="text" id="mobile" name="mobile" value="<?php if (!empty($mobile)) echo $mobile; ?>" /><br />
      <label for="firstname">First name:</label>
      <input type="text" id="firstname" name="firstname" value="<?php if (!empty($first_name)) echo $first_name; ?>" /><br />
      <label for="lastname">Last name:</label>
      <input type="text" id="lastname" name="lastname" value="<?php if (!empty($last_name)) echo $last_name; ?>" /><br />
      <label for="password1">Password:</label>
      <input type="password" id="password1" name="password1" /><br />
      <label for="password2">Password (retype):</label>
      <input type="password" id="password2" name="password2" /><br />
    </fieldset>
    <input type="submit" value="Sign Up" name="submit" />
  </form>
</body> 
</html>
