<?php

class AuthUser extends Controller {

    function authenticated( $authUserKey ) {

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
