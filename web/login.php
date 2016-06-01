<?php
  require_once('connectvars.php');

  // Start the session
  session_start();

  // Clear the error message
  $error_msg = "";

  // If the user isn't logged in, try to log them in
  if (!isset($_SESSION['user_id'])) {
    if (isset($_POST['submit'])) {
      // Connect to the database
      $dbc = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD, DB_NAME) or die('<h1>Something bad happened.</h1></br><h3>Try again later</h3>');

      // Grab the user-entered log-in data
      $user_email = mysqli_real_escape_string($dbc, trim($_POST['email']));
      $user_password = mysqli_real_escape_string($dbc, trim($_POST['password']));

      if (!empty($user_email) && !empty($user_password)) {
        // Look up the email and password in the database
        $query = "SELECT user_id, email FROM mismatch_user WHERE email = '$user_email' AND password = SHA('$user_password') AND active = '1'";
        $data = mysqli_query($dbc, $query);

        if (mysqli_num_rows($data) == 1) {
          // The log-in is OK so set the user ID and email session vars (and cookies), and redirect to the home page
          $row = mysqli_fetch_array($data);
          $_SESSION['user_id'] = $row['user_id'];
          $_SESSION['email'] = $row['email'];
          setcookie('user_id', $row['user_id'], time() + (60 * 60 * 24 * 30));    // expires in 30 days
          setcookie('email', $row['email'], time() + (60 * 60 * 24 * 30));  // expires in 30 days
          $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
          header('Location: ' . $home_url);
        }
        else {
          // The email/password are incorrect so set an error message
          $error_msg = 'Sorry, you must enter a valid email and password to log in.';
        }
      }
      else {
        // The email/password weren't entered so set an error message
        $error_msg = 'Sorry, you must enter your email and password to log in.';
      }
    }
  }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Mismatch - Log In</title>
  <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
  <h3>Mismatch - Log In</h3>

<?php
  // If the session var is empty, show any error message and the log-in form; otherwise confirm the log-in
  if (empty($_SESSION['user_id'])) {
    echo '<p class="error">' . $error_msg . '</p>';
?>

  <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <fieldset>
      <legend>Log In</legend>
      <label for="email">Email:</label>
      <input type="text" name="email" value="<?php if (!empty($user_email)) echo $user_email; ?>" /><br />
      <label for="password">Password:</label>
      <input type="password" name="password" />
    </fieldset>
    <input type="submit" value="Log In" name="submit" />
  </form>

<?php
  }
  else {
    // Confirm the successful log-in
    echo('<p class="login">You are logged in as ' . $_SESSION['email'] . '.</p>');
  }
?>

</body>
</html>
