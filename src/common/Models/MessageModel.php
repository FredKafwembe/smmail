<?php

class MessageModel extends Model {
	function __construct() {
		parent::__construct();
	}

	public function createMessage($data) {
		$sql = "INSERT INTO SmmailMessages(GroupIdFk, Message, MessageType, TimeSent) 
			VALUES (:GroupId, :Message, :MessageType, :TimeSent)";

		$statment = $this->_db->prepare($sql);
		$insertedMessage = $statment->execute(array(
			"GroupId" => $data["GroupId"],
			"Message" => $data["Message"],
			"MessageType" => $data["MessageType"],
			"TimeSent" => null
		));

		if(!$insertedMessage) {
			throw new Exception("Failed to insert message into database");
		}

		return $this->_db->lastInsertId();
	}

	public function updateTimeStent($id) {
		$timeSent = date("Y-m-d H:i:s");
		$sql = "UPDATE SmmailMessages SET TimeSent = :TimeSent WHERE MessageId = :ID";

		$statment = $this->_db->prepare($sql);
		$updatedTimeSent = $statment->execute(array(
			"ID" => $id,
			"TimeSent" => $timeSent
		));

		if(!$updatedTimeSent) {
			throw new Exception("Failed to update time sent");
		}

		return $timeSent;
	}
}