<?php

session_start();

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
  header("location: ../index.php");
  exit;
}

// Include config file
require_once "../dbconnection.php";

$email = $password = "";
$email_err = $password_err = $login_err = "";

$sql_get_email = "SELECT email, password FROM users WHERE email = ?";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty(trim($_POST["email"]))) {
    $email_err = "Please enter email.";
  } else {
    $email = trim($_POST["email"]);
  }
  if (empty(trim($_POST["password"]))) {
    $password_err = "Please enter your password.";
  } else {
    $password = trim($_POST["password"]);
  }
  if (empty($email_err) && empty($password_err)) {

    if ($stmt_get_email = mysqli_prepare($link, $sql_get_email)) {
      mysqli_stmt_bind_param($stmt_get_email, "s", $email);
      if (mysqli_stmt_execute($stmt_get_email)) {
        mysqli_stmt_store_result($stmt_get_email);
        if (mysqli_stmt_num_rows($stmt_get_email) == 1) {
          mysqli_stmt_bind_result($stmt_get_email, $email, $hashed_password);
          if (mysqli_stmt_fetch($stmt_get_email)) {
            if (password_verify($password, $hashed_password)) {
              session_start();
              $_SESSION["loggedin"] = true;
              $_SESSION["email"] = $email;

              header("location: ../index.php");
            } else {
              $login_err = "Invalid username or password.";
            }
          }
        } else {
          $login_err = "Invalid username or password.";
        }
      } else {
        echo "Oops! Something went wrong. Please try again later.";
      }
      mysqli_stmt_close($stmt_get_email);
    }
  }
  mysqli_close($link);
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>LOGIN</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous" />
  <link rel="stylesheet" href="../styles.css" />
</head>

<body>
  <div class="container login-form">
    <div class="tab-content">
      <div class="tab-pane fade show active" id="pills-login" role="tabpanel" aria-labelledby="tab-login">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

          <div class="form-outline mb-4">
            <label class="form-label" for="loginName">Email</label>
            <input type="email" name="email" id="loginName" class="form-control" />
            <span class="text-danger"><?php echo $email_err; ?></span>
          </div>

          <div class="form-outline mb-4">
            <label class="form-label" for="loginPassword">Password</label>
            <input type="password" name="password" id="loginPassword" class="form-control" />
            <span class="text-danger"><?php echo $password_err; ?></span>
          </div>
          <span class="text-danger"><?php echo $login_err; ?></span>

          <button type="submit" class="btn btn-primary btn-block mb-4">
            Log in
          </button>

          <div class="text-center">
            <p>Not a member? <a href="register.php">Register</a></p>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>

</html>