<?php

class GroupModel extends Model {
	function __construct() {
		parent::__construct();
	}

	public function createGroup($data) {
		$sql = "INSERT INTO SmmailGroups(Name, IsEmailGroup, IsSmsGroup) 
			VALUES (:Name, :isEmailGroup, :isSmsGroup)";

		$statment = $this->_db->prepare($sql);
		$createdGroup = $statment->execute(array(
			"Name" => $data["GroupName"],
			"isEmailGroup" => ($data["IsEmailGroup"] ? 1 : 0),
			"isSmsGroup" => ($data["IsSmsGroup"] ? 1 : 0)
		));
		if(!$createdGroup) {
			throw new Exception("Failed to insert group \"$data[GroupName]\" into database");
		}
		return $this->_db->lastInsertId();
	}

	public function findGroup($groupName) {
		$sql = "SELECT GroupId, Name, IsEmailGroup, IsSmsGroup 
			FROM SmmailGroups WHERE Name = :Name";

		$statment = $this->_db->prepare($sql);
		$foundGroup = $statment->execute(array("Name" => $groupName));

		if($foundGroup && $statment->rowCount() > 0) {
			return $statment->fetch();
		}
		return false;
	}
	
	public function addContactToGroup($data) {
		$sql = "INSERT INTO SmmailGroupContact (ContactIdFk, GroupIdFk) VALUES (:ContactId, :GroupId)";

		$statment = $this->_db->prepare($sql);
		$contactAdded = $statment->execute(array(
			"ContactId" => $data["ContactId"],
			"GroupId" => $data["GroupId"]
		));
		if(!$contactAdded) {
			throw new Exception("Failed to add contact '$data[ContactName]' to group '$data[GroupName]'");
		}
	}

	public function storeMessageInformation($messageInfo) {
		$sql = "INSERT INTO SmmailMessageReceiveDetails (ContactIdFk, MessageIdFk, IsSent) 
			VALUES (:ContactId, :MessageId, :IsSent)";
		$statment = $this->_db->prepare($sql);
		$infoStored = $statment->execute(array(
			"ContactId" => $messageInfo["ContactId"],
			"MessageId" => $messageInfo["MessageId"],
			"IsSent" => ($messageInfo["IsSent"]) ? 1 : 0
		));
		if(!$infoStored) {
			throw new Exception("Failed to store message transfer information");
		}
	}

	public function loadGroupContacts($groupId) {
		$sql = "SELECT sc.ContactId, sc.Name, sc.Email, sc.PhoneNumber
			FROM SmmailGroupContact AS sgc 
			INNER JOIN SmmailContacts AS sc 
			ON sgc.ContactIdFk = sc.ContactId 
			AND sgc.GroupIdFk = :GroupId";
		$statment = $this->_db->prepare($sql);
		$retrivedContacts = $statment->execute(array("GroupId" => $groupId));
		if(!$retrivedContacts)
			throw new Exception("Failed to retrieve contacts from database.");
		
		$contacts = array();
		$contactsInfo = $statment->fetchAll();
		foreach($contactsInfo as $contactInfo) {
			$contact = new Contact($contactInfo["Name"], 
				array("Email" => $contactInfo["Email"], 
				"PhoneNumber" => $contactInfo["PhoneNumber"]),
				$contactInfo["ContactId"]
			);
			$contacts[] = $contact;
		}

		return $contacts;
	}
}
