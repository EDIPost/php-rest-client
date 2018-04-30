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


    /*
     * Autoload all less important files
     * Wrap it in a function to prevent RecursiveIteratorIterator from hanging in global scope
     *
     */
	function autoLoad() {
		$path = dirname(__FILE__);

		// Autoload manual important files
		$Autoload = array();
		$Autoload[] = $path . DIRECTORY_SEPARATOR . 'Utils' . DIRECTORY_SEPARATOR . 'lexa-xml-serialization.php';
		$Autoload[] = $path . DIRECTORY_SEPARATOR . 'Client' . DIRECTORY_SEPARATOR . 'Builder' . DIRECTORY_SEPARATOR . 'PartyBuilder.php';
		$Autoload[] = $path . DIRECTORY_SEPARATOR . 'ServiceConnection' . DIRECTORY_SEPARATOR . 'ServiceConnection.php';
		$Autoload[] = $path . DIRECTORY_SEPARATOR . 'ServiceConnection' . DIRECTORY_SEPARATOR . 'Communication.php';
		$Autoload[] = $path . DIRECTORY_SEPARATOR . 'Client' . DIRECTORY_SEPARATOR . 'Party.php';


		foreach ( $Autoload as &$a ){
			if ( file_exists($a) ){
				require_once( $a );
			}
		}


		$objects = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator( $path ),\ RecursiveIteratorIterator::SELF_FIRST);
		foreach($objects as $name => $object){
			if ( substr($object->getBasename(), strpos($object->getBasename(), ".")) == '.php' ) {
				if( ! stristr($object->getBasename(), 'test' ) ) {
					require_once( $object->getPath() . DIRECTORY_SEPARATOR . $object->getBasename()  );
				}
			}
		}
    }


	autoLoad();


?>
