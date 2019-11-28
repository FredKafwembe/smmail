<?php

require_once __DIR__ . "\\..\\vendor\\autoload.php";
require_once __DIR__ . "\\common\\Config.php";

//$smsSender = new SmsSender();
//$smsSender->setSendersName("Fred");
//$smsSender->setMessage("First test of many");
//$smsSender->addReceiverPhoneNumber("0974223613", "ZM");
//$smsSender->addReceiverPhoneNumber("+260 97 1503354");
//$smsSender->sendSms();

//$dbConn = DBConnector::instance();

$emailSender = new EmailSender();
//print(__DIR__ . "\n");
//$emailSender->setEmailContent("This is a test email");
//$emailSender->setEmailSubject("Test email");
//$emailSender->setSenderEmail("fred.kafwembe@cs.unza.zm", "Fred");
$emailSender->addReceiverEmail("fredjkafwembe@gmail.com", "Fred");
$emailSender->addReceiverEmail("fredjkafwemb@gmail.com", "Fred");
//print($emailSender->removeReceiverEmail("fredjkafwemb@gmail.com") ? "removed\n" : "not removed\n");
//$emailSender->sendEmail();

//print(("revolve" == "revolve") ? "true" : "false");

//$group = new Group("New Group 5", true, true);
//$group->storeGroup();
//$group->loadGroupInfoFromStorage();

//echo "Group Id: \t\t" . $group->getId() .
//"     \nGroup Name: \t\t" . $group->getName() .
//"     \nIs email group: \t" . ($group->isEmailGroup() ? "True" : "False") .
//"     \nIs sms group: \t\t" . ($group->isSmsGroup() ? "True" : "False");

//$message = new Message("Testing message class", MessageType::Email, 1);
//$group->sendMessage($message);

//require "php_serial.class.php";
$serial = new phpSerial;
$serial->deviceSet("COM6");
$serial->confBaudRate(9600);

// Then we need to open it
$serial->deviceOpen();

// To write into
//$serial->sendMessage("AT\n\r"); 		//
//$serial->sendMessage("AT+CMGF=1\n\r"); 
//$serial->sendMessage("AT+CMGW=\"+260960217153\"\n\r");
//$serial->sendMessage(chr(13));
//$serial->sendMessage("sms text\n\r");
//$serial->sendMessage(chr(26));
//$serial->sendMessage("AT+CMSS=0\n\r"); 

$serial->sendMessage("AT+CMGF=1\n\r", 1); 
//$serial->sendMessage("AT+CSCS=\"GSM\"", 1);	//
//$serial->sendMessage("AT+cmgs=\"+260960217153\"\n\r", 1);
//$serial->sendMessage(chr(13), 1);
//$serial->sendMessage("sms text\n\r", 1);
//$serial->sendMessage(chr(26), 1);

//wait for modem to send message
sleep(7);
$read=$serial->readPort(100);
$serial->deviceClose();

echo $read;

//$contact = new Contact("Jack", array("Email" => "email", "PhoneNumber" => "phone number"));
//$contact2 = new Contact("Jane", array("Email" => "email1", "PhoneNumber" => "phone number1"));
//$contact->storeContact();
//$contact2->storeContact();
//$contact->loadContactInfo();
//$contact2->loadContactInfo();

//$group->addContactToGroup($contact);
//$group->addContactToGroup($contact2);
//$group->storeGroupContacts();

//echo "ID: " . $contact->getId() .
//"     \nName: " . $contact->getName() .
//"     \nEmail: " . $contact->getEmail() .
//"     \nPhone Number: " . $contact->getPhoneNumber();

class SmsAndEmailSender {

}
