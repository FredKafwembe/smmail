<?php

/**
 * Reveals the functionallity needed to send emails.
 * 
 * @author Fred Kafwembe
 */
class EmailSender {
    private $_sendgrid;                 //sendgrid api object
    private $_receiverEmailList;        //a list of all the email addresses that will receive the email
    private $_senderEmailAddress;       //the senders email address
    private $_senderName;               //name of the sender
    private $_emailSubject;             //the email subject
    private $_isHtml;                   //boolean used to determine if the email is html or plain text
    private $_emailContent;             //the content of the email

    function __construct() {
        $this->_sendgrid = new \SendGrid(SENDGRID_API_KEY);
        $this->_emailSubject = "";
        $this->_receiverEmailList = array();
    }

    /**
     * Adds an email to the list of receiving emails.
     * 
     * @param string $email The email that is going to be added to the receiver list.
     * @param string $receiverName The name of the receiver.
     * 
     * @throws InvalidArgumentException if the given string is not formated as an email.
     * 
     * @return bool True if the email was added successfully, false 
     * if the email is already on the list.
     */
    public function addReceiverEmail($email, $receiverName = "") {
        //throw an exception if the email is invalid
        if(!$this->_validateEmail($email)) {
            throw new InvalidArgumentException($email . " is not a valid email.");
        }

        if($this->onReceiverList($email) == -1) {
            //add email to the receiver list with name or without name
            $this->_receiverEmailList[] = 
                ($receiverName == "" ? array("email" => $email) : 
                array("email" => $email, "name" => $receiverName));
            return true;
        }
        return false;
    }

    /**
     * Remove a specific email address from the receiver list
     * 
     * @param String $email The email to remove from the receiver list
     * 
     * @return Boolean True if the email was successfully removed, false otherwise
     */
    public function removeReceiverEmail($email) {
        $index = $this->onReceiverList($email);
        if($index != -1) {
            unset($this->_receiverEmailList[$index]);
            return true;
        }
        return false;
    }

    /**
     * Clear all the emails on the receiver list
     */
    public function removeAllReceiverEmails() {
        $this->_receiverEmailList = array();
    }

    /**
     * Gets the number of emails currently on the receiver email list
     * 
     * @return Integer the number of email on the receivers list
     */
    public function getReceiverEmailCount() {
        return count($this->_receiverEmailList);
    }

    /**
     * @param String $receiverEmail The email to be searched for
     * 
     * @return Integer Index of the email if the email is on the receiver list, -1 otherwise
     */
    public function onReceiverList($receiverEmail) {
        for($i = 0; $i < count($this->_receiverEmailList); $i++) {
            if($this->_receiverEmailList[$i]["email"] == $receiverEmail) {
                return $i;
            }
        }
        return -1;
    }

    /**
     * Sets the senders email address.
     * 
     * @param String $email The senders email.
     * @param String $senderName The name of the sender.
     * 
     * @throws InvalidArgumenException if the given string is not formatted as an email.
     * 
     * @return Boolean Indicates if the senders email address was set successfully.
     */
    public function setSenderEmail($email, $senderName) {
        if($this->_validateEmail($email)) {
            $this->_senderEmailAddress = $email;
            $this->_senderName = $senderName;
        } else {
            throw new InvalidArgumentException($email . " is not a valid email.");
        }
        return true;
    }

    /**
     * Sets the email subject
     * 
     * @param String $subject The subject of the email.
     */
    public function setEmailSubject($subject) {
        $this->_emailSubject = $subject;
    }

    /**
     * @return String The subject of the email
     */
    public function getEmailSubject() {
        return $this->_emailSubject;
    } 

    /**
     * Set the content of the email
     * 
     * @param String $content The content of the email can be plain plain text or HTML.
     * @param Boolean $isHtml Determines if the content is html or plain text.
     */
    public function setEmailContent($content, $isHtml = false) {
        $this->_emailContent = $content;
        $this->_isHtml = $isHtml;
    }

    /**
     * @return Bool True if the content of the email is html content, 
     * false if content is just plain text
     */
    public function getIsHtml() {
        return $this->_isHtml;
    }

    /**
     * @return String The content of the email
     */
    public function getEmailContent() {
        return $this->_emailContent;
    }

    /**
     * Sends an email to all the email addresses added to the email reciver list.
     * 
     * @throws Exception If they are no emails on the reciving email list.
     * @throws Exception If fails to send an email.
     * 
     * @return Boolean Indicates if the emails were sent successfully.
     */
    public function sendEmail() {
        if(isset($this->_receiverEmailList) && count($this->_receiverEmailList) != 0) {
            echo "one or more emails added\n";
            foreach($this->_receiverEmailList as $emailDetails) {
                $email = new \SendGrid\Mail\Mail();
                $email->setFrom($this->_senderEmailAddress, $this->_senderName);
                $email->setSubject($this->_emailSubject);
                $email->addTo($emailDetails["email"], isset($emailDetails["name"]) ? $emailDetails["name"] : "");
                $email->addContent($this->_isHtml ? "text/html" : "text/plain", $this->_emailContent);
                //$email->addContent(, "<strong>and easy to do anywhere, even with PHP</strong>");
                //$sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
                try {
                    $response = $this->_sendgrid->send($email);
                    print $response->statusCode() . "\n";
                    print_r($response->headers());
                    print $response->body() . "\n";
                } catch (Exception $e) {
                    throw new Exception("Failed to send email: " . $e->getMessage());
                }
            }
        } else {
            throw new Exception("No emails added to the receivers list");
        }
        return true;
    }

    /**
     * Validates if a given string is formatted as an email
     * 
     * @param String $email The string to be validated as an email.
     * 
     * @return Bool True if the email given has valid email format, false otherwise
     */
    private function _validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}
