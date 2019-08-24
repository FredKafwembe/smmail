<?php

use PHPUnit\Framework\TestCase;

class DBConnectorTest extends TestCase {
    public function testSameSingletonInstanceReturned() {
        $instance1 = DBConnector::instance();
        $instance2 = DBConnector::instance();
        $this->assertTrue($instance1 == $instance2);
    }

    public function testSamePdoConnectionReturned() {
        $instance1 = DBConnector::instance()->getConnection();
        $instance2 = DBConnector::instance()->getConnection();
        $this->assertTrue($instance1 == $instance2);
    }
}