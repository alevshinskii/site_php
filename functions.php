<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';


function get_uniq_userid($link)
{
    $attempts = 5;
    $is_correct_id = true;
    $user_id = uniqid();
    $sql_check_userid_exists = "SELECT id FROM users WHERE user_id = ?";

    do {
        if ($statement = mysqli_prepare($link, $sql_check_userid_exists)) {
            mysqli_stmt_bind_param($statement, "s", $user_id);
            if (mysqli_stmt_execute($statement)) {
                mysqli_stmt_store_result($statement);
                if (mysqli_stmt_num_rows($statement) == 1) {
                    $is_correct_id = false;
                }
            }
        }

        $attempts--;
    } while ($attempts > 0 && !$is_correct_id);


    if ($is_correct_id) {
        return $user_id;
    } else {
        return null;
    }
}

function send_email($email,$user_id,$token)
{
    require("mailbot_creds.php");
    $link="http://" . $_SERVER['HTTP_HOST'] . "/test/auth/set_password.php?uid=" . $user_id . "&&t=" . $token;

    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host   = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = $bot['email'];
    $mail->Password   = $bot['pass'];
    $mail->SMTPSecure = 'ssl';
    $mail->Port   = 465;

    $mail->setFrom($bot['email'], 'Registration Bot');
    $mail->addAddress($email);

    $mail->Subject = 'Registration';
    $mail->msgHTML("<html>
                    <body>
                    <h1>Your registration link</h1>
                    <p>" . $link . "</p>
                    </body>
                    </html>");

    return $mail->send();
}

function get_access_token(){
   
    if (function_exists('com_create_guid') === true)
    {
        return trim(com_create_guid(), '{}');
    }

    return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', 
    mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), 
    mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), 
    mt_rand(0, 65535), mt_rand(0, 65535));
}