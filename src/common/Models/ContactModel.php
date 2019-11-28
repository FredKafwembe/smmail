<?php

class ContactModel extends Model {
	function __construct() {
		parent::__construct();
	}

	public function createContact($data) {
		$sql = "INSERT INTO SmmailContacts(Name, Email, PhoneNumber)
			VALUES (:Name, :Email, :PhoneNumber)";

		$statment = $this->_db->prepare($sql);
		$createdContact = $statment->execute(array(
			"Name" => $data["Name"],
			"Email" => $data["Email"],
			"PhoneNumber" => $data["PhoneNumber"]
		));
		if(!$createdContact) {
			throw new Exception("Failed to insert contact \"$data[Name]\" into database");
		}
		return $this->_db->lastInsertId();
	}

	public function findContactByEmail($email) {
		$sql = "SELECT ContactId, Name, Email, PhoneNumber 
			FROM SmmailContacts WHERE Email = :Email";
		$data = array("Email" => $email);
		return $this->findContact($sql, $data);
	}

	public function findContactByPhoneNumber($phoneNumber) {
		$sql = "SELECT ContactId, Name, Email, PhoneNumber 
			FROM SmmailContacts WHERE PhoneNumber = :PhoneNumber";
		$data = array("PhoneNumber" => $phoneNumber);
		return $this->findContact($sql, $data);
	}

	private function findContact($sql, $data) {
		$statment = $this->_db->prepare($sql);
		$foundContact = $statment->execute($data);

		if($foundContact && $statment->rowCount() > 0) {
			return $statment->fetch();
		}
		return false;
	}
}