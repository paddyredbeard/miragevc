<?php

/**
 * PostVars.php
 *
 * @package	MirageVC
 * @author	Patrick Barabe
 * @copyright	Copyright &copy; 2007 Patrick Barabe
 * @license	http://creativecommons.org/licenses/GPL/2.0/ GNU Public License
 *
 */


/**
 * PostVars
 *
 * A wrapper around the $_POST array.
 */
class PostVars extends HttpRequestVars {

    public function __construct() {
	$this->_keys = array_keys( $_POST ) ;
    }

    public function __destruct() {}
    private function __set( $aVar, $aVal ) {} 

    private function __get( $aVar ) {

	$returnVar = null ;

	if( !empty( $_POST[$aVar] )) {
	    $returnVar = $_POST[$aVar] ;
	}

	return $returnVar ;
	    
    }

}// end PostVars

?>
