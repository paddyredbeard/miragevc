<?php

/**
 * AuthNone.php
 *
 * @package	MirageVC
 * @subpackage	MirageVC4
 * @author	Patrick Barabe
 * @copyright	Copyright &copy; 2007 Patrick Barabe
 * @license	http://creativecommons.org/licenses/GPL/2.0/ GNU Public License
 * @filesource
 *
 */


class AuthNone extends Controller {

    function authenticated( $authUserKey ) {
	return true ;
    }

}// end AuthNone

?>
