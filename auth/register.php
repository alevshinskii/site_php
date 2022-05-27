<?php

require_once "../dbconnection.php";
require_once "../functions.php";

// Define variables and initialize with empty values
$email = "";
$email_err = "";
// Processing form data when form is submitted
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST") {

    $email = trim($_POST["email"]);

    $sql_check_email_exists = "SELECT id FROM users WHERE email = ?";
    $sql_pass_not_installed = "SELECT id FROM users WHERE email = ? AND LENGTH(password)=0";
    $sql_insert = "INSERT INTO users (user_id,email,access_token) VALUES (?,?,?)";
    $sql_get_uid_token_from_db = "SELECT user_id,access_token FROM users WHERE email = ?";

    if ($stmt_check_email_exists = mysqli_prepare($link, $sql_check_email_exists)) {
        mysqli_stmt_bind_param($stmt_check_email_exists, "s", $email);
        if (mysqli_stmt_execute($stmt_check_email_exists)) {
            mysqli_stmt_store_result($stmt_check_email_exists);

            $user_id = get_uniq_userid($link);
            if ($user_id == null) {
                echo "Oops! Something went wrong. Please try again later.";
            }
            $token = get_access_token();
            if (mysqli_stmt_num_rows($stmt_check_email_exists) == 1) {

                if ($stmt_pass_not_installed = mysqli_prepare($link, $sql_pass_not_installed)) {
                    mysqli_stmt_bind_param($stmt_pass_not_installed, "s", $email);
                    if (mysqli_stmt_execute($stmt_pass_not_installed)) {
                        mysqli_stmt_store_result($stmt_pass_not_installed);
                        if (mysqli_stmt_num_rows($stmt_pass_not_installed) == 1) {

                            if ($stmt_get_token_from_db = mysqli_prepare($link, $sql_get_uid_token_from_db)) {
                                mysqli_stmt_bind_param($stmt_get_token_from_db, "s", $email);
                                if (mysqli_stmt_execute($stmt_get_token_from_db)) {
                                    $result=mysqli_stmt_get_result($stmt_get_token_from_db);
                                    $array=mysqli_fetch_assoc($result);
                                    $token=$array["access_token"];
                                    $user_id=$array["user_id"];
                                }
                            }
                            if (strlen($token) > 0 && send_email($email, $user_id, $token)) {
                                header("location: email_success.php?email=$email");
                            } else {
                                header("location: login.php");
                            }
                        }
                    }
                }

                $email_err = "This email is already taken.";
            } else {
                if ($stmt_insert = mysqli_prepare($link, $sql_insert)) {
                    mysqli_stmt_bind_param($stmt_insert, "sss", $user_id, $email, $token);
                    if (mysqli_stmt_execute($stmt_insert)) {
                        if (send_email($email, $user_id, $token)) {
                            header("location: email_success.php?email=$email");
                        } else {
                            header("location: login.php");
                        }
                    }
                }
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
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
    <title>Registration</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous" />
    <link rel="stylesheet" href="../styles.css" />
</head>

<body>
    <div class="container login-form">
        <div class="tab-content">
            <div class="tab-pane fade show active" id="pills-login" role="tabpanel" aria-labelledby="tab-login">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <!-- Email input -->
                    <div class="form-outline mb-4">
                        <label class="form-label" for="email">Email</label>
                        <input type="email" name="email" class="form-control" />
                        <span class="text-danger"><?php echo $email_err; ?></span>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block mb-4">
                        Register
                    </button>

                    <div class="text-center">
                        <p>Already a member? <a href="login.php">Log in</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>