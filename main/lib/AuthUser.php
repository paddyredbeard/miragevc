<?php

abstract class AuthUser extends Controller {

    public function __construct() {
	parent::__construct() ;
    }

    public function __destruct() {
	parent::__destruct() ;
    }

    public function authenticated( $authUserKey ) {

	$_output = false ;

	if( !empty( $_SESSION['authUserKey'] )) {
	    $theAuthKey = $_SESSION['authUserKey'] ;

	    if( $theAuthKey == $authUserKey ) {
		if( !empty( $_SESSION['authUserId'] )) {
		    $_output = true ;
		}
	    }
	}

	return $_output ;
    }

}// end AuthUser

?>
