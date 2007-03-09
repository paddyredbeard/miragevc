<?php

class HttpRequestVars {

	var $_request ;

	function HttpRequestVars() {
		$this->_request = null ;
	}

	function populate() {
		if( !empty( $this->_request )) {
			foreach( $this->_request as $key=>$val ) {
				$this->{$key} = $val ;
			}
		}
	}

}// end HttpRequestVars

?>
