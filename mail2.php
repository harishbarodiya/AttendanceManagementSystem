
<?php
$to       = 'kratikabhagat823@gmail.com';
$subject  = 'Short Attendance';
$message  = 'Hi, Its a testing email for short attendance.
 Your attendance is lee than 75% Please be regular to maintain your attendance
 
 ~harish';
$headers  = 'From: attendancemngmnt@gmail.com' . "\r\n" .
            'MIME-Version: 1.0' . "\r\n" .
            'Content-type: text/html; charset=utf-8';
if(mail($to, $subject, $message, $headers))
    echo "Email sent";
else
    echo "Email sending failed";

    ?>