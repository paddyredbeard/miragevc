<?php

/**
 * AuthUser.php
 *
 * @package	MirageVC
 * @subpackage	MirageVC4
 * @author	Patrick Barabe
 * @copyright	Copyright &copy; 2007 Patrick Barabe
 * @license	http://creativecommons.org/licenses/GPL/2.0/ GNU Public License
 *
 */


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
