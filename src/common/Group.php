<?php
class Group {
	private $_groupId = -1;
	private $_groupName;

	/**
	 * @param String $groupName The name of the group
	 * @param Bool $createGroup If true a new group will be created with the given name
	 */
	function __construct($groupName, $createGroup = false) {
		if(ENABLE_DATABASE_STORAGE) {
			$db = DBConnector::instance()->getConnection();
			if($createGroup) {
				$statment = $db->prepare("SELECT FROM SmmailGroups WHERE Name = :Name");
				$statment->execute(array("Name" => $groupName));
				if($statment->rowCount() <= 0) {
					$statment = $db->prepare("INSERT INTO SmmailGroups(Name) VALUES (:Name)");
					$createdGroup = $statment->execute(array("Name" => $groupName));
					if($createdGroup) {
						$this->_groupId = $db->lastInsertId();
						$this->_groupName = $groupName;
					} else {
						throw new Exception("Failed to insert group \"$groupName\" into database");
					}
				} else {
					throw new Exception("Group already exists.");
				}
			} else {
				$statment = $db->prepare("SELECT FROM SmmailGroups WHERE Name = :Name");
				$foundGroup = $statment->execute(array("Name" => $groupName));
				if($foundGroup) {
					$groupInfo = $statment->fetchAll();
					$this->_groupId = $groupInfo["GroupId"];
					$this->_groupName = $groupInfo["Name"];
				} else {
					throw new Exception("Failed to find group \"$groupName\".");
				}
			}
		} else {
			$this->_groupName = $groupName;
		}
	}

	public function getName() {
		return $this->_groupName;
	}

	public function getId() {
		return $this->_groupId;
	}
}