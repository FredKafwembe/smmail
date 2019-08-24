<?php

/**
 * Singleton class used to connect to database
 * 
 * @author Fred kafwembe
 */
final class DBConnector {

    private $conn;
    private $dbCreateScript = array(
        "CREATE SCHEMA IF NOT EXISTS `Smmail` DEFAULT CHARACTER SET utf8 ;
        USE `Smmail` ;",
        
        "CREATE TABLE IF NOT EXISTS `Smmail`.`SmmailContacts` (
        `ContactId` INT(21) NOT NULL AUTO_INCREMENT,
        `Name` VARCHAR(50) NULL,
        `Email` VARCHAR(120) NULL,
        `PhoneNumber` INT(20) NULL,
        PRIMARY KEY (`ContactId`))
        ENGINE = InnoDB;",
        
        "CREATE TABLE IF NOT EXISTS `Smmail`.`SmmailGroups` (
        `GroupId` INT(21) NOT NULL AUTO_INCREMENT,
        `Name` VARCHAR(120) NOT NULL,
        PRIMARY KEY (`GroupId`))
        ENGINE = InnoDB;",
        
        "CREATE TABLE IF NOT EXISTS `Smmail`.`SmmailMessages` (
        `MessageId` INT(21) NOT NULL AUTO_INCREMENT,
        `GroupIdFk` INT(21) NOT NULL,
        `Message` TEXT NOT NULL,
        `TimeSent` TIMESTAMP NOT NULL,
        PRIMARY KEY (`MessageId`),
        INDEX `MessageGroupId_idx` (`GroupIdFk` ASC),
        CONSTRAINT `MessageGroupId`
            FOREIGN KEY (`GroupIdFk`)
            REFERENCES `Smmail`.`SmmailGroups` (`GroupId`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION)
        ENGINE = InnoDB;",
        
        "CREATE TABLE IF NOT EXISTS `Smmail`.`SmmailGroupContact` (
        `ContactIdFk` INT(21) NOT NULL,
        `GroupIdFk` INT(21) NOT NULL,
        `IsEmailGroup` TINYINT(1) NOT NULL,
        `IsSmsGroup` TINYINT(1) NOT NULL,
        INDEX `GCContactId_idx` (`ContactIdFk` ASC),
        INDEX `GCGroupId_idx` (`GroupIdFk` ASC),
        CONSTRAINT `GCContactId`
            FOREIGN KEY (`ContactIdFk`)
            REFERENCES `Smmail`.`SmmailContacts` (`ContactId`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION,
        CONSTRAINT `GCGroupId`
            FOREIGN KEY (`GroupIdFk`)
            REFERENCES `Smmail`.`SmmailGroups` (`GroupId`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION)
        ENGINE = InnoDB;",
        
        "CREATE TABLE IF NOT EXISTS `Smmail`.`SmmailMessageReceiveDetails` (
        `ContactIdFk` INT(21) NOT NULL,
        `MessageIdFk` INT(21) NOT NULL,
        `IsSent` TINYINT(1) NOT NULL,
        `SentTime` TIMESTAMP NULL,
        INDEX `MRDContactId_idx` (`ContactIdFk` ASC),
        INDEX `MRDMessageId_idx` (`MessageIdFk` ASC),
        CONSTRAINT `MRDContactId`
            FOREIGN KEY (`ContactIdFk`)
            REFERENCES `Smmail`.`SmmailContacts` (`ContactId`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION,
        CONSTRAINT `MRDMessageId`
            FOREIGN KEY (`MessageIdFk`)
            REFERENCES `Smmail`.`SmmailMessages` (`MessageId`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION)
        ENGINE = InnoDB;"
    );

    /**
     * Returns the singleton instance
     * 
     * @throws Exception If the ENABLE_DATABASE_STORAGE configuration is not enabled
     * 
     * @return DBConnector The returned object represents the singleton object
     */
    public static function instance() {
        if(!ENABLE_DATABASE_STORAGE) {
            throw new Exception("Database storage is not enabled!");
        }
        static $inst = null;
        if ($inst === null) {
            $inst = new DBConnector();
        }
        return $inst;
    }

    /**
     * Private constructor so nobody else can instantiate it
     * 
     * If the database DATABASE_NAME does't exist it is created
     */
    private function __construct() {
        try {
            //make a connection to the database with name DATABASE_NAME
            $this->conn = new PDO(DATABASE_TYPE . ":host=" . DATABASE_HOST . 
                ";dbname=" . DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);

            foreach($this->dbCreateScript as $index => $command) {
                //Skip the first command if the database already exists
                if($index == 0) {
                    continue;
                }
                $this->conn->exec($command);
            }    
        } catch(PDOException $e) {
            //if DATABASE_NAME does not exist make a connection without any database
            $this->conn = new PDO(DATABASE_TYPE . ":host=" . DATABASE_HOST, 
                DATABASE_USERNAME, DATABASE_PASSWORD);
            //excecute all commands
            foreach($this->dbCreateScript as $command) {
                $this->conn->exec($command);
            }
        }
    }

    /**
     * @return PDO The active pdo connection to the database
     */
    public function getConnection() {
      return $this->conn;
    }
}
