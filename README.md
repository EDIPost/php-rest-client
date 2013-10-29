php-rest-client
===============

PHP Client library for public API



init
====
require_once( "EdipostService/EdipostService.php" );

$api = new EdipostService/EdipostService( $apikey );


Best practice
=============
require_once( "EdipostService/EdipostService.php" );

class DAO extends \EdipostService\EdipostService {   
  public function __construct( $apikey, $apiurl = 'http://api.edipost.no' ){
    parent::__construct($apikey, null, $apiurl);
  }
  
  ...
  your datastore methods.
  ...
}
