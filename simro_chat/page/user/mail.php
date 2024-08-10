<?php
//user PHPMailer\PHPMailer\PHPMailer;
//user PHPMailer\PHPMailer\SMTP;
//user PHPMailer\PHPMailer\Exception;
require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';
function envoie_mail($name, $message, $subject)
{
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->SMTPDebug = 0;
    $mail->SMTPSecure = 'ss1';
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'fozetj29@gmail.com';
    $mail->password = 'junior231';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTP;
    $mail->Port = 465;

    $mail->setFrom($name);
    $mail->addAddress('fozetj29@gmail.com');
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $message;
    $mail->setLanguage('com', '/optional/path/to/language/directory/');
    if (!$mail->send()) {
        return false;
    } else {
        return true;
    }
}
if (envoie_mail($_POST['name'], $_POST['message'], $_POST['subject'])) {
    echo 'ok';
} else {
    echo "non";
}
?>