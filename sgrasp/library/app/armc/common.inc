<?php
//header includes
require_once("XML/RPC.php");
require_once($cfg['base_dir'].'/class/dbConnect.php');
require_once($cfg['base_dir'].'/class/oracleConnect.php');
//require_once($cfg['base_dir'].'/class/dbClass/brDB.class');
//require_once($cfg['base_dir'].'/class/dbClass/brApplication.class');
require_once($cfg['base_dir'].'/class/dbClass/oracleDB.class');
require_once($cfg['base_dir'].'/class/dbClass/studyDB.class');
require_once($cfg['base_dir'].'/class/dbClass/samDB.class');
require_once($cfg['base_dir'].'/class/dbClass/atmDB.class');
//require_once($cfg['base_dir'].'/class/dbClass/oracleDB.class');
require_once($cfg['base_dir'].'/class/inputParser.class');
require_once($cfg['base_dir'].'/class/dbClass/partnerDB.class');
//require_once($cfg['base_dir'].'/class/dbClass/studyDB.class');
require_once($cfg['base_dir'].'/class/dbClass/userDB.class');
//require_once($cfg['base_dir'].'/class/dbClass/vendorDB.class');
//require_once($cfg['base_dir'].'/class/dbClass/messageDB.class');
//require_once($cfg['base_dir']."/class/dbClass/netmrDB.class");
require_once($cfg['base_dir']."/class/dbClass/commonDB.class");
//require_once($cfg['base_dir']."/class/rpcClass/netmrXML.class");
require_once($cfg['base_dir'].'/class/userRights.php');
require_once($cfg['base_dir'].'/class/Encryption.class');
require_once($cfg['base_dir'].'/include/smarty.inc');
require_once($cfg['base_dir'].'/locale/en_US.inc');
require_once($cfg['base_dir']."/common/functions.inc");

/* FRAMEWORK 2.0 style */
require_once($cfg['base_dir']."/class/hb.class");

//To load Armc class 
require_once($cfg['base_dir']."/app/atm/armc/Armc.class");

//To load the new business layer component, ContactManager.class
require_once($cfg['base_dir']."/app/atm/armc/ContactsManager.class");

//To load the new business layer component, FileManager.class
require_once($cfg['base_dir']."/app/atm/armc/FileManager.class");

//To load the new db layer component, ContactManager.class
//require_once($cfg['base_dir']."/class/db/atm/armc/ContactsManager.class");

//To load the new db layer component, FileManager.class
//require_once($cfg['base_dir']."/class/db/atm/armc/FileManager.class");

//Billing Manager Component
require_once($cfg['base_dir']."/app/atm/armc/BillingManager.class");
?>
