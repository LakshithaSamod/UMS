<?php session_start(); ?>
<?php require_once('../inc/connection.php'); ?>
<?php require_once('../inc/func.php'); ?>
<?php
//check if a user is logged
verify_user_isLogged($_SESSION['id']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0,  maximum-scale=1.0, minimum-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" type="text/css" href="../css/contact.css">
    <link rel="stylesheet" href="../css/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="../css/alert.css">    
    <script src="../js/alert.js"></script>
    <script>
    </script>
</head>
<body>
<?php require_once('../inc/header.php'); ?>
    <div class="container">
        <form action="contact-us.php" method="post">

            <div class="background">
                <div class="container">
                    <div class="screen">
                        <div class="screen-header">
                            <div class="screen-header-left">
                                <div class="screen-header-button close"></div>
                                <div class="screen-header-button maximize"></div>
                                <div class="screen-header-button minimize"></div>
                            </div>
                            <div class="screen-header-right">
                                <div class="screen-header-ellipsis"></div>
                                <div class="screen-header-ellipsis"></div>
                                <div class="screen-header-ellipsis"></div>
                            </div>
                        </div>
                        <div class="screen-body">
                            <div class="screen-body-item left">
                                <div class="app-title">
                                    <span>CONTACT</span>
                                    <span>US</span>
                                </div>
                                <div class="app-contact">CONTACT INFO : +94 76 4191 333</div>
                            </div>
                            <div class="screen-body-item">
                                <div class="app-form">
                                    <div class="app-form-group">
                                        <input class="app-form-control" name="fullname" placeholder="NAME" value="Lakshitha Samod" maxlength="30" required>
                                    </div>
                                    <div class="app-form-group">
                                        <input class="app-form-control" name="subject" placeholder="SUBJECT" maxlength="30">
                                    </div>
                                    <div class="app-form-group">
                                        <input class="app-form-control" name="email" placeholder="EMAIL" maxlength="30">
                                    </div>
                                    <div class="app-form-group">
                                        <input class="app-form-control" name="contact-no" placeholder="CONTACT NO" maxlength="12">
                                    </div>
                                    <div class="app-form-group message">
                                        <textarea class="app-form-control" name="message" id="message" cols="35" rows="2" maxlength="1000" placeholder="MESSAGE" required></textarea>
                                    </div>
                                    <div class="app-form-group buttons" name="send-msg">
                                        <button class="app-form-button" name="msg">Message Us</button> &nbsp &nbsp &nbsp
                                        <button class="app-form-button1" name="send-mail">Email Us</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="credits">
                        Back to
                        <a class="credits-link" href="../users/users.php">
                            <svg class="dribbble" viewBox="0 0 200 200">
                                <g stroke="#ffffff" fill="none">
                                    <circle cx="100" cy="100" r="90" stroke-width="20"></circle>
                                    <path d="M62.737004,13.7923523 C105.08055,51.0454853 135.018754,126.906957 141.768278,182.963345" stroke-width="20"></path>
                                    <path d="M10.3787186,87.7261455 C41.7092324,90.9577894 125.850356,86.5317271 163.474536,38.7920951" stroke-width="20"></path>
                                    <path d="M41.3611549,163.928627 C62.9207607,117.659048 137.020642,86.7137169 189.041451,107.858103" stroke-width="20"></path>
                                </g>
                            </svg>
                            Users
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div> <!-- .container -->
</body>
</html>

<?php
require_once "../vendor/autoload.php";
$status = '';

// checking if the click send mail button
if (isset($_POST['send-mail'])) {
    $fullname    = $_POST['fullname'];
    $email_address = $_POST['email'];
    $subject    = $_POST['subject'];
    $message    = $_POST['message'];
    $contactNo =  $_POST['contact-no'];

    $to               = "lakisaman19@gmail.com";
    $mail_subject = 'Message from Website';
    $email_body   = "Message from Contact Us Page of the Lakisam Website: <br>";
    $email_body   .= "<b>From:</b> {$fullname} <br>";
    $email_body      .= "<b>Email Address:</b> {$email_address} <br>";
    $email_body      .= "<b>Phone Number:</b> {$contactNo} <br>";
    $email_body   .= "<b>Subject:</b> <a>{$subject} </><br>";
    $email_body   .= "<b>Message:</b><br>" . nl2br(strip_tags($message));

    $header       = "From: {$email_address}\r\nContent-Type: text/html;";

    $send_mail_result = mail($to, $mail_subject, $email_body, $header);

      // Display the alert box 
    if ($send_mail_result) {   
        echo "<script>swal('Done!', 'Your email was sent successfully!', 'success');</script>";
    } else {
        echo "<script>swal('Oops... operation failed! ', 'Sending email failed ', 'error');</script>";
    } 
}

// checking if the click send msg button
if (isset($_POST['msg'])) {
    $basic  = new \Vonage\Client\Credentials\Basic("6c42b247", "NbgFMl6Fg2DJdEVa");
    $client = new \Vonage\Client($basic);

    $fullname    = $_POST['fullname'];
    $email_address = $_POST['email'];
    $message    = $_POST['message'];
    $contactNo =  $_POST['contact-no'];

    $msg1 = nl2br(strip_tags($message));

    $msg_body   = "Message from Contact Us Page of the LakiSam Website:  ";
    $msg_body   .= "From: {$fullname} , ";
    $msg_body      .= "Email Address: {$email_address} , ";
    $msg_body      .= "Phone Number: {$contactNo} , ";
    $msg_body   .= "Message: {$msg1}";


    $response = $client->sms()->send(
        new \Vonage\SMS\Message\SMS("94760368869", "Vonage APIs", $msg_body)
    );

    $message = $response->current();

    // Display the alert box 
    if ($message->getStatus() == 0) {
        echo "<script>swal('Done!', 'Your message was sent successfully!', 'success');</script>";

    } else {
        echo "<script>swal('Oops... operation failed! ', 'Sending message failed ', 'error');</script>";
    }
}

mysqli_close($connection); 
?>