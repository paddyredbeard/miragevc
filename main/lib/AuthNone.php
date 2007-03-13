<?php

/**
 * AuthNone.php
 *
 * @package	MirageVC
 * @author	Patrick Barabe
 * @copyright	Copyright &copy; 2007 Patrick Barabe
 * @license	http://creativecommons.org/licenses/GPL/2.0/ GNU Public License
 *
 */


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
