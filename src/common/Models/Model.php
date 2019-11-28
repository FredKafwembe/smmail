<?php
/**
 * Base abstract class for all models
 */
abstract class Model {
	protected $_db;

	function __construct() {
		$this->_db = DBConnector::instance()->getConnection();
	}
}