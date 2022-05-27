<?php

require_once "../dbconnection.php";
require_once "../functions.php";

$sql_validation = "SELECT id FROM users WHERE user_id=? AND access_token=?";
$is_valid = false;
$pass_err = "";
$email="empty@gmail.com";

if (isset($_REQUEST['uid']) && isset($_REQUEST['t'])) {
    $token = $_REQUEST['t'];
    $uid = $_REQUEST['uid'];
    if ($stmt = mysqli_prepare($link, $sql_validation)) {
        mysqli_stmt_bind_param($stmt, "ss", $uid, $token);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) == 1) {
                $is_valid = true;
            }
        }
    }
}

$sql_set_password = "UPDATE users SET users.password=? WHERE user_id=? AND access_token=?";
$sql_update_token = "UPDATE users SET access_token=? WHERE user_id=? AND access_token=?";
$sql_get_email="SELECT email FROM users WHERE user_id=?";

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST") {
    $uid = trim($_POST["uid"]);
    $token = trim($_POST["token"]);
    $password = trim($_POST["password"]);
    $hash="";
    if (strlen($password) > 6) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
    } else {
        $pass_err = "Password is too short.";
    }

    if (strlen($hash)>0 && $stmt_set_password = mysqli_prepare($link, $sql_set_password)) {
        mysqli_stmt_bind_param($stmt_set_password, "sss", $hash, $uid, $token);
        if (mysqli_stmt_execute($stmt_set_password) && mysqli_stmt_affected_rows($stmt_set_password) == 1) {

            $new_token = get_access_token();
            if ($stmt_update_token = mysqli_prepare($link, $sql_update_token)) {
                mysqli_stmt_bind_param($stmt_update_token, "sss", $new_token, $uid, $token);
                if (mysqli_stmt_execute($stmt_update_token) && mysqli_stmt_affected_rows($stmt_update_token) == 1) {
                    session_start();
                    $_SESSION["loggedin"] = true;

                    if ($stmt_get_email = mysqli_prepare($link, $sql_get_email)) {
                        mysqli_stmt_bind_param($stmt_get_email, "s", $uid);
                        if (mysqli_stmt_execute($stmt_get_email)){
                            $result=mysqli_stmt_get_result($stmt_get_email);
                            $array=mysqli_fetch_array($result);
                            $email=$array[0];
                        }
                    }

                    $_SESSION["email"] = $email;
                    header("location: ../index.php");
                }
            }
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
    <title>Reset password</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous" />
    <link rel="stylesheet" href="../styles.css" />
</head>

<body>
    <div class="container login-form">
        <?php if ($is_valid) { ?>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="pills-login" role="tabpanel" aria-labelledby="tab-login">
                    <form action="<?php echo $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']; ?>" method="post">
                        <div class="form-outline mb-4">
                            <label class="form-label" for="password">New password</label>
                            <input type="password" name="password" class="form-control" />
                            <span class="text-danger"><?php echo $pass_err; ?></span>
                        </div>
                        <input type="hidden" name="token" value="<?php echo $token ?> " />
                        <input type="hidden" name="uid" value="<?php echo $uid ?> " />
                        <button type="submit" class="btn btn-primary btn-block mb-4">
                            Confirm
                        </button>
                    </form>
                </div>
            </div>
        <?php } else { ?>
            <p>Oops, something wents wrong... Please try again later.</p>
        <?php } ?>

    </div>
</body>

</html>