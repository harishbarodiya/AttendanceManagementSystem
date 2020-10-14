<?php
// $to = "harishbarodiya111@gmail.com";
// $subject = "My subject";
// $txt = "Hello world!";
// $headers = "From: harishbarodiya111@gmail.com" . "\r\n" .
// "CC: somebodyelse@example.com";

// mail($to,$subject,$txt,$headers);
?>

<?php
require "includes/PHPMailerAutoload.php";
$mail = new PHPMailer();
$mail ->HOST = 'smtp.gmail.com';
$mail ->Port = 587;
$mail ->SMTPAuth = true;
$mail ->SMTPSecure = 'tls';
$mail ->Username = 'harishbarodiya111@gmail.com';
$mail ->Password ='123';
$mail ->setFrom('no-reply@gmail.com','harish');
$mail ->addAddress('harishbarodiya111@gmail.com');
$mail ->IsSmtp(true);
$mail ->SMTpDebug=3;
$mail ->Subject='subject';
$mail ->Body='This is body';

if(!$mail->send()){
    echo "Error :message not send";
}else{
    echo "message send successfully";
}


?>