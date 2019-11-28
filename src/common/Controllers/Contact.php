<?php

class Contact extends ControllerSm {
	private $_id;
	private $_name;
	private $_email;
	private $_phoneNumber;

	function __construct($name, $contactInfo, $id = -1) {
		parent::__construct();

		$this->_id = $id;
		$this->_name = $name;
		$this->_email = (isset($contactInfo["Email"])) ? $contactInfo["Email"] : null;
		$this->_phoneNumber = (isset($contactInfo["PhoneNumber"])) ? $contactInfo["PhoneNumber"] : null;
	}
	
	public function getId() { return $this->_id; }
	public function getName() { return $this->_name; }
	public function getEmail() { return $this->_email; }
	public function getPhoneNumber() { return $this->_phoneNumber; }

	public function setName() {}

	public function setEmail() {}

	public function setPhoneNumber() {}

	protected function setModel() {
		if(!ENABLE_DATABASE_STORAGE)
			return;
		
		$this->_model = new ContactModel();
	}

	public function storeContact() {
		if(!ENABLE_DATABASE_STORAGE)
			return;

		$data = array(
			"Name" => $this->_name,
			"Email" => $this->_email,
			"PhoneNumber" => $this->_phoneNumber
		);

		$this->_id = $this->_model->createContact($data);
	}

	public function loadContactInfo() {
		$contactInfo = null;
		if($this->_email != null) {
			$contactInfo = $this->_model->findContactByEmail($this->_email);
		} else if($this->_phoneNumber != null) {
			$contactInfo = $this->_model->findContactByPhoneNumber($this->_phoneNumber);
		}
		if($contactInfo != null) {
			$this->_id = $contactInfo["ContactId"];
			$this->_name = $contactInfo["Name"];
			$this->_email = $contactInfo["Email"];
			$this->_phoneNumber = $contactInfo["PhoneNumber"];
		}
	}
}