<?php

/**
 * PostVars.php
 *
 * @package	MirageVC
 * @subpackage	MirageVC4
 * @author	Patrick Barabe
 * @copyright	Copyright &copy; 2007 Patrick Barabe
 * @license	http://creativecommons.org/licenses/GPL/2.0/ GNU Public License
 *
 */


class PostVars extends HttpRequestVars {

    function PostVars() {
	    $this->_request = $_POST ;
	    $this->populate() ;
    }

}// end PostVars

?>
