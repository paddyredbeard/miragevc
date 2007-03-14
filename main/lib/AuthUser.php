<?php

/**
 * AuthUser.php
 *
 * @package	MirageVC
 * @author	Patrick Barabe
 * @copyright	Copyright &copy; 2007 Patrick Barabe
 * @license	http://creativecommons.org/licenses/GPL/2.0/ GNU Public License
 *
 */

/**
 * AuthUser
 *
 * An abstract {@link Controller} class that requires an
 * authenticated user in order to be instantiated.
 */
abstract class AuthUser extends Controller {

    public function __construct() {
	parent::__construct() ;
    }

    public function __destruct() {
	parent::__destruct() ;
    }


    /**
     * authenticated
     *
     * Method used to determine whether a user may access the requested URI.
     *
     * @param string $authUserKey The SESSION key that stores a userid.
     * @return bool True if a session has started and the application has stored a userid.
     */
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
