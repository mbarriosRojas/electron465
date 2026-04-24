<?php
date_default_timezone_set ( 'America/Caracas' );
class Soapserver extends Controller  {
	function __construct() {
		parent::__construct ();
		$this->load->database ();
		$ns = 'http://' . $_SERVER['HTTP_HOST'] . '/coopera/index.php/soapserver';
		$this -> load -> library("Nusoap_library");
		// load nusoap toolkit library in controller
		$this -> nusoap_server = new soap_server();
		// create soap server object
		$this -> nusoap_server -> configureWSDL("Primera Prueba", $ns,$ns.'/index/addnumbers');
		// wsdl cinfiguration
		$this -> nusoap_server -> wsdl -> schemaTargetNamespace = $ns;
		// server namespace
		$input_array = array ('a' => "xsd:string", 'b' => "xsd:string"); // "addnumbers" method parameters
		$return_array = array ("return" => "xsd:string");
		$this->nusoap_server->register('addnumbers', $input_array, $return_array, "urn:SOAPServerWSDL", "urn:".$ns."/addnumbers", "rpc", "encoded", "Suma dos numeros");
		
	}
	
	function index(){
		function addnumbers($a,$b){
	        $c = $a + $b;
	        return $c;
	    }
    	$this->nusoap_server->service(file_get_contents("php://input")); // read raw data from request body
	}

}
?>