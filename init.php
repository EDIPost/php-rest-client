<?php
    /**
    * Initializes the classes and make sure they are preloaded in the correct order.
    * 
    */
    define("_api_logfile_", "EdipostService.log" );
    define("REST_GET", "GET");
    define("REST_CREATE", "POST");
    define("REST_UPDATE", "PUT");
    define("REST_DELETE","DELETE");
    
    define("PARTY_CONSIGNEE", "consignee");
    define("PARTY_CONSIGNOR", "consignor");
    

    $path = substr(__FILE__, 0, strrpos(__FILE__, "\\"));

    // Autoload manual important files
    $Autoload = array();
    $Autoload[] = $path . "\\Utils\\lexa-xml-serialization.php";
    $Autoload[] = $path . "\\Client\\Builder\\PartyBuilder.php";
    $Autoload[] = $path . "\\ServiceConnection\\ServiceConnection.php";
    $Autoload[] = $path . "\\ServiceConnection\\Communication.php";
    $Autoload[] = $path . "\\Client\\Party.php";


    foreach ( $Autoload as &$a ){
        if ( file_exists($a) ){
            require_once( $a );
        }
    }

    // autoload all less important files
    $objects = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator( $path ),\ RecursiveIteratorIterator::SELF_FIRST);
    foreach($objects as $name => $object){
        if ( substr($object->getBasename(), strpos($object->getBasename(), ".")) == '.php' ) { 
            require_once( $object->getPath() . '\\'. $object->getBasename()  );
        }
    }
?>
