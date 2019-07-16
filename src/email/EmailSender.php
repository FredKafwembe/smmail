<?php
require 'vendor/autoload.php'; // If you're using Composer (recommended)

/**
 * Reveals the functionallity needed to send
 * emails.
 */
class EmailSender {
    private $_sendgrid;
    private $_emails;

    function __construct() {
        //$_sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
    }

    /**
     * Adds an email to the list of reciving emails.
     *
     * Throws an InvalidArgumentException if the given string is not formated as an email.
     */
    public function addReciverEmail($email) {
        if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->_emails[] = $email;
        } else {
            throw new InvalidArgumentException($email . " is not a valid email.");
        }
        return true;
    }

    public function sendEmail() {
        if(count($this->_emails) != 0) {
            echo "one or more emails added\n";
            foreach($this->_emails as $email) {
                echo $email . "\n";
            }
        } else {
            echo "no emails set\n";
        }
    }
}

/*
$email = new \SendGrid\Mail\Mail();
$email->setFrom("test@example.com", "Example User");
$email->setSubject("Sending with SendGrid is Fun");
$email->addTo("test@example.com", "Example User");
$email->addContent("text/plain", "and easy to do anywhere, even with PHP");
$email->addContent(
    "text/html", "<strong>and easy to do anywhere, even with PHP</strong>"
);
$sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
try {
    $response = $sendgrid->send($email);
    print $response->statusCode() . "\n";
    print_r($response->headers());
    print $response->body() . "\n";
} catch (Exception $e) {
    echo 'Caught exception: '. $e->getMessage() ."\n";
}
*/
