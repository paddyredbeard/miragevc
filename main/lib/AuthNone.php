<?php

abstract class AuthNone extends Controller {

    public function __construct() {
	parent::__construct() ;
    }

    public function __destruct() {
	parent::__destruct() ;
    }

    public function authenticated( $authUserKey ) {
	return true ;
    }

}// end AuthNone

?>
