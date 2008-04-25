<?php

/**
 * SessionVars.php
 *
 * @package	MirageVC
 * @author	Patrick Barabe
 * @copyright	Copyright &copy; 2007 Patrick Barabe
 * @license	http://creativecommons.org/licenses/GPL/2.0/ GNU Public License
 *
 */


/**
 * SessionVars
 *
 * A wrapper around the $_SESSION array.
 */
class SessionVars extends HttpRequestVars {

	public function __construct() {
		$this->_keys = array_keys( $_SESSION ) ;
	}

	public function __destruct() {}
	private function __set( $aVar, $aVal ) {} 

	private function __get( $aVar ) {

		$returnVar = null ;

		if( !empty( $_SESSION[$aVar] )) {
			if( is_array( $_SESSION[$aVar] )) {
				$returnVar = $_SESSION[$aVar] ;
			} else {
				$returnVar = trim( $_SESSION[$aVar] ) ;
			}
		}

		return $returnVar ;

	}

}// end SessionVars

?>
