<?php

class GetVars extends HttpRequestVars {

	function GetVars() {
		$this->_request = $_GET ;
		$this->populate() ;
	}

}// end GetVars

?>
