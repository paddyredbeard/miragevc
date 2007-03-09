<?php

class PostVars extends HttpRequestVars {

    function PostVars() {
	    $this->_request = $_POST ;
	    $this->populate() ;
    }

}// end PostVars

?>
