<?php

/**
 * GetVars.php
 *
 * @package	MirageVC
 * @author	Patrick Barabe
 * @copyright	Copyright &copy; 2007 Patrick Barabe
 * @license	http://creativecommons.org/licenses/GPL/2.0/ GNU Public License
 *
 */


class GetVars extends HttpRequestVars {

    public function __construct() {
	$this->_keys = array_keys( $_GET ) ;
    }

    public function __destruct() {}
    private function __set( $aVar, $aVal ) {} 

    private function __get( $aVar ) {

	$returnVar = null ;

	if( !empty( $_GET[$aVar] )) {
	    $returnVar = $_GET[$aVar] ;
	}

	return $returnVar ;
	    
    }

}// end GetVars

?>
