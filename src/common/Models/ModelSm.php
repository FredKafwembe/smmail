<?php
/**
 * Base abstract class for all models
 */
abstract class ModelSm {
	protected $_db;

	function __construct() {
		$this->_db = DBConnector::instance()->getConnection();
	}
}