<?php

require_once("email/EmailSender.php");

$emailSender = new EmailSender();
$emailSender->addEmail("fredjkafwembe@gmail.com");
$emailSender->sendEmail();
print("Hello");
